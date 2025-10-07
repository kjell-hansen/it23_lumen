<?php

namespace App\Http\Middleware;

class LowerCaseUrls {
    public function handle($request, \Closure $next) {
        $path=$request->path() ;

        if($path!==strtolower($path)) {
            // Gör en redirect till lowercase-versionen av path
            return redirect(strtolower($request->getRequestUri()));
        }

        return $next($request);
    }
}
