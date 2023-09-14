<?php

namespace Apteasy\Controller\Web;

use Apteasy\Controller\BaseController;

class AdminController extends BaseController
{

    public function index(): string
    {
        return $this->view('index');
    }

}