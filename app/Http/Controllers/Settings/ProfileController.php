<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\ProfileUpdateRequest;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use App\Models\User;
use Illuminate\Support\Facades\Storage; 

class ProfileController extends Controller
{
    /**
     * Show the user's profile settings page.
     */
    public function edit(Request $request): Response
    {
        return Inertia::render('settings/Profile', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => $request->session()->get('status'),
            'auth' => [
                'user' => [
                    'name' => $request->user()->name,
                    'email' => $request->user()->email,
                    'bio' => $request->user()->bio,
                    'birthday' => $request->user()->birthday ? $request->user()->birthday->format('Y-m-d') : null,
                    'profile_photo_path' => $request->user()->profile_photo_path,
                    'email_verified_at' => $request->user()->email_verified_at,
                ],
            ],
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        Log::info('Dados recebidos', $request->all());


        $user = $request->user();

        $data = $request->validated();

        if ($request->boolean('remove_photo') && $user->profile_photo_path) {
            Storage::disk('public')->delete($user->profile_photo_path);
            $user->profile_photo_path = null;
        }


        if ($request->hasFile('profile_photo')) {
            $path = $request->file('profile_photo')->store('profile_photos', 'public');
            $user->profile_photo_path = $path;
        }

        $user->fill($data);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return redirect()->route('profile.edit')->with([
            'user' => [
                'name' => $user->name,
                'email' => $user->email,
                'bio' => $user->bio,
                'birthday' => $user->birthday ? $user->birthday->format('Y-m-d') : null,
                'profile_photo_path' => $user->profile_photo_path,
                'email_verified_at' => $user->email_verified_at,
            ],
            'status' => 'profile-updated',
        ]);
    }



    /**
     * Delete the user's profile.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}