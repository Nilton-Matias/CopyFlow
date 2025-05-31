<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        $userSocial = Socialite::driver('google')->user();
        $user = User::firstOrCreate([
            'email' => $userSocial->getEmail(),
        ], [
            'name' => $userSocial->getName(),
            'password' => bcrypt(uniqid()), // Gera uma senha aleatória
        ]);

        Auth::login($user);
        return redirect()->route('welcome');
    }

    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function handleFacebookCallback()
    {
        $userSocial = Socialite::driver('facebook')->user();

        $email = $userSocial->getEmail();
        if (!$email) {
            $email = 'facebook_' . $userSocial->getId() . '@facebook.com'; // Gera um email aleatório se não houver email no perfil do Facebook
        }

        $user = User::firstOrCreate([
            'email' => $email,
        ], [
            'name' => $userSocial->getName(),
            'password' => bcrypt(uniqid()), // Gera uma senha aleatória
        ]);

        Auth::login($user);
        return redirect()->route('welcome');
    }
}
