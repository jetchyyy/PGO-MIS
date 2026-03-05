<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        abort_unless($user, 401);

        if (!empty($roles) && !in_array($user->role, $roles, true)) {
            abort(403, 'Insufficient role privileges.');
        }

        return $next($request);
    }
}
