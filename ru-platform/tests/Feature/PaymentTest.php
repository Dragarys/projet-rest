<?php

namespace Tests\Feature;

use App\Models\Menu;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PaymentTest extends TestCase
{
    use RefreshDatabase;

    private function authHeadersFor(User $user, string $plainToken = 'test-token'): array
    {
        $user->api_token = hash('sha256', $plainToken);
        $user->save();

        return ['Authorization' => 'Bearer '.$plainToken];
    }

    public function test_cannot_pay_twice(): void
    {
        $user = User::create(['role' => 'student', 'name' => 'S', 'email' => 's_pay@example.com', 'password' => Hash::make('secret')]);

        $order = Order::create(['user_id' => $user->id, 'menu_id' => Menu::create(['menu_date' => '2026-02-03', 'service' => 'dinner'])->id, 'status' => 'reserved', 'total_amount' => 15.0]);

        $headers = $this->authHeadersFor($user);
        $first = $this->postJson('/api/orders/'.$order->id.'/pay', [], $headers);
        $first->assertStatus(200)->assertJsonFragment(['status' => 'paid']);

        $second = $this->postJson('/api/orders/'.$order->id.'/pay', [], $headers);
        $second->assertStatus(200)->assertJsonFragment(['message' => 'Order already paid']);
    }
}
