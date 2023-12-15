<?php

namespace App\Console\Commands;

use App\Models\Agent;
use App\Models\Courier;
use App\Models\Order;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Console\Helper\ProgressBar;

class Setup extends Command
{
    private ProgressBar $bar;

    protected $signature = 'setup';

    protected $description = 'Create and install all necessary tables, fields or data for running application';

    public function handle(): int
    {
        $this->bar = $this->output->createProgressBar(2);
        $this->bar->start();

        $this->migrate();
        $this->seeder();
        $this->finish();

        return Command::SUCCESS;
    }

    //--------------------------------|| Private Methods ||--------------------------------

    private function migrate(): void
    {
        Artisan::call('migrate:refresh -q');
        $this->bar->advance();
    }

    private function finish(): void
    {
        $this->bar->finish();
        $this->newLine();

        $vendors = Vendor::all();
        $users = User::all();
        $agents = Agent::all();
        $couriers = Courier::all();
        $orders = Order::all();

        $this->info('Vendors : ');
        $this->table(
            ['ID', 'name', 'Created At', 'Updated At'],
            $vendors
        );

        $this->info('Users : ');
        $this->table(
            ['ID', 'name', 'Created At', 'Updated At'],
            $users
        );

        $this->info('Agents : ');
        $this->table(
            ['ID', 'name', 'Created At', 'Updated At'],
            $agents
        );

        $this->info('Couriers : ');
        $this->table(
            ['ID', 'name', 'Created At', 'Updated At'],
            $couriers
        );

        $this->info('Orders : ');
        $this->table(
            ['ID', 'vendor_id', 'user_id', 'status', 'time_delivery', 'date_delivery', 'Created At', 'Updated At'],
            $orders
        );
        $this->info('Current Date : ' . now());
        $this->newLine();
    }

    private function systemicAgency(): void
    {
        $stub = agencyDetailsStub();
        $stub['type'] = Type::SYSTEMIC->value;
        $systemicAgency =
            [
                'title' => 'آژانس هتل',
                'mobile' => $this->systemicAgencyMobile,
                'mobile_verified_at' => now(),
                'password' => $this->defaultPassword,
                'role' => Role::AGENCY->value,
                'details' => $stub,
            ];
        UserFacade::upsert($systemicAgency);
        $this->bar->advance();
    }

    private function seeder(): void
    {
        Artisan::call('db:seed');
        $this->bar->advance();
    }
}
