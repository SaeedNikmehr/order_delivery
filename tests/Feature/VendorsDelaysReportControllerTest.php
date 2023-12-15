<?php

use App\Facades\Vendor;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class VendorsDelaysReportControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_get_vendors_delays_report(): void
    {
        Vendor::shouldReceive('DelaysReport')->once()->andReturn(new Collection());

        $response = $this->getJson(route('vendors.delays.report'));
        $this->assertIsArray($response['data']);
        $response->assertJson(fn(AssertableJson $json) => $json->where('status', 'success')
            ->has('data')
            ->has('errors')
            ->etc()
        );
    }

}
