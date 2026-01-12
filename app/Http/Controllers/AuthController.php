<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\LoginUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function signup(CreateUserRequest $request)
    {
        $validated = $request->validated();
        logger($validated);

        $user = User::create($validated);

        Auth::login($user);
        $request->session()->regenerate();

        return [
            'user' => $user,
        ];
    }

    public function login(LoginUserRequest $request)
    {
        $validated = $request->validated();

        if (Auth::attempt($validated)) {
            $request->session()->regenerate();

            return [
                'user' => $request->user(),
            ];
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function me(Request $request)
    {
        return $request->user();
    }
}
