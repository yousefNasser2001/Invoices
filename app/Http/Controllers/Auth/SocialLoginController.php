<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialLoginController extends Controller
{
    public function redirect($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function callback($provider)
    {
        try {
            $providerUser = Socialite::driver($provider)->stateless()->user();
            $existingUser = User::where('email', $providerUser->email)->first();

            if ($existingUser) {
                // اذا كان هذا المستخدم مسجل بنفس الايميل من قبل ادمج معلوماته و سجل دخول تلقائي
                $existingUser->update([
                    'provider' => $provider,
                    'provider_id' => $providerUser->id,
                    'provider_token' => $providerUser->token,
                ]);

                Auth::login($existingUser);
            } else {
                // اذا مكانش الايميل موجود من قبل انشأ مستخدم جديد 
                $user = User::create([
                    'name' => $providerUser->name,
                    'email' => $providerUser->email,
                    'provider' => $provider,
                    'provider_id' => $providerUser->id,
                    'password' => Hash::make(Str::random(8)),
                    'provider_token' => $providerUser->token,
                ]);

                Auth::login($user);
            }

            return redirect()->route('dashboard');
        } catch (Exception $e) {
            return redirect()->route('login')->withErrors([
                'email' => $e->getMessage(),
            ]);
        }
    }

}
