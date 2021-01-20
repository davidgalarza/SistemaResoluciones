<?php

namespace App\Http\Middleware;

use Closure;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $role)
    {

        $roles = explode("|", $role);
        $puedeAcceder = false;

        
        foreach ($roles as $role) {
            if(!$puedeAcceder){
                $puedeAcceder = $request->user()->tieneRol($role);
            }
        }

        if (!$puedeAcceder) {
            return abort(403, 'No tienes autorización para ingresar.');
        }
        return $next($request);
    }
}
