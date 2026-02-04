<?php

namespace Tests\Feature;

use App\Models\Menu;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ReservationTest extends TestCase
{
    use RefreshDatabase;

    private function authHeadersFor(User $user, string $plainToken = 'test-token'): array
    {
        $user->api_token = hash('sha256', $plainToken);
        $user->save();

        return ['Authorization' => 'Bearer '.$plainToken];
    }

    public function test_student_can_cancel_unpaid_order(): void
    {
        $user = User::create([
            'role' => 'student',
            'name' => 'Student',
            'email' => 's_cancel@example.com',
            'password' => Hash::make('secret'),
        ]);

        $order = Order::create([
            'user_id' => $user->id,
            'menu_id' => Menu::create(['menu_date' => '2026-02-03', 'service' => 'lunch'])->id,
            'status' => 'reserved',
            'total_amount' => 10.0,
        ]);

        $headers = $this->authHeadersFor($user);
        $response = $this->postJson('/api/orders/'.$order->id.'/cancel', [], $headers);

        $response->assertStatus(200)->assertJsonFragment(['status' => 'cancelled']);
        $this->assertDatabaseHas('orders', ['id' => $order->id, 'status' => 'cancelled']);
    }

    public function test_cannot_cancel_paid_order(): void
    {
        $user = User::create([
            'role' => 'student',
            'name' => 'Student',
            'email' => 's_paid@example.com',
            'password' => Hash::make('secret'),
        ]);

        $order = Order::create([
            'user_id' => $user->id,
            'menu_id' => Menu::create(['menu_date' => '2026-02-03', 'service' => 'dinner'])->id,
            'status' => 'paid',
            'total_amount' => 20.0,
        ]);

        $headers = $this->authHeadersFor($user);
        $response = $this->postJson('/api/orders/'.$order->id.'/cancel', [], $headers);

        $response->assertStatus(422);
        $this->assertDatabaseHas('orders', ['id' => $order->id, 'status' => 'paid']);
    }
}
