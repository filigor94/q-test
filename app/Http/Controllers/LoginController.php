<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\ClientService;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function __construct(private ClientService $clientService)
    {
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (auth()->attempt($request->only(['email', 'password']))) {
            return to_route('profile.index');
        }

        return back()->withErrors(['message' => 'Invalid credentials']);
    }

    public function logout(Request $request)
    {
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
