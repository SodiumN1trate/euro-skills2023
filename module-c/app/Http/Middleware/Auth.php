<?php

namespace App\Http\Middleware;

use App\Models\ApiToken;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Auth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->header('X-API-TOKEN') ?? null;
        $api_token = ApiToken::where('token', $token)->first();
        if($api_token === null) {
            return response()->json([
                'message' => 'Please submit correct access token',
            ], 401);
        }

        return $next($request);
    }
}
