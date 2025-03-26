<?php

namespace App\Http\Requests\Api;

use App\DTO\AuthenticateDTO;
use Illuminate\Foundation\Http\FormRequest;

class AuthenticateFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->guest();
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'exists:users,email'],
            'password' => ['required']
        ];
    }

    public function toDto(): AuthenticateDTO
    {
        return new AuthenticateDTO(
            $this->input('email'),
            $this->input('password')
        );
    }

}
