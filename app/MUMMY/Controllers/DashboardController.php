<?php

namespace App\MUMMY\Controllers;

use App\Http\Controllers\Controller;
use App\MUMMY\Data\BabyWeekData;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;
use DB;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $user      = Auth::user();
        $pregnancy = $user->currentPregnancy;
        $week      = $pregnancy?->current_week ?? 1;

        $reminders = $user->reminders()
            ->where('is_done', false)
            ->where('date', '>=', now())
            ->orderBy('date')
            ->take(5)
            ->get();

        $notesCount = $user->notes()->count();
        $babyData   = BabyWeekData::get($week);

        return view('MUMMY::dashboard.dashboard', compact(
            'user', 'pregnancy', 'reminders', 'notesCount', 'babyData', 'week'
        ));
    }
}