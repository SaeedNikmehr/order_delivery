<?php

namespace App\Repositories;

use App\Enums\Models\Order\Status;
use App\Models\Order;
use Exception;
use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class OrderRepository
{
    /**
     * @throws Exception
     */
    public function shouldBelongsToUser(Order $order, int $userId): void
    {
        if ($order->user_id !== $userId) {
            throw new Exception('Order does not belongs to you');
        }
    }

    /**
     * @throws Exception
     */
    public function shouldBeDelayed(Order $order): void
    {
        match (true) {
            $order->status->isInProgress() => $this->manageInProgress($order),
            $order->status->isDelayed() => $this->manageDelayed($order),
            $order->status->isDelivered() => $this->manageDelivered(),
        };
    }

    public function handleDelayedOrder(Order $order): array
    {
        if ($this->canEstimateNewTimeDelivery($order)) {
            $result = $this->sendRequest();
            $message = 'New time for you order has been calculated, thank you for your patience';
            $data = ['new_time_delivery' => $result['data']['new_time_delivery']];
        } else {
            $order->delayQueue()->create();
            $message = 'Your order pushed in the queue and will be investigated by one of our agents';
            $data = [];
        }
        $order->delayReports()->create(['time_delivery' => $result['data']['new_time_delivery'] ?? null]);

        return [$message, $data];
    }

    //--------------------------------|| Private Methods ||--------------------------------

    /**
     * @throws Exception
     */
    private function manageInProgress(Order $order): void
    {
        if ($order->date_delivery > now()) {
            throw new Exception('Can not submit delay for this order, there is still time to deliver');
        }
        $order->update(['status' => Status::DELAYED]);
    }

    /**
     * @throws Exception
     */
    private function manageDelayed(Order $order): void
    {
        $inProgressDelayedDelivery = $order->delayReports()->where('date_delivery', '>', now())->first();
        if (!is_null($inProgressDelayedDelivery)) {
            throw new Exception('A delay has been registered before and still in progress');
        }
        if (!is_null($order->delayQueue)) {
            throw new Exception('Your order is in queue for investigating, please be patient');
        }
    }

    /**
     * @throws Exception
     */
    private function manageDelivered(): void
    {
        throw new Exception('The order has been completed');
    }

    private function sendRequest(): PromiseInterface|Response
    {
        return Http::connectTimeout(20)
            ->timeout(40)
            ->retry(
                2,
                500,
                function ($exception, $request) {
                    return $exception instanceof ConnectionException;
                },
                throw: false
            )
            ->get(config('general.new_time_delivery_url'));
    }

    private function canEstimateNewTimeDelivery(Order $order): bool
    {
        if (!is_null($order->trip) && !$order->trip->status->isDelivered()) {
            return true;
        }

        return false;
    }

}
