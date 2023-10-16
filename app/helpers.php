<?php

use Money\Currencies\ISOCurrencies;
use Money\Currency;
use Money\Formatter\IntlMoneyFormatter;
use Money\Money;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactory;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;

$dotenv = new Dotenv();
$dotenv->load(APP_PATH.'/.env');

$session = new Session();
$session->start();

if(!function_exists('config')) {
    function config($variable, $default=null) {
        return $_ENV[$variable] ?? $default;
    }
}

if(!function_exists('__')) {
    function __($variable, ...$values) {
        global $_lang;
        return sprintf(($_lang[$variable] ?? $variable), ...$values);
    }
}

if(!function_exists('dump')) {
    function dump($arg, $exit = true, $preFormatter = true): void
    {

        if($preFormatter)
            echo '<pre>';
        if(is_bool($arg) || is_null($arg)) {
            var_dump($arg);
        } else {
            print_r($arg);
        }
        if($preFormatter)
            echo '</pre>';
        if($exit)
            exit;

    }
}

if(!function_exists('session')) {
    function session($variable, $set = false) {
        $session = new Session();
        if($set === false) {
            return $session->get($variable);
        }
        if($set === -1) {
            return $session->remove($variable);
        }
        $session->set($variable, $set);
    }
}

if(!function_exists('cookie')) {
    function cookie($variable, $set=false, $expire_date=0) {
        if(!$set) {
            return $_COOKIE[$variable]??null;
        }

        $cookie = Cookie::create($variable)
            ->withValue($set)
            ->withExpires($expire_date);
        $response = new Response();
        $response->headers->setCookie($cookie);
        $response->send();
    }
}

if(!function_exists('redirectTo')) {
    function redirectTo($destination) {
        return new RedirectResponse($destination);
    }
}

if(!function_exists('flash')) {
    function flash($type, $message) {
        $session = new Session();
        $session->getFlashBag()->add(
            $type, $message
        );
    }
}

if(!function_exists('password')){
    function password(): PasswordHasherInterface {
        $factory = new PasswordHasherFactory([
            'common' => ['algorithm' => 'bcrypt'],
            'memory-hard' => ['algorithm' => 'sodium']
        ]);
        return $factory->getPasswordHasher('common');
    }
}

if(!function_exists('format_money')){
    function format_money($amount, $currency='TRY') {
        $amount *= 100;
        $money = new Money($amount, new Currency($currency));
        $currencies = new ISOCurrencies();

        $numberFormatter = new \NumberFormatter(config('LOCALE'), \NumberFormatter::CURRENCY);
        return (new IntlMoneyFormatter($numberFormatter, $currencies))->format($money);
    }
}
if(!function_exists('encrypt')){
    function encrypt($plaintext, $cipher = "aes-256-gcm") {
        if (!in_array($cipher, openssl_get_cipher_methods())) {
            return false;
        }
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($cipher));
        $tag = null;
        $ciphertext = openssl_encrypt(
            gzcompress($plaintext),
            $cipher,
            base64_decode(config('APP_SECRET')),
            $options=0,
            $iv,
            $tag,
        );
        return json_encode([
            "ciphertext" => base64_encode($ciphertext),
            "cipher" => $cipher,
            "iv" => base64_encode($iv),
            "tag" => base64_encode($tag),
        ], JSON_THROW_ON_ERROR);
    }
}

if(!function_exists('decrypt')){
    function decrypt($cipherjson) {
        try {
            $json = json_decode($cipherjson, true, 2,  JSON_THROW_ON_ERROR);
        } catch (Exception $e) {
            return false;
        }
        return gzuncompress(
            openssl_decrypt(
                base64_decode($json['ciphertext']),
                $json['cipher'],
                base64_decode(config('APP_SECRET')),
                $options=0,
                base64_decode($json['iv']),
                base64_decode($json['tag'])
            )
        );
    }
}
if(!function_exists('generate_random_string')) {
    function generate_random_string($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}