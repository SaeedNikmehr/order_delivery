<?php

namespace App\Exceptions;

use App\Facades\Response;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response as HttpResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class CustomHandler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    public function render($request, Throwable $e)
    {
        if ($request->is('api/*')) {
            $e = $this->prepareException($this->mapException($e));

            return Response::error()
                ->message($this->message($e))
                ->errors($this->errors($e))
                ->code($this->status($e))
                ->get();
        }

        return parent::render($request, $e);
    }

    //--------------------------------|| Private Methods ||--------------------------------

    private function message(Throwable $e): string
    {
        return match (true) {
            $e instanceof NotFoundHttpException => 'Entity Not Found !',
            $e instanceof HttpResponseException, $e instanceof ValidationException => $e->getMessage(),
            default => $this->convertExceptionToArray($e)['message'],
        };
    }

    private function errors(Throwable $e): array
    {
        return match (true) {
            $e instanceof ValidationException => $e->errors(),
            default => [],
        };
    }

    private function status(Throwable $e): int
    {
        return match (true) {
            $e instanceof HttpResponseException => $e->getResponse()->getStatusCode(),
            $e instanceof ValidationException => $e->status,
            default => $this->isHttpException($e) ? $e->getStatusCode() : HttpResponse::HTTP_INTERNAL_SERVER_ERROR,
        };
    }
}
