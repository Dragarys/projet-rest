<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DishImageTest extends TestCase
{
    use RefreshDatabase;

    private function authHeadersFor(User $user, string $plainToken = 'test-token'): array
    {
        $user->api_token = hash('sha256', $plainToken);
        $user->save();

        return ['Authorization' => 'Bearer ' . $plainToken];
    }

    public function test_admin_can_upload_image_for_dish(): void
    {
        Storage::fake('public');

        $admin = User::create(['role' => 'admin', 'name' => 'AdminImg', 'email' => 'admin_img@example.com', 'password' => Hash::make('secret')]);
        $headers = $this->authHeadersFor($admin);

        // GD is not available in CI/local test environment; create a fake file with image mime type
        $file = UploadedFile::fake()->create('dish.jpg', 100, 'image/jpeg');

        $response = $this->post('/api/dishes', [
            'name' => 'DishWithImage',
            'price' => 5.0,
            'image' => $file,
        ], $headers);

        $response->assertStatus(201)->assertJsonStructure(['id', 'name', 'image_url']);
        $this->assertStringContainsString('/storage/', $response->json('image_url'));

        // ensure file stored
        Storage::disk('public')->assertExists(str_replace('/storage/', '', $response->json('image_url')));
    }
}
