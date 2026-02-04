<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Ingredient;
use App\Models\Menu;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminCrudTest extends TestCase
{
    use RefreshDatabase;

    private function authHeadersFor(User $user, string $plainToken = 'test-token'): array
    {
        $user->api_token = hash('sha256', $plainToken);
        $user->save();

        return ['Authorization' => 'Bearer ' . $plainToken];
    }

    public function test_category_crud(): void
    {
        $admin = User::create(['role' => 'admin', 'name' => 'Admin', 'email' => 'admin_cat@example.com', 'password' => Hash::make('secret')]);
        $headers = $this->authHeadersFor($admin);

        $create = $this->postJson('/api/categories', ['name' => 'TestCat'], $headers);
        $create->assertStatus(201)->assertJsonFragment(['name' => 'TestCat']);

        $categoryId = $create->json('id');

        $update = $this->putJson('/api/categories/' . $categoryId, ['name' => 'UpdatedCat'], $headers);
        $update->assertStatus(200)->assertJsonFragment(['name' => 'UpdatedCat']);

        $delete = $this->deleteJson('/api/categories/' . $categoryId, [], $headers);
        $delete->assertStatus(200);
    }

    public function test_ingredient_crud(): void
    {
        $admin = User::create(['role' => 'admin', 'name' => 'Admin2', 'email' => 'admin_ing@example.com', 'password' => Hash::make('secret')]);
        $headers = $this->authHeadersFor($admin);

        $create = $this->postJson('/api/ingredients', ['name' => 'Salt', 'unit' => 'g'], $headers);
        $create->assertStatus(201)->assertJsonFragment(['name' => 'Salt']);

        $id = $create->json('id');

        $show = $this->getJson('/api/ingredients/' . $id, $headers);
        $show->assertStatus(200)->assertJsonStructure(['ingredient', 'stock']);

        $update = $this->putJson('/api/ingredients/' . $id, ['name' => 'Sea Salt'], $headers);
        $update->assertStatus(200)->assertJsonFragment(['name' => 'Sea Salt']);

        $delete = $this->deleteJson('/api/ingredients/' . $id, [], $headers);
        $delete->assertStatus(200);
    }

    public function test_menu_crud_as_admin(): void
    {
        $admin = User::create(['role' => 'admin', 'name' => 'Admin3', 'email' => 'admin_menu@example.com', 'password' => Hash::make('secret')]);
        $headers = $this->authHeadersFor($admin);

        $create = $this->postJson('/api/menus', ['menu_date' => '2026-02-05', 'service' => 'lunch'], $headers);
        $create->assertStatus(201)->assertJsonFragment(['service' => 'lunch']);

        $id = $create->json('id');

        $update = $this->putJson('/api/menus/' . $id, ['service' => 'dinner'], $headers);
        $update->assertStatus(200)->assertJsonFragment(['service' => 'dinner']);

        $delete = $this->deleteJson('/api/menus/' . $id, [], $headers);
        $delete->assertStatus(200);
    }

    public function test_user_crud_by_admin(): void
    {
        $admin = User::create(['role' => 'admin', 'name' => 'Admin4', 'email' => 'admin_user@example.com', 'password' => Hash::make('secret')]);
        $headers = $this->authHeadersFor($admin);

        $create = $this->postJson('/api/users', ['role' => 'student', 'name' => 'U1', 'email' => 'u1@example.com', 'password' => 'secret'], $headers);
        $create->assertStatus(201)->assertJsonFragment(['email' => 'u1@example.com']);

        $id = $create->json('id');

        $index = $this->getJson('/api/users', $headers);
        $index->assertStatus(200)->assertJsonStructure(['data']);

        $update = $this->putJson('/api/users/' . $id, ['name' => 'U1 Updated'], $headers);
        $update->assertStatus(200)->assertJsonFragment(['name' => 'U1 Updated']);

        $delete = $this->deleteJson('/api/users/' . $id, [], $headers);
        $delete->assertStatus(200);
    }
}
