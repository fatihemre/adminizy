<?php

namespace Apteasy\Controller\Web;

use Apteasy\Controller\BaseController;
use Apteasy\Model\Building;
use Apteasy\Model\Flat;
use Apteasy\Model\Resident;

class AdminController extends BaseController
{

    public function index(): string
    {
        $flatStats = (new Flat())->total();

        $buildings = (new Building())->total();
        $flats = $flatStats->totalFlats;
        $residents = (new Resident())->total();
        $amount = $flatStats->totalAmount;

        return $this->view('index', [
            'stats' => [
                'buildings' => $buildings,
                'flats' => $flats,
                'residents' => $residents,
                'amount' => format_money($amount)
            ]
        ]);
    }

}