<?php

namespace Eva\Controller;

use Eva\Library\Breadcrumbs;
use Symfony\Component\HttpFoundation\Session\Session;

class BaseController extends \Buki\Router\Http\Controller
{
    protected \Twig\Environment $view;
    protected Breadcrumbs $breadcrumbs;

    public function __construct()
    {
        $loader = new \Twig\Loader\FilesystemLoader(APP_PATH .'/views/');
        $this->view = new \Twig\Environment($loader, [
            'cache' => APP_PATH . '/storage/cache/views',
            'auto_reload' => true,
            'debug' => true
        ]);
        $this->view->addFunction(new \Twig\TwigFunction('formatMoney', 'format_money'));

        $this->breadcrumbs = new Breadcrumbs();
    }

    public function view($path, $args=[])
    {
        $args['user'] = session('user');
        $args['flash'] = (new Session())->getFlashBag()->all();
        $args['siteName'] = config('SITE_NAME');
        $args['breadcrumbs'] = $this->breadcrumbs;

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