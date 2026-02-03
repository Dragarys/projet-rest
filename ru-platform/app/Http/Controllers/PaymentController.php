<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function pay(Request $request, Order $order)
    {
        $user = $request->user();
        if ($user->role === 'student' && $order->user_id !== $user->id) {
            abort(403);
        }

        if ($order->status === 'paid') {
            return response()->json(['message' => 'Order already paid']);
        }

        $payment = Payment::updateOrCreate(
            ['order_id' => $order->id],
            ['status' => 'paid', 'method' => 'simulated', 'amount' => $order->total_amount]
        );

        $order->status = 'paid';
        $order->save();

        return ['order' => $order, 'payment' => $payment];
    }
}
