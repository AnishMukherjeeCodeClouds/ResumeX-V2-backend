<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class CreateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'min:2', 'max:255'],
            'username' => ['required', 'min:3', 'max:30', 'regex:/^[a-zA-Z0-9_]+$/'],
            'email' => ['required', 'email'],
            'password' => ['required', Password::min(8)->max(128)->mixedCase()->numbers()->symbols()],
            'confirmPassword' => ['required', 'same:password'],
        ];
    }
}
