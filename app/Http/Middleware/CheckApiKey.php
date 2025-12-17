<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckApiKey
{
    public function handle(Request $request, Closure $next): Response
    {
        $validKey = env('PYTHON_API_KEY', 'default-secret-key');
        
        // Ambil key dari Header Request
        $requestKey = $request->header('X-API-KEY');

        if ($requestKey !== $validKey) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized: Invalid API Key'
            ], 401);
        }

        return $next($request);
    }
}