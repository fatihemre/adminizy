<?php

namespace Apteasy\Controller\Web;

use Apteasy\Controller\BaseController;
use Apteasy\Entity\UserEntity;
use Apteasy\Model\User;
use PragmaRX\Google2FA\Google2FA;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
class ProfileController extends BaseController
{
    public function twofactor() {

        $encrypted = encrypt('MYTEXTisVeryGood');
        $decrypted = decrypt($encrypted);

        echo $encrypted;
        echo '<hr>';
        echo $decrypted;

    }
    public function edit(): string
    {
        $authorized_user = session('user');
        $user = (new User())->fetch($authorized_user->email);
        $qrcode_svg = null;

        if($user->is_mfa_enabled && is_null($user->mfa_recovery_codes)) {
            $google2fa = (new Google2FA());
            $secret_key = $google2fa->generateSecretKey();

            $save_secret_key = (new User())->saveSecretKey($user->id, encrypt($secret_key));

            if($save_secret_key) {
                $inlineUrl = $google2fa->getQRCodeUrl(
                    config('SITE_NAME'),
                    $user->email,
                    $secret_key
                );

                $writer = new Writer(
                    new ImageRenderer(
                        new RendererStyle(200),
                        new SvgImageBackEnd()
                    )
                );

                $qrcode_svg = $writer->writeString($inlineUrl);
            }
        }

        return $this->view('edit', ['qrcode_svg' => $qrcode_svg, 'user' => $user]);
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
        $entity->is_mfa_enabled = $request->get('is_mfa_enabled');

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

        $secret = $request->get('mfa_validation');
        if(!is_null($secret)) {
            $fetchUser = $user->fetch(session('user')->email);
            $google2fa = (new Google2FA());
            $valid = $google2fa->verifyKey(decrypt($fetchUser->mfa_secret_key), $secret);
            if($valid) {
                $strs = [];
                for($i=0;$i<10;$i++) {
                    $strs[$i] = generate_random_string(8) . '-' . generate_random_string(8);
                }

                $user->saveRecoveryCodes($entity->id,  encrypt(json_encode($strs, JSON_THROW_ON_ERROR)));
            }
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