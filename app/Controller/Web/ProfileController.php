<?php

namespace Apteasy\Controller\Web;

use Apteasy\Controller\BaseController;
use Apteasy\Entity\UserEntity;
use Apteasy\Model\User;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class ProfileController extends BaseController
{

    public function edit(): string
    {
        return $this->view('edit');
    }

    public function update(Request $request): RedirectResponse
    {
        $entity = new UserEntity();
        $entity->id = session('user')->id;
        $entity->display_name = $request->get('display_name');
        $entity->email = $request->get('email');
        $entity->phone = $request->get('phone');
        $entity->language = $request->get('language');
        $entity->theme = $request->get('theme');
        $entity->password = $request->get('password');

        if($entity->display_name === '' || $entity->email === '' || $entity->phone === '') {
            flash('danger', __('Please fill all inputs'));
            return redirectTo('/admin/profile');
        }

        if(filter_var($entity->email, FILTER_VALIDATE_EMAIL) === false) {
            flash('danger', __('Please enter a valid email address'));
            return redirectTo('/admin/profile');
        }

        $user = new User();

        if($user->updateExists($entity->email, session('user')->id)) {
            flash('danger', __('This email address is already in use'));
            return redirectTo('/admin/profile');
        }

        if(
            ($entity->password !== '' && $request->get('password_confirmation') !== '') &&
            $entity->password !== $request->get('password_confirmation')
        ) {
            flash('danger', __('The passwords you entered do not match each other, please check and re-enter.'));
            return redirectTo('/admin/profile');
        }

        $update = $user->update($entity);

        if($update) {
            $entity->password = '*****';
            session('user', $entity);
            cookie('lang', $entity->language);
            flash('success', __('Your profile has been updated successfully'));
            return redirectTo('/admin/profile');
        }

        flash('danger', __('Your profile could not be updated'));
        return redirectTo('/admin/profile');

    }
}