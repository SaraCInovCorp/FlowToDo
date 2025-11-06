<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;

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

        activity()
            ->performedOn($user)
            ->causedBy($user)
            ->event('created')
            ->log('Criou uma nova conta');

        return User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => $input['password'],
            'bio' => $input['bio'] ?? null,
            'birthday' => $input['birthday'] ?? null,
            'profile_photo_path' => $photoPath,
            ]);
    }
}
