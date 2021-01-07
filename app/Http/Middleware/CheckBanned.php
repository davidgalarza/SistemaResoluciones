<?php

namespace App\Http\Middleware;

use Closure;

class CheckBanned
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
        if (auth()->user()->baneado) {
            auth()->logout();
            $message = 'Cuenta suspendida.';

            return redirect()->route('login')->with('error',  $message);
        }

        return $next($request);
    }
}
