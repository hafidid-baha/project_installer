<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsInstalled
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (file_exists(storage_path('installed'))) {
            // dd("the file is existed");
            return redirect('/');
        }
        // dd("inside the middleware");
        return $next($request);
    }
}
