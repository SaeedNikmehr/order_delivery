<?php

namespace App\Utils;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\JsonResponse;

class Response implements Arrayable
{
    private ?int $code = null;

    private ?string $status = null;

    private string $message = '';

    private array $data = [];

    private array $errors = [];

    private array $headers = [];

    public function success(string $message = ''): static
    {
        $this->reset();
        $this->message = $message;
        $this->status = 'success';

        if (is_null($this->code)) {
            $this->code = 200;
        }

        return $this;
    }

    public function error(string $message = ''): static
    {
        $this->reset();
        $this->message = $message;
        $this->status = 'error';

        if (is_null($this->code)) {
            $this->code = 400;
        }

        return $this;
    }

    public function message(string $message = ''): static
    {
        $this->message = $message;

        return $this;
    }

    public function data(array|Arrayable|Responsable $input = []): static
    {
        if ($input instanceof Arrayable) {
            $input = $input->toArray();
        }
        if ($input instanceof Responsable) {
            $input = collect($input)->toArray();
        }
        $this->data = $input;

        return $this;
    }

    public function errors(array $input = []): static
    {
        $this->errors = $input;

        return $this;
    }

    public function code(int $code): static
    {
        $this->code = $code;

        return $this;
    }

    public function headers(array $inputs = []): static
    {
        $this->headers = $inputs;

        return $this;
    }

    public function header(string $name, string $value): static
    {
        $this->headers[$name] = $value;

        return $this;
    }

    public function get(): JsonResponse
    {
        return new JsonResponse($this->toArray(), $this->code, $this->headers);
    }

    public function successful(): bool
    {
        return $this->code >= 200 && $this->code < 300;
    }

    public function getCode(): ?int
    {
        return $this->code;
    }

    public function getData(): array
    {
        return $this->data;
    }

    /**
     * Get the instance as an array.
     */
    public function toArray(): array
    {
        return [
            'status' => $this->status,
            'message' => $this->message,
            'data' => $this->data,
            'errors' => $this->errors,
        ];
    }

    //--------------------------------|| Private Methods ||--------------------------------

    private function reset(): void
    {
        $this->code = null;
        $this->status = null;
        $this->message = '';
        $this->data = [];
        $this->errors = [];
        $this->headers = [];
    }

}
