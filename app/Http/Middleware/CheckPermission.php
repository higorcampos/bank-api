<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $permissions): Response
    {
        $user = Auth::guard('api')->user();

        $permissionsArray = explode('|', $permissions);

        if (!in_array($user->role, $permissionsArray)) {
            return response()->json(['message' => __('actions.unauthorized')], JsonResponse::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}