<?php

namespace App\Http\Requests\Settings;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'bio' => ['nullable', 'string', 'max:255'],
            'birthday' => ['nullable', 'date'],
            'profile_photo' => [
                'nullable',
                'file',
                'mimes:jpg,jpeg,png,gif,webp',
                'max:2048',
            ],
            'remove_photo' => ['boolean'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }

    
}
