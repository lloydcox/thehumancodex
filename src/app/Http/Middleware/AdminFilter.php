<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AdminFilter
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $loggedInUser = Auth::user();
        if($loggedInUser->type !== config('constance.user_types')['ADMIN']){
            return redirect('admin/login');
        }
        return $next($request);
    }
}
