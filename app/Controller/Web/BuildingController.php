<?php

namespace Apteasy\Controller\Web;

use Apteasy\Controller\BaseController;
use Apteasy\Entity\BuildingEntity;
use Apteasy\Model\Building;
use Apteasy\Model\Flat;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class BuildingController extends BaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->breadcrumbs->add(__('Buildings'), '/admin/buildings');
    }

    public function index(): string
    {
        $buildings = (new Building())->fetchAll();

        return $this->view('index', ['buildings' => $buildings]);
    }

    public  function show($id): string
    {

        $building = (new Building())->fetch($id);

        $flats = (new Flat())->fetchAll($building->id);

        $residents = 0;
        foreach($flats as $flat){
            $residents += $flat->count;
        }

        return $this->view('show', ['building'=>$building, 'flats'=>$flats, 'residents'=>$residents]);
    }

    public function create(): string
    {
        return $this->view('create');
    }

    public function store(Request $request): RedirectResponse
    {
        $entity = new BuildingEntity();
        $entity->display_name = $request->get('display_name');
        $entity->address = $request->get('address');

        if($entity->display_name === '' || $entity->address === '') {
            flash('danger', __('Please fill all inputs'));
            return redirectTo('/admin/buildings/create');
        }

        $insert = (new Building())->insert($entity);

        if($insert) {
            flash('success', __('Building has been created'));
            return redirectTo('/admin/buildings');
        }

        flash('danger', __('Building could not be created'));
        return redirectTo('/admin/buildings/create');
    }

    public function edit($id): string
    {
        $building = (new Building())->fetch($id);

        $this->breadcrumbs->add($building->display_name, '/admin/buildings/show/' . $building->id);

        return $this->view('edit', ['building'=>$building]);
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $entity = new BuildingEntity();
        $entity->id = $id;
        $entity->display_name = $request->get('display_name');
        $entity->address = $request->get('address');

        $update = (new Building())->update($entity);

        flash($update ? 'success' : 'danger', $update ? __('Building has been updated') : __('Building could not be updated'));
        return redirectTo('/admin/buildings');
    }

    public function destroy($id): RedirectResponse
    {
        $remove = (new Building())->remove($id);
        flash($remove ? 'success' : 'danger', $remove ? __('Building has been removed') : __('Building could not be removed'));
        return redirectTo('/admin/buildings');
    }

}