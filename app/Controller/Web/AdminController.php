<?php

namespace Apteasy\Controller\Web;

use Apteasy\Controller\BaseController;

class AdminController extends BaseController
{

    public function index()
    {
        return $this->view('index');
    }

}