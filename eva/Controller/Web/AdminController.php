<?php

namespace Eva\Controller\Web;

use Eva\Controller\BaseController;

class AdminController extends BaseController
{

    public function index()
    {
        return $this->view('index');
    }

}