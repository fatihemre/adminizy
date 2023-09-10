<?php

namespace Eva\Middleware;

use Buki\Router\Http\Middleware;
use Symfony\Component\HttpFoundation\Request;

class AuthMiddleware extends Middleware {

    public function handle(Request $request) {
        if(!session('user') || session('user') === '') {
            return redirectTo('/auth/login');
        }
        return true;
    }
}