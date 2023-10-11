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
        $entity->password = $request->get('password');

        if($entity->display_name === '' || $entity->email === '' || $entity->phone === '') {
            flash('danger', 'Lütfen formu tam olarak doldurun.');
            return redirectTo('/admin/profile');
        }

        if(filter_var($entity->email, FILTER_VALIDATE_EMAIL) === false) {
            flash('danger', 'Lütfen doğru bir eposta adresi girin');
            return redirectTo('/admin/profile');
        }

        $user = new User();

        if($user->updateExists($entity->email, session('user')->id)) {
            flash('danger', 'Bu eposta adresi zataen kullanımda.');
            return redirectTo('/admin/profile');
        }

        if(
            ($entity->password !== '' && $request->get('password_confirmation') !== '') &&
            $entity->password !== $request->get('password_confirmation')
        ) {
            flash('danger', 'Parolanızı güncellemek istediniz, ancak girdiğiniz parolalar birbiriyle uyuşmuyor. Lütfen kontrol edin.');
            return redirectTo('/admin/profile');
        }

        $update = $user->update($entity);

        if($update) {
            $entity->password = '*****';
            session('user', $entity);
            cookie('lang', $entity->language);
            flash('success', 'Bilgileriniz başarıyla güncellendi.');
            return redirectTo('/admin/profile');
        }

        flash('danger', 'Bilgileriniz güncellenemedi.');
        return redirectTo('/admin/profile');

    }
}