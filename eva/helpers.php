<?php

use Money\Currencies\ISOCurrencies;
use Money\Currency;
use Money\Formatter\IntlMoneyFormatter;
use Money\Money;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactory;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;

$dotenv = new Dotenv();
$dotenv->load(APP_PATH.'/.env');

$session = new Session();
$session->start();

if(!function_exists('config')) {
    function config($variable) {
        return $_ENV[$variable] ?? null;
    }
}

if(!function_exists('debug')) {
    function debug($arg, $exit = true, $preFormatter = true) {

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