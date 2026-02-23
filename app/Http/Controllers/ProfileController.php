<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();
        
        if (!$user) {
            return redirect()->route('login');
        }

        // Get user roles
        $roles = $user->roles->pluck('name')->toArray();
        $primaryRole = $user->roles->first() ? $user->roles->first()->name : 'No Role';
        
        // Get settings for language
        $settings = \App\Models\CompanySetting::current();
        
        return view('profile', [
            'user' => $user,
            'roles' => $roles,
            'primaryRole' => $primaryRole,
            'settings' => $settings,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        
        if (!$user) {
            return Redirect::route('profile.edit')->with('error', 'User not authenticated.');
        }

        $validated = $request->validated();

        // Handle profile image removal
        if ($request->boolean('remove_profile_image')) {
            // Delete existing profile image if exists
            if ($user->profile_image && Storage::disk('public')->exists($user->profile_image)) {
                Storage::disk('public')->delete($user->profile_image);
            }
            $validated['profile_image'] = null;
            
            $user->fill($validated);
            $user->save();
            
            return Redirect::route('profile.edit')->with('status', 'image-deleted');
        }
        // Handle profile image upload
        elseif ($request->hasFile('profile_image')) {
            // Delete old profile image if exists
            if ($user->profile_image && Storage::disk('public')->exists($user->profile_image)) {
                Storage::disk('public')->delete($user->profile_image);
            }

            // Store new image with secure random filename
            $file = $request->file('profile_image');
            $extension = $file->getClientOriginalExtension();
            $filename = uniqid('img_', true) . '.' . $extension;
            $imagePath = $file->storeAs('profile-images', $filename, 'public');
            $validated['profile_image'] = $imagePath;
        }
        // If no image change, preserve existing profile_image
        else {
            // Keep existing profile_image value if not being changed
            $validated['profile_image'] = $user->profile_image;
        }

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $user = $request->user();
        
        if (!$user) {
            return Redirect::route('profile.edit')->with('error', 'User not authenticated.');
        }

        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
