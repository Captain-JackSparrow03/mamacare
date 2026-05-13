<?php
namespace App\MUMMY\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller;
use App\MUMMY\Data\BabyWeekData;

class BabyController extends Controller
{
    public function index()
    {
        $user      = Auth::user();
        $pregnancy = $user->currentPregnancy;
        $week      = $pregnancy?->current_week ?? 1;
        $babyData  = BabyWeekData::get($week);
        $allWeeks  = BabyWeekData::all();

        return view('MUMMY::baby.index', compact(
            'pregnancy', 'week', 'babyData', 'allWeeks'
        ));
    }

    public function byWeek(int $week)
    {
        $week      = max(1, min(40, $week));
        $user      = Auth::user();
        $pregnancy = $user->currentPregnancy;
        $babyData  = BabyWeekData::get($week);
        $allWeeks  = BabyWeekData::all();

        return view('MUMMY::baby.index', compact(
            'pregnancy', 'week', 'babyData', 'allWeeks'
        ));
    }
}