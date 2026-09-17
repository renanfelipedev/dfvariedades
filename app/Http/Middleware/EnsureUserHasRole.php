<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     * @param  string  ...$roles
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        if (empty($roles)) {
            if (! $user->canAccessAdmin()) {
                abort(403, 'Acesso não autorizado ao painel administrativo.');
            }

            return $next($request);
        }

        if (! $user->hasRole($roles)) {
            abort(403, 'Você não possui permissão para acessar esta seção.');
        }

        return $next($request);
    }
}
