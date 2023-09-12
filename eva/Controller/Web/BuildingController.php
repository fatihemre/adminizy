<?php

namespace Eva\Controller\Web;

use Eva\Controller\BaseController;
use Eva\Model\Building;
use Eva\Model\Flat;
use Money\Currencies\ISOCurrencies;
use Money\Currency;
use Money\Formatter\IntlMoneyFormatter;
use Money\Money;
use Symfony\Component\HttpFoundation\Request;

class BuildingController extends BaseController
{

    public function index()
    {
        $buildings = (new Building())->fetchAll();

        return $this->view('index', ['buildings' => $buildings]);
    }

    public  function show($id)
    {

        $building = (new Building())->fetch($id);

        $flats = (new Flat())->fetchAll($building->id);

        $residents = 0;
        foreach($flats as $flat){
            $residents += $flat->count;
        }

        return $this->view('show', ['building'=>$building, 'flats'=>$flats, 'residents'=>$residents]);
    }

    public function create()
    {
        return $this->view('create');
    }

    public function store(Request $request)
    {
        $display_name = $request->get('display_name');
        $address = $request->get('address');

        if($display_name === '' || $address === '') {
            flash('danger', 'Lütfen formu tam olarak doldurun.');
            return redirectTo('/admin/buildings/create');
        }

        $insert = (new Building())->insert(['display_name' => $display_name, 'address' =>$address]);

        if($insert) {
            flash('success', 'Apartman Eklendi');
            return redirectTo('/admin/buildings');
        }

        flash('danger', 'Apartman Eklenemedi.');
        return redirectTo('/admin/buildings/create');

    }

    public function edit($id)
    {
        $building = (new Building())->fetch($id);

        return $this->view('edit', ['building'=>$building]);
    }

    public function update(Request $request, $id)
    {
        $display_name = $request->get('display_name');
        $address = $request->get('address');

        $update = (new Building())->update($id, ['display_name' => $display_name, 'address' =>$address]);
        flash($update ? 'success' : 'danger', $update ? 'Apartman Güncellendi' : 'Apartman Güncellenemedi');
        return redirectTo('/admin/buildings');
    }

    public function destroy($id) {
        $remove = (new Building())->remove($id);
        flash($remove ? 'success' : 'danger', $remove ? 'Apartman Silindi' : 'Apartman Silinemedi');
        return redirectTo('/admin/buildings');
    }

}