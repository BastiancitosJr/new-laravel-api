<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Facades\JWTAuth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  mixed  ...$roles
     * @return mixed
     */
    public function handle($request, Closure $next, ...$roles)
    {
        // Autenticar el usuario desde el token
        $user = JWTAuth::parseToken()->authenticate();

        // Verificar si el rol del usuario está en la lista de roles permitidos
        if (!in_array($user->role_id, $roles)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return $next($request);
    }
}
