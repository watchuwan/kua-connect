<?php

namespace App\Http\Middleware;

use App\Models\Visitor;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VisitorTracker
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->path() === '/') {
            try {
                Visitor::record($request->path());
            } catch (\Throwable $e) {
                // table might not exist yet
            }
        }

        return $next($request);
    }
}
