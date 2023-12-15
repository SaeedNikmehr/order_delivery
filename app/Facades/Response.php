<?php

namespace App\Facades;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Responsable;

/**
 * @method static self success(string $message = '')
 * @method static static error(string $message = '')
 * @method static static message(string $message = '')
 * @method static static data(array|Arrayable|Responsable $input = [])
 *
 * @see \App\Utils\Response
 */
class Response extends BaseFacade
{
    const key = 'response.facade';
}
