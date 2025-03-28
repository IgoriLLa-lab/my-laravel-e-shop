<?php

namespace App\Http\Requests\Api;

use App\DTO\AuthenticateDTO;
use Illuminate\Foundation\Http\FormRequest;

class RefreshTokenFormRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'refresh_token' => ['required', 'string'],
        ];
    }
}
