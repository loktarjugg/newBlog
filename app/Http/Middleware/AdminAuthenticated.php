<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class AdminAuthenticated
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
        if (Auth::guest() || ! Auth::user()->is_admin() ) {

            if ($request->expectsJson()){
                return new JsonResponse([
                    'error' => 'You do not have permission to access.'
                ],401);
            }

            return redirect()->guest(route('admin_login'));
        }

        return $next($request);
    }
}
