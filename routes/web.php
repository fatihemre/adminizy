<?php

use Apteasy\Controller\BaseController;
use Apteasy\Controller\Web\AuthController;
use Apteasy\Controller\Web\AdminController;
use Apteasy\Controller\Web\BuildingController;
use Apteasy\Controller\Web\FlatController;
use Apteasy\Controller\Web\ResidentController;
use Apteasy\Controller\Web\ProfileController;
use Apteasy\Middleware\AdminMiddleware;
use Apteasy\Middleware\AuthMiddleware;
use Symfony\Component\HttpFoundation\Request;

$webRouter = &$router;

$webRouter->get('/', function(Request $request) {
    return redirectTo('/auth/login');
}, ['before'=> AdminMiddleware::class]);

$webRouter->get('/set-locale/:string', [BaseController::class, 'setLocale']);


$webRouter->group('/admin', function($webRouter){
    $webRouter->get('', [AdminController::class, 'index']);

    $webRouter->group('/buildings', function($webRouter) {
        $webRouter->get('', [BuildingController::class, 'index']);
        $webRouter->get('/create', [BuildingController::class, 'create']);
        $webRouter->post('/create', [BuildingController::class, 'store']);
        $webRouter->get('/remove/:id', [BuildingController::class, 'destroy']);
        $webRouter->get('/edit/:id', [BuildingController::class, 'edit']);
        $webRouter->post('/edit/:id', [BuildingController::class, 'update']);
        $webRouter->get('/show/:id', [BuildingController::class, 'show']);
    });

    $webRouter->group('/flats', function($webRouter) {
        $webRouter->get('/create/:id', [FlatController::class, 'create']);
        $webRouter->post('/create/:id', [FlatController::class, 'store']);

        $webRouter->get('/show/:id', [FlatController::class, 'show']);

        $webRouter->get('/edit/:id', [FlatController::class, 'edit']);
        $webRouter->post('/edit/:id', [FlatController::class, 'update']);

        $webRouter->get('/remove/:id', [FlatController::class, 'destroy']);
    });

    $webRouter->group('/residents', function($webRouter) {
        $webRouter->get('/create/:id', [ResidentController::class, 'create']);
        $webRouter->post('/create/:id',[ResidentController::class, 'store']);

        $webRouter->get('/edit/:id', [ResidentController::class, 'edit']);
        $webRouter->post('/edit/:id', [ResidentController::class, 'update']);

        $webRouter->get('/remove/:id', [ResidentController::class, 'destroy']);
    });

    $webRouter->group('/profile', function($webRouter) {
        $webRouter->get('', [ProfileController::class, 'edit']);
        $webRouter->post('', [ProfileController::class, 'update']);
        $webRouter->post('/2fa/recovery-codes', [ProfileController::class, 'recoveryCodes']);
        $webRouter->post('/2fa/generate-recovery-codes', [ProfileController::class, 'generateRecoveryCodes']);
    });



}, ['before'=>AuthMiddleware::class]);

$webRouter->get('/auth/login', [AuthController::class, 'login'], ['before'=> AdminMiddleware::class]);
$webRouter->post('/auth/login', [AuthController::class, 'check']);

$webRouter->get('/auth/2fa', [AuthController::class, 'mfa'], ['before'=> AdminMiddleware::class]);
$webRouter->post('/auth/2fa', [AuthController::class, 'mfaCheck']);

$webRouter->get('/auth/logout', [AuthController::class, 'logout']);
