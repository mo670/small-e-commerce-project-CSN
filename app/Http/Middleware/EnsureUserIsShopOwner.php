<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsShopOwner
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || ($user->role !== User::ROLE_SHOP_OWNER && $user->role !== User::ROLE_ADMIN)) {
            abort(403, 'Unauthorized access. Shop Owner privileges required.');
        }

        // Check if shop owner has an assigned shop
        if ($user->role === User::ROLE_SHOP_OWNER && ! $user->shop) {
            abort(403, 'You do not have any active shop assigned to your account. Please contact an administrator.');
        }

        return $next($request);
    }
}

