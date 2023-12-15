<?php

namespace Tests\Feature;

use App\Facades\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class ReportDelayControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_throws_exception_if_order_does_not_belonged_to_user(): void
    {
        $order = \App\Models\Order::factory()->create();
        $user = User::factory()->create();

        Order::shouldReceive('shouldBelongsToUser')->andThrow(new \Exception(), 'Order does not belongs to you');
        Order::shouldReceive('shouldBeDelayed')->never();
        Order::shouldReceive('handleDelayedOrder')->never();

        $response = $this->postJson(route('orders.report.delay', ['order' => $order->id]), [
            'user_id' => $user->id,
        ]);

        $response->assertJson(fn(AssertableJson $json) => $json->where('status', 'error')
            ->has('data')
            ->has('errors')
            ->etc()
        );
    }

    public function test_throws_exception_if_does_not_be_delayed(): void
    {
        $order = \App\Models\Order::factory()->create();
        $user = User::factory()->create();

        Order::shouldReceive('shouldBelongsToUser')->andReturnNull();
        Order::shouldReceive('shouldBeDelayed')->andThrow(new \Exception());
        Order::shouldReceive('handleDelayedOrder')->never();

        $response = $this->postJson(route('orders.report.delay', ['order' => $order->id]), [
            'user_id' => $user->id,
        ]);

        $response->assertJson(fn(AssertableJson $json) => $json->where('status', 'error')
            ->has('data')
            ->has('errors')
            ->etc()
        );
    }

    public function test_can_report_a_delay_and_get_a_new_time_delivery(): void
    {
        $message = 'New time for you order has been calculated, thank you for your patience';
        $data = ['new_time_delivery' => 50];
        $order = \App\Models\Order::factory()->create();
        $user = User::factory()->create();

        Order::shouldReceive('shouldBelongsToUser')->andReturnNull();
        Order::shouldReceive('shouldBeDelayed')->andReturnNull();
        Order::shouldReceive('handleDelayedOrder')->andReturn([$message, $data]);

        $response = $this->postJson(route('orders.report.delay', ['order' => $order->id]), [
            'user_id' => $user->id,
        ]);

        $response->assertSuccessful();
        $response->assertJson(fn(AssertableJson $json) => $json->where('status', 'success')
            ->has('data', fn($dataJson) => $dataJson
                ->where('new_time_delivery', $data['new_time_delivery'])
                ->etc()
            )
            ->has('errors')
            ->etc()
        );
    }

    public function test_goes_to_delay_queue_if_could_not_calculate_a_new_time_delivery(): void
    {
        $message = 'Your order pushed in the queue and will be investigated by one of our agents';
        $data = [];
        $order = \App\Models\Order::factory()->create();
        $user = User::factory()->create();

        Order::shouldReceive('shouldBelongsToUser')->andReturnNull();
        Order::shouldReceive('shouldBeDelayed')->andReturnNull();
        Order::shouldReceive('handleDelayedOrder')->andReturn([$message, $data]);

        $response = $this->postJson(route('orders.report.delay', ['order' => $order->id]), [
            'user_id' => $user->id,
        ]);

        $response->assertSuccessful();
        $response->assertJson(fn(AssertableJson $json) => $json->where('status', 'success')
            ->has('data')
            ->has('errors')
            ->etc()
        );
    }
}
