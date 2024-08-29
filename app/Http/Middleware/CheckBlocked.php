<?php

namespace App\Http\Middleware;

use App\Models\UsersMeta;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckBlocked
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        $userMeta = UsersMeta::where('user_id', $user->id)->first();

        if ($userMeta && $userMeta->blocked) {
            return response()->json(['error' => 'Siz bloklangansiz!'], 403);
        }

        return $next($request);
    }
}
