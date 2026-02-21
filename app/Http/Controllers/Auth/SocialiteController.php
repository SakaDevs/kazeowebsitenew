<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class SocialiteController extends Controller
{
    // Mengarahkan user ke halaman login Google/Facebook/Github
    public function redirect($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    // Menangkap data user yang dikembalikan oleh Google/Facebook/Github
    public function callback($provider)
    {
        try {
            $socialUser = Socialite::driver($provider)->user();
            
            // Cek apakah email user ini sudah pernah terdaftar di Kazeo
            $user = User::where('email', $socialUser->getEmail())->first();

            // Kalau belum pernah daftar, kita buatkan akunnya otomatis
            if (!$user) {
                $user = User::create([
                    'name' => $socialUser->getName() ?? $socialUser->getNickname(),
                    'email' => $socialUser->getEmail(),
                    'password' => bcrypt(Str::random(16)), // Kasih password acak karena dia login pake sosmed
                    'provider' => $provider,
                    'provider_id' => $socialUser->getId(),
                    // Kita bisa sekalian sedot foto profil sosmednya (opsional)
                    // 'avatar' => $socialUser->getAvatar(), 
                ]);
            }

            // Loginkan user tersebut secara paksa
            Auth::login($user, true);

            return redirect()->route('dashboard');

        } catch (\Exception $e) {
            // Kalau batal/error, kembalikan ke halaman login
            return redirect()->route('login')->withErrors(['email' => 'Login menggunakan '.$provider.' gagal atau dibatalkan.']);
        }
    }
}