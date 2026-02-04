<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DocsUiTest extends TestCase
{
    use RefreshDatabase;

    public function test_docs_ui_is_available(): void
    {
        $response = $this->get('/api/docs');
        $response->assertStatus(200);
        $response->assertSee('SwaggerUIBundle');
    }
}
