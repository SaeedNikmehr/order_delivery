<?php

namespace App\Facades;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\JsonResponse;

/**
 * @method static self success(string $message = '')
 * @method static static error(string $message = '')
 * @method static static message(string $message = '')
 * @method static static data(array|Arrayable|Responsable $input = [])
 * @method static static errors(array $input = [])
 * @method static static code(int $code)
 * @method static static headers(array $inputs = [])
 * @method static static header(string $name, string $value)
 * @method static JsonResponse get()
 * @method static bool successful()
 * @method static null|int getCode()
 * @method static null|int getData()
 *
 * @see \App\Utils\Response
 */
class Response extends BaseFacade
{
    const key = 'response.facade';
}
