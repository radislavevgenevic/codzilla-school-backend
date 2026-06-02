<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class TeacherMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->user() || $request->user()->role !== 'teacher') {
            return response()->json([
                'success' => false,
                'message' => 'Доступ запрещен. Требуются права преподавателя.'
            ], 403);
        }

        return $next($request);
    }
}
