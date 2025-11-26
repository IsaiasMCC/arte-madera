<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\PageVisit;
use Symfony\Component\HttpFoundation\Response;

class CountVisits
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        if (app()->environment('testing')) {
            return $next($request);
        }
        $currentPage = $request->path();

        // Busca o crea un registro para la página actual
        $pageVisit = PageVisit::firstOrCreate(['page' => $currentPage]);
        $pageVisit->increment('visits');
        return $next($request);
    }
}
