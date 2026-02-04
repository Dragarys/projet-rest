<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InfoTest extends TestCase
{
    use RefreshDatabase;

    public function test_info_endpoint_returns_expected_fields(): void
    {
        $response = $this->getJson('/api/info');
        $response->assertStatus(200)
            ->assertJsonStructure(['storage_link_exists', 'image_upload_endpoint', 'example_image_url']);
    }
}
