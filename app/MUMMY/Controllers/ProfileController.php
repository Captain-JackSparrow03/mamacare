<?php

namespace App\MUMMY\Controllers;

use App\Http\Controllers\Controller;
use App\Helpers\Flash;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;
use DB;

class ProfileController extends Controller
{
    public function showCompleteProfile()
    {
        $user = Auth::user();
        return view('MUMMY::profile.profile', compact('user'));
    }

    public function completeProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name'             => 'required|string|max:100',
            'phone'            => 'required|unique:users,phone,' . $user->id,
            'pregnancy_start'  => 'required|date|before_or_equal:today',
        ]);

        
        $user->update([
            'name'                 => $request->name,
            'phone'                => $request->phone,
            'is_profile_completed' => true,
        ]);
            
        // Crée (ou met à jour) la grossesse
        $user->pregnancies()->updateOrCreate(
            ['user_id' => $user->id],
            ['start_date' => $request->pregnancy_start]
        );
            
            // dd("test");
        Flash::success('Profil complété ! Bienvenue sur MamaCare+');
        return redirect()->route('mummy.dashboard');
    }

}