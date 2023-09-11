<?php

namespace Eva\Controller\Web;

use Eva\Controller\BaseController;
use Eva\Model\Apartment;
use Eva\Model\Flat;
use Money\Currencies\ISOCurrencies;
use Money\Currency;
use Money\Formatter\IntlMoneyFormatter;
use Money\Money;
use Symfony\Component\HttpFoundation\Request;

class ApartmentController extends BaseController
{

    public function index()
    {
        $apartments = (new Apartment())->fetchAll();

        return $this->view('index', ['apartments' => $apartments]);
    }

    public  function show($id)
    {

        $apartment = (new Apartment())->fetch($id);

        $flats = (new Flat())->fetchAll($apartment->id);

        $residents = 0;
        foreach($flats as $flat){
            $residents += $flat->count;
        }

        return $this->view('show', ['apartment'=>$apartment, 'flats'=>$flats, 'residents'=>$residents]);
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
            return redirectTo('/admin/apartments/create');
        }

        $insert = (new Apartment())->insert(['display_name' => $display_name, 'address' =>$address]);

        if($insert) {
            flash('success', 'Apartman Eklendi');
            return redirectTo('/admin/apartments');
        }

        flash('danger', 'Apartman Eklenemedi.');
        return redirectTo('/admin/apartments/create');

    }

    public function edit($id)
    {
        $aparment = (new Apartment())->fetch($id);

        return $this->view('edit', ['apartment'=>$aparment]);
    }

    public function update(Request $request, $id)
    {
        $display_name = $request->get('display_name');
        $address = $request->get('address');

        $update = (new Apartment())->update($id, ['display_name' => $display_name, 'address' =>$address]);
        flash($update ? 'success' : 'danger', $update ? 'Apartman Güncellendi' : 'Apartman Güncellenemedi');
        return redirectTo('/admin/apartments');
    }

    public function destroy($id) {
        $remove = (new Apartment())->removePermanently($id);
        flash($remove ? 'success' : 'danger', $remove ? 'Apartman Silindi' : 'Apartman Silinemedi');
        return redirectTo('/admin/apartments');
    }

}