<?php

namespace App\Http\Middleware;

class AuthenticatedUser {
    public function handle($request, \Closure $next) {
        if (!$request->session()->has('user_id')) {
            // Användaren är inte inloggad - redirecta till login-sidan
            return redirect('/login');//->with('errors', ['Du måste logga in först']);
        }
        return $next($request);
    }
}
