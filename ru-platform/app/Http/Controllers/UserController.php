<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    private function ensureAdmin(Request $request): void
    {
        abort_unless($request->user()?->role === 'admin', 403);
    }

    public function index(Request $request)
    {
        $this->ensureAdmin($request);
        return User::paginate(20);
    }

    public function store(Request $request)
    {
        $this->ensureAdmin($request);

        $data = $request->validate([
            'role' => 'required|in:student,staff,admin',
            'name' => 'required|string|max:120',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'student_id' => 'nullable|string|max:50',
            'staff_id' => 'nullable|string|max:50',
        ]);

        $data['password'] = Hash::make($data['password']);
        return User::create($data);
    }

    public function show(Request $request, User $user)
    {
        $this->ensureAdmin($request);
        return $user;
    }

    public function update(Request $request, User $user)
    {
        $this->ensureAdmin($request);

        $data = $request->validate([
            'role' => 'sometimes|in:student,staff,admin',
            'name' => 'sometimes|string|max:120',
            'email' => 'sometimes|email|unique:users,email,' . $user->id,
            'password' => 'sometimes|string|min:6',
            'student_id' => 'nullable|string|max:50',
            'staff_id' => 'nullable|string|max:50',
        ]);

        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        $user->update($data);
        return $user;
    }

    public function destroy(Request $request, User $user)
    {
        $this->ensureAdmin($request);
        $user->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
