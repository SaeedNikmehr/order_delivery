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

    public function handle(): void
    {
        $this->bar = $this->output->createProgressBar(2);
        $this->bar->start();
        $this->migrate();
        $this->seeder();
        $this->finish();
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
        $this->showVendorsTable();
        $this->showUsersTable();
        $this->showAgentsTable();
        $this->showCouriersTable();
        $this->showOrdersTable();
        $this->info('Current Date : ' . now());
        $this->newLine();
    }

    private function seeder(): void
    {
        Artisan::call('db:seed');
        $this->bar->advance();
    }

    private function showVendorsTable(): void
    {
        $vendors = Vendor::all();
        $this->info('Vendors : ');
        $this->table(
            ['ID', 'name', 'Created At', 'Updated At'],
            $vendors
        );
    }

    private function showUsersTable(): void
    {
        $users = User::all();
        $this->info('Users : ');
        $this->table(
            ['ID', 'name', 'Created At', 'Updated At'],
            $users
        );
    }

    private function showAgentsTable(): void
    {
        $agents = Agent::all();
        $this->info('Agents : ');
        $this->table(
            ['ID', 'name', 'Created At', 'Updated At'],
            $agents
        );
    }

    private function showCouriersTable(): void
    {
        $couriers = Courier::all();
        $this->info('Couriers : ');
        $this->table(
            ['ID', 'name', 'Created At', 'Updated At'],
            $couriers
        );
    }

    private function showOrdersTable(): void
    {
        $orders = Order::all();
        $this->info('Orders : ');
        $this->table(
            ['ID', 'vendor_id', 'user_id', 'status', 'time_delivery', 'date_delivery', 'Created At', 'Updated At'],
            $orders
        );
    }
}
