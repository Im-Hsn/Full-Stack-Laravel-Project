<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

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

        try {
            // Handle profile image upload
            if ($request->hasFile('profile_image')) {
                $profileImagePath = $request->file('profile_image')->storeAs(
                    'Assets',
                    $request->file('profile_image')->getClientOriginalName()
                );
                $user->profile_image = $profileImagePath;
            }

            // Initialize verification status
            $verificationMessage = null;

            // Handle identity image upload
            if ($request->hasFile('identity_image')) {
                $identityImage = $request->file('identity_image');
                $tempPath = $identityImage->store('temp');

                if (!Storage::exists($tempPath)) {
                    return redirect()->back()->with('error', 'Failed to upload identity image.');
                }

                // Use OCR API to extract text from the image
                $response = Http::attach(
                    'file',
                    Storage::get($tempPath),
                    $identityImage->getClientOriginalName()
                )->post('https://api.ocr.space/parse/image', [
                    'apikey' => env('OCR_API_KEY'), // Ensure your API key is set in the `.env` file
                    'language' => 'eng',
                ]);

                // Parse OCR response
                $ocrData = $response->json();
                $extractedName = $ocrData['ParsedResults'][0]['ParsedText'] ?? '';

                // Verify extracted name with the user's name
                if (str_contains(strtolower($extractedName), strtolower($user->name))) {
                    $user->is_verified = true;
                    Log::info('User verified successfully.');
                } else {
                    $user->is_verified = false;
                    $verificationMessage = 'Your ID verification failed. Please try again or contact support.';
                    Log::warning('User verification failed.');
                }

                // Delete the temporary file
                Storage::delete($tempPath);
            }

            // Update user information
            $user->phone_number = $request->phone_number;
            $user->address = $request->address;
            $user->role = $request->role;

            $user->save();

            // Flash messages
            $messages = [
                'success' => 'Information updated successfully.',
            ];

            if ($verificationMessage) {
                $messages['warning'] = $verificationMessage;
            } elseif (!$user->is_verified) {
                $messages['warning'] = 'Your ID verification is pending.';
            }

            return redirect('/dashboard')->with($messages);
        } catch (\Exception $e) {
            Log::error('Error saving user information:', ['message' => $e->getMessage()]);
            return redirect()->back()->with('error', 'An error occurred while saving information.');
        }
    }

    private function needsAdditionalInfo(User $user)
    {
        return is_null($user->role);
    }
}
