<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ApiTokenAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        $header = $request->header('Authorization', '');
        if (!str_starts_with($header, 'Bearer ')) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $token = trim(substr($header, 7));
        if ($token === '') {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $hash = hash('sha256', $token);
        $user = User::where('api_token', $hash)->first();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        Auth::setUser($user);
        return $next($request);
    }
}
