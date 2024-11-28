<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();

            \Log::info('Google User Retrieved:', [
                'id' => $googleUser->id,
                'name' => $googleUser->name,
                'email' => $googleUser->email,
            ]);

            // Check if the user exists
            $existingUser = User::where('google_id', $googleUser->id)
                ->orWhere('email', $googleUser->email)
                ->first();

            if ($existingUser) {
                // Update Google ID if not set
                if (!$existingUser->google_id) {
                    $existingUser->update(['google_id' => $googleUser->id]);
                }

                Auth::login($existingUser);

                // Check if additional info is needed
                if ($this->needsAdditionalInfo($existingUser)) {
                    \Log::info('Redirecting to Information Page.');
                    return redirect()->route('information');
                }

                \Log::info('Redirecting to Dashboard.');
                return redirect('/dashboard');
            } else {
                // Create a new user
                $newUser = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                ]);

                Auth::login($newUser);

                \Log::info('New User Created and Redirected to Information Page.');
                return redirect()->route('information');
            }
        } catch (\Exception $e) {
            \Log::error('Google Login Error:', ['message' => $e->getMessage()]);
            return redirect('/')->with('error', 'Failed to authenticate with Google.');
        }
    }

    public function showInformationForm()
    {
        return view('auth.information');
    }

    public function saveInformation(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect('/login')->with('error', 'Session expired. Please log in again.');
        }

        // Handle image uploads
        if ($request->hasFile('profile_image')) {
            $profileImagePath = $request->file('profile_image')->storeAs('Assets', $request->file('profile_image')->getClientOriginalName());
            $user->profile_image = $profileImagePath;
        }

        if ($request->hasFile('identity_image')) {
            $identityImagePath = $request->file('identity_image')->storeAs('Assets', $request->file('identity_image')->getClientOriginalName());
            $user->identity_image = $identityImagePath;
        }

        // Update user information
        $user->phone_number = $request->phone_number;
        $user->address = $request->address;
        $user->role = $request->role;

        $user->save();

        return redirect('/dashboard');
    }


    private function needsAdditionalInfo(User $user)
    {
        return is_null($user->role);
    }
}
