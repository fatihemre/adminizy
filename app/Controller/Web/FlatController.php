<?php

namespace Apteasy\Controller\Web;

use Apteasy\Controller\BaseController;
use Apteasy\Entity\FlatEntity;
use Apteasy\Model\Building;
use Apteasy\Model\Flat;
use Apteasy\Model\Resident;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class FlatController extends BaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->breadcrumbs->add(__('Buildings'), '/admin/buildings');
    }

    public function show($id): string
    {
        $flat = (new Flat)->fetch($id);
        $building = (new Building())->fetch($flat->building_id);
        $residents = (new Resident())->fetchAll($flat->id);

        $this->breadcrumbs->add($building->display_name, '/admin/buildings/show/' . $building->id);

        return $this->view('show', ['flat' => $flat, 'building' => $building, 'residents' => $residents]);
    }

    public function create($building_id): string
    {
        $building = (new Building())->fetch($building_id);

        $this->breadcrumbs->add($building->display_name, '/admin/buildings/show/' . $building_id);

        return $this->view('create', ['building' => $building]);
    }

    public function store(Request $request, $building_id): RedirectResponse
    {
        $entity = new FlatEntity();
        $entity->building_id = $building_id;
        $entity->display_name = $request->get('display_name');
        $entity->amount = $request->get('amount', 0) !== '' ? $request->get('amount', 0) : 0;

        if($entity->display_name === '') {
            flash('danger', __('Please fill all inputs'));
            return redirectTo('/admin/buildings/create');
        }

        $insert = (new Flat())->insert($entity);

        if($insert) {
            flash('success', __('The flat has been created'));
            return redirectTo('/admin/buildings/show/' . $building_id);
        }

        flash('danger', 'The flat could not be created');
        return redirectTo('/admin/flats/create/' . $building_id);
    }

    public function edit($flat_id): string
    {
        $flat = (new Flat())->fetch($flat_id);
        $building = (new Building())->fetch($flat->building_id);
        $this->breadcrumbs->add($building->display_name, '/admin/buildings/show/' . $flat->building_id);
        
        return $this->view('edit', ['flat' => $flat]);
    }

    public function update(Request $request, $flat_id): RedirectResponse
    {
        $entity = new FlatEntity();
        $entity->id = $flat_id;
        $entity->display_name = $request->get('display_name');
        $entity->amount = $request->get('amount', 0) !== '' ? $request->get('amount', 0) : 0;

        if($entity->display_name === '') {
            flash('danger', __('Please fill all inputs'));
            return redirectTo('/admin/flats/edit/' . $flat_id);
        }

        $update = (new Flat())->update($entity);

        flash($update ? 'success' : 'danger', $update ? __('The flat has been updated') : __('The flat could not be updated'));
        return redirectTo('/admin/flats/edit/' . $flat_id );

    }

    public function destroy($flat_id): RedirectResponse
    {
        $flat = new Flat();
        $getFlat = $flat->fetch($flat_id);
        $remove = $flat->remove($flat_id);
        flash($remove ? 'success' : 'danger', $remove ? __('The flat has been removed') : __('The flat could not be removed'));
        return redirectTo('/admin/buildings/show/' . $getFlat->building_id);
    }

}