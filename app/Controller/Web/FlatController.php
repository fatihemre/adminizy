<?php

namespace Apteasy\Controller\Web;

use Apteasy\Controller\BaseController;
use Apteasy\Model\Building;
use Apteasy\Model\Flat;
use Symfony\Component\HttpFoundation\Request;

class FlatController extends BaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->breadcrumbs->add('Apartmanlar', '/admin/buildings');
    }

    public function show($id)
    {
    }

    public function create($building_id)
    {
        $building = (new Building())->fetch($building_id);

        $this->breadcrumbs->add($building->display_name, '/admin/buildings/show/' . $building_id);

        return $this->view('create', ['building' => $building]);
    }

    public function store(Request $request, $building_id)
    {
        $display_name = $request->get('display_name');
        $amount = $request->get('amount', 0);
        $amount = $amount !== '' ? $amount : 0;

        if($display_name === '') {
            flash('danger', 'Lütfen formu tam olarak doldurun.');
            return redirectTo('/admin/buildings/create');
        }

        $insert = (new Flat())->insert($building_id, ['display_name' => $display_name, 'amount' => $amount]);

        if($insert) {
            flash('success', 'Daire Eklendi');
            return redirectTo('/admin/buildings/show/' . $building_id);
        }

        flash('danger', 'Daire Eklenemedi.');
        return redirectTo('/admin/flats/create/' . $building_id);

    }

    public function edit($flat_id)
    {
        $flat = (new Flat())->fetch($flat_id);
        $building = (new Building())->fetch($flat->building_id);
        $this->breadcrumbs->add($building->display_name, '/admin/buildings/show/' . $flat->building_id);
        
        return $this->view('edit', ['flat' => $flat]);
    }

    public function update(Request $request, $flat_id)
    {
        $display_name = $request->get('display_name');
        $amount = $request->get('amount', 0);
        $amount = $amount !== '' ? $amount : 0;

        if($display_name === '') {
            flash('danger', 'Lütfen formu tam olarak doldurun.');
            return redirectTo('/admin/flats/edit/' . $flat_id);
        }

        $update = (new Flat())->update($flat_id, ['display_name' => $display_name, 'amount'=>$amount]);

        flash($update ? 'success' : 'danger', $update ? 'Daire Güncellendi' : 'Daire Güncellenemedi');
        return redirectTo('/admin/flats/edit/' . $flat_id );

    }

    public function destroy($flat_id) {
        $flat = new Flat();
        $getFlat = $flat->fetch($flat_id);
        $remove = $flat->remove($flat_id);
        flash($remove ? 'success' : 'danger', $remove ? 'Daire Silindi' : 'Daire Silinemedi');
        return redirectTo('/admin/buildings/show/' . $getFlat->building_id);
    }

}