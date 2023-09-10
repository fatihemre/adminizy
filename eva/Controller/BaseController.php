<?php

namespace Eva\Controller;

use Symfony\Component\HttpFoundation\Session\Session;

class BaseController extends \Buki\Router\Http\Controller
{
    protected $view;

    public function __construct()
    {
        $loader = new \Twig\Loader\FilesystemLoader(APP_PATH .'/views/');
        $this->view = new \Twig\Environment($loader, [
            'cache' => APP_PATH . '/storage/cache/views',
            'auto_reload' => true,
            'debug' => true
        ]);
    }

    public function view($path, $args=[])
    {
        $args['user'] = session('user');
        $args['flash'] = (new Session())->getFlashBag()->all();
        $args['siteName'] = config('SITE_NAME');

        $fullPath = $this->className() . DIRECTORY_SEPARATOR . $path . '.twig';
        return $this->view->render($fullPath, $args);
    }

    public function className(): string
    {
        $className = (new \ReflectionClass(get_called_class()))->getName();
        $className = str_replace(['Eva\\Controller\\', 'Controller'], '', $className);

        return strtolower(str_replace('\\', DIRECTORY_SEPARATOR, $className));
    }
}