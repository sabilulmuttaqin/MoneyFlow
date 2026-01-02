<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /**
     * Show profile page
     */
    public function show()
    {
        return view('featureview.profile.index', [
            'user' => Auth::user()
        ]);
    }

    /**
     * Update profile (name and photo only)
     */
    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048'
        ]);

        $user = Auth::user();
        $user->name = $request->name;

        // Handle photo upload
        if ($request->hasFile('profile_photo')) {
            // Delete old photo if exists
            if ($user->profile_photo && file_exists(public_path('uploads/profiles/' . $user->profile_photo))) {
                unlink(public_path('uploads/profiles/' . $user->profile_photo));
            }

            $file = $request->file('profile_photo');
            $filename = time() . '_' . $user->id . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/profiles'), $filename);
            $user->profile_photo = $filename;
        }

        $user->save();

        return redirect()->route('profile.show')->with('success', 'Profile berhasil diperbarui!');
    }

    /**
     * Update password
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => ['required', 'confirmed', Password::min(6)],
        ]);

        $user = Auth::user();

        // Check current password
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini tidak sesuai.']);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('profile.show')->with('success', 'Password berhasil diubah!');
    }
}
