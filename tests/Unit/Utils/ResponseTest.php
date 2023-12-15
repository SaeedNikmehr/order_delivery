<?php

namespace Tests\Unit\Utils;

use App\Utils\Response;
use Illuminate\Http\JsonResponse;
use PHPUnit\Framework\TestCase;

class ResponseTest extends TestCase
{
    public function test_returns_a_jsonResponse_object()
    {
        $builder = new Response();
        $result = $builder->success('ok')->get();
        $this->assertInstanceOf(JsonResponse::class, $result);
    }

    public function test_returns_a_proper_structured_response()
    {
        $builder = new Response();
        $result = $builder->success('ok')->toArray();
        $this->assertIsArray($result);
        $this->assertArrayHasKey('status', $result);
        $this->assertArrayHasKey('message', $result);
        $this->assertArrayHasKey('data', $result);
        $this->assertArrayHasKey('errors', $result);
        $this->assertCount(4, $result);
    }

    public function test_error_method_fill_status_index_with_error_value()
    {
        $builder = new Response();
        $result = $builder->error('error')->toArray();
        $this->assertEquals('error', $result['status']);
    }

    public function test_message_method_fill_related_index_with_given_value()
    {
        $builder = new Response();
        $result = $builder->success('ok')->message('test message')->toArray();
        $this->assertEquals('test message', $result['message']);
    }

    public function test_data_method_fill_related_index_with_given_value()
    {
        $builder = new Response();
        $result = $builder->success('ok')->data(['test data'])->toArray();
        $this->assertEquals(['test data'], $result['data']);
    }

    public function test_errors_method_fill_related_index_with_given_value()
    {
        $builder = new Response();
        $result = $builder->error('error')->errors(['error1' => 'error description'])->toArray();
        $this->assertEquals(['error1' => 'error description'], $result['errors']);
    }

    public function test_can_change_its_headers()
    {
        $builder = new Response();
        $result = $builder->success('ok')->headers(['IP' => '127.0.0.1'])->get();
        $this->assertEquals('127.0.0.1', $result->headers->get('IP'));
    }

    public function test_can_change_its_specific_header()
    {
        $builder = new Response();
        $result = $builder->success('ok')->header('IP', '127.0.0.1')->get();
        $this->assertEquals('127.0.0.1', $result->headers->get('IP'));
    }
}

