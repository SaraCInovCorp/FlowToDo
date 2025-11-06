<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use App\Helpers\ActivityLogger;
use Illuminate\Support\Facades\Hash;


class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class),
            ],
            'password' => $this->passwordRules(),
            'bio' => ['nullable', 'string', 'max:500'],
            'birthday' => ['nullable', 'date'],
            'profile_photo' => ['nullable', 'image', 'max:2048'],
        ])->validate();

        $photoPath = null;
        if (isset($input['profile_photo'])) {
            $photoPath = $input['profile_photo']->store('profile_photos', 'public');
        }

        $user = User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
            'bio' => $input['bio'] ?? null,
            'birthday' => $input['birthday'] ?? null,
            'profile_photo_path' => $photoPath,
        ]);

        ActivityLogger::log('created', $user, 'Criou uma nova conta', [
            'data' => [
                'name' => $user->name,
                'email' => $user->email,
                'bio' => $user->bio,
                'birthday' => $user->birthday,
                'profile_photo_path' => $user->profile_photo_path,
            ]
        ]);

        return $user;
    }
}
