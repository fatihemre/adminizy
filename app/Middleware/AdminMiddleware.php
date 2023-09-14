<?php

namespace Apteasy\Middleware;

use Buki\Router\Http\Middleware;
use Apteasy\Entity\UserEntity;
use Symfony\Component\HttpFoundation\Request;

class AdminMiddleware extends Middleware {

    public function handle(Request $request) {
        $user = session('user');
        if($user instanceof UserEntity) {
            return redirectTo('/admin');
        }
        return true;
    }
}