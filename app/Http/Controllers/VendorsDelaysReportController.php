<?php

namespace App\Http\Controllers;

use App\Facades\Response;
use App\Facades\Vendor;
use App\Http\Resources\VendorsDelayReportResource;

class VendorsDelaysReportController extends Controller
{
    public function __invoke()
    {
        $report = Vendor::DelaysReport();

        return Response::success()->data(VendorsDelayReportResource::collection($report));
    }
}
