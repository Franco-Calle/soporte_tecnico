<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Enums\Rol;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class EnsureUserHasRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if ($user === null) {
            return redirect()->route('login');
        }

        $rolesPermitidos = array_map(
            static fn (string $r): Rol => Rol::from($r),
            $roles,
        );

        if (! in_array($user->rol, $rolesPermitidos, true)) {
            abort(403, 'Su rol no tiene permiso para acceder a esta seccion.');
        }

        return $next($request);
    }
}
