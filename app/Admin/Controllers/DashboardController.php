<?php
namespace App\Admin\Controllers;

use Illuminate\Routing\Controller;
use App\Models\User;
use App\Models\Content;
use App\Models\Reminder;
use App\Models\Note;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'users'     => User::where('is_admin', false)->count(),
            'contents'  => Content::count(),
            'reminders' => Reminder::count(),
            'notes'     => Note::count(),
        ];

        $latestUsers = User::where('is_admin', false)
            ->latest()
            ->take(5)
            ->get();

        return view('Admin::dashboard', compact('stats', 'latestUsers'));
    }
}