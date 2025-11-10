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
use App\Helpers\ActivityLogger;

class ProfileController extends Controller
{
    /**
     * Show the user's profile settings page.
     */
    public function edit(Request $request): Response
    {
        $user = auth()->user();
        $userId = $user->id;

        return Inertia::render('settings/Profile', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => $request->session()->get('status'),
            'auth' => [
                'user' => [
                    'id' => $request->user()->id,
                    'is_admin' => $request->user()->is_admin,
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
    public function update(ProfileUpdateRequest $request)
    {
        $user = $request->user();

        $data = $request->validated();

        if ($request->boolean('remove_photo') && $user->profile_photo_path) {
            Storage::disk('public')->delete($user->profile_photo_path);
            $user->profile_photo_path = null;
        }

        if ($request->hasFile('profile_photo') && !$request->boolean('remove_photo')) {
            $path = $request->file('profile_photo')->store('profile_photos', 'public');
            $user->profile_photo_path = $path;
        }

        $user->fill($data);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        ActivityLogger::log('updated', $user, 'Atualizou seu perfil', [
            'changes' => $user->getChanges()
        ]);

        return Inertia::render('settings/Profile', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => 'profile-updated',
            'auth' => [
                'user' => $user->fresh(),
            ],
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

        activity()
            ->performedOn($user)
            ->causedBy($user)
            ->event('deleted')
            ->log('Excluiu seu perfil');


        return redirect()->route('home');
    }
}