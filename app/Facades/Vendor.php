<?php

namespace App\Facades;

use Illuminate\Database\Eloquent\Collection;

/**
 * @method static Collection|array DelaysReport()
 *
 * @see \App\Repositories\VendorRepository
 */
class Vendor extends BaseFacade
{
    const key = 'vendor.facade';
}
