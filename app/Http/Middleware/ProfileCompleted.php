<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Helpers\Flash;

class ProfileCompleted
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if ($user && !$user->is_profile_completed) {
            Flash::info('Merci de compléter votre profil avant de continuer.');
            return redirect()->route('mummy.profile');
        }

        return $next($request);
    }
}