<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::user(); // Get logged-in user

                // Redirect based on user role
                switch ($user->role) {
                    case 'Admin':
                        return redirect('/admin/dashboard');
                    case 'Marshall':

                        if ($request->is('marshall/*')) {
                            return $next($request);
                        }

                        return redirect('/marshall/dashboard');
                    case 'Inspector':
                        return redirect('/inspector/dashboard');
                    case 'Client':

                        if ($request->is('client/*')) {
                            return $next($request);
                        }

                        return redirect('/client/dashboard');
                    case 'Chief':
                        return redirect('/chief/dashboard');
                    default:
                        return redirect(RouteServiceProvider::HOME); // Default home route
                }
            }
        }

        return $next($request);
    }
}
