<?php

namespace Apteasy\Controller\Web;

use Apteasy\Controller\BaseController;
use Apteasy\Model\User;
use Symfony\Component\HttpFoundation\Request;

class AuthController extends BaseController
{

    public function login()
    {
        return $this->view('login');
    }

    public function logout()
    {
        session('user', -1);
        return redirectTo('/');
    }

    public function check(Request $request)
    {
        $email = $request->get('email');
        $password = $request->get('password');

        $user = (new User())->fetch($email);

        if(!$user) {
            flash('warning', 'User not found');
            return redirectTo('/auth/login');
        }

        if(!password()->verify($user->password, $password)) {
            flash('warning', 'Username/Password incorrect.');
            return redirectTo('/auth/login');
        }

        session('user', $user);

        return redirectTo('/admin');

    }

}