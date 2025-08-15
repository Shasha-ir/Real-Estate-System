<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'max:255', Rule::unique(User::class, 'email')->ignore($this->user()->id)],
            'username' => ['required', 'string', 'max:255', Rule::unique(User::class, 'username')->ignore($this->user()->id)],
        ];
    }
}
