<?php

namespace App\auth\Controllers;

use App\Http\Controllers\Controller;
use App\Helpers\Flash;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;
use DB;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth::login');
    }


    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $email = strtolower($request->email);

        // 1. créer user si n'existe pas (inscription automatique)
        $user = User::firstOrCreate(
            ['email' => $email]
        );

        // 2. générer OTP
        $code = random_int(100000, 999999);

        // 3. stocker OTP (remplace ancien)
        DB::table('otps')->updateOrInsert(
            ['email' => $email],
            [
                'code' => $code,
                'expires_at' => now()->addMinutes(5),
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );

        // 4. envoyer mail
        Mail::raw(
            "Votre code de connexion est : $code",
            function ($message) use ($email) {
                $message->to($email)
                    ->subject('Code de connexion');
            }
        );
        Flash::success('Code OTP envoyé ! Vérifiez votre email.');
        // 5. garder email en session pour afficher champ OTP
        return back()->with([
            'email' => $email,
            'otp_sent' => true
        ]);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'code' => 'required'
        ]);

        $email = strtolower($request->email);

        $otp = DB::table('otps')
            ->where('email', $email)
            ->where('code', $request->code)
            ->first();

        if (!$otp) {
            return back()->withErrors([
                'code' => 'Code invalide'
            ]);
        }

        if (now()->gt($otp->expires_at)) {
            return back()->withErrors([
                'code' => 'Code expiré'
            ]);
        }

        $user = User::where('email', $email)->first();

        if (!$user) {
            return back()->withErrors([
                'email' => 'Utilisateur introuvable'
            ]);
        }

        // login Laravel
        Auth::login($user);

        // cleanup OTP (important 🔥)
        DB::table('otps')->where('email', $email)->delete();

        // regen session security
        $request->session()->regenerate();

        if (!$user->is_profile_completed) {
            Flash::info('Merci de completer votre profil avant de poursuivre.');
            return redirect()->route('mummy.profile');
        }

        return redirect()->route('mummy.dashboard');
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }
}