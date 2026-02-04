<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DocsTest extends TestCase
{
    use RefreshDatabase;

    public function test_api_docs_endpoint_returns_yaml(): void
    {
        $response = $this->get('/api/docs/api.yaml');
        $response->assertStatus(200);
        $this->assertStringContainsString('openapi:', $response->getContent());
    }
}
