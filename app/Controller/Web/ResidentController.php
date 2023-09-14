<?php

namespace Apteasy\Controller\Web;

use Apteasy\Controller\BaseController;
use Apteasy\Entity\ResidentEntity;
use Apteasy\Model\Flat;
use Apteasy\Model\Resident;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class ResidentController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->breadcrumbs->add('Apartmanlar', '/admin/buildings');
    }

    public function create(int $flat_id): string
    {
        $flat = (new Flat())->fetch($flat_id);
        return $this->view('create', ['flat' => $flat]);
    }

    public function store(Request $request, int $flat_id): RedirectResponse
    {
        $entity = new ResidentEntity();
        $entity->flat_id = $flat_id;
        $entity->display_name = $request->get('display_name');
        $entity->phone = $request->get('phone');
        $entity->email = $request->get('email');

        if($entity->display_name === '' || $entity->phone === '' || $entity->email === '') {
            flash('danger', 'Lütfen formu tam olarak doldurun.');
            return redirectTo('/admin/flats/show/' . $flat_id);
        }

        if(filter_var($entity->email, FILTER_VALIDATE_EMAIL) === false) {
            flash('danger', 'Lütfen doğru bir eposta adresi girin.');
            return redirectTo('/admin/flats/show/' . $flat_id);
        }

        $insert = (new Resident())->insert($entity);

        if($insert) {
            flash('success', 'Daire Sakini Eklendi.');
            return redirectTo('/admin/flats/show/' . $flat_id);
        }

        flash('danger', 'Daire Sakini Eklenemedi.');
        return redirectTo('/admin/flats/show/' . $flat_id);
    }

    public function edit(int $resident_id): string
    {
        $resident = (new Resident())->fetch($resident_id);

        return $this->view('edit', ['resident' => $resident]);
    }

    public function update(Request $request, $resident_id): RedirectResponse
    {
        $entity = new ResidentEntity();
        $entity->id = $resident_id;
        $entity->display_name = $request->get('display_name');
        $entity->phone = $request->get('phone');
        $entity->email = $request->get('email');

        if($entity->display_name === '' || $entity->phone === '' || $entity->email === '') {
            flash('danger', 'Lütfen formu tam olarak doldurun.');
            return redirectTo('/admin/residents/edit/' . $resident_id);
        }

        if(filter_var($entity->email, FILTER_VALIDATE_EMAIL) === false) {
            flash('danger', 'Lütfen doğru bir eposta adresi girin.');
            return redirectTo('/admin/residents/edit/' . $resident_id);
        }

        $update = (new Resident())->update($entity);

        if($update) {
            flash('success', 'Daire Sakini Güncellendi.');
            return redirectTo('/admin/residents/edit/' . $resident_id);
        }

        flash('danger', 'Daire Sakini Eklenemedi.');
        return redirectTo('/admin/residents/edit/' . $resident_id);
    }

    public function destroy(int $resident_id)
    {
        $resident = new Resident();
        $getResident = $resident->fetch($resident_id);
        $remove = $resident->removePermanently($resident_id);
        flash($remove ? 'success' : 'danger', $remove ? 'Daire sakini silindi' : 'Daire sakini silinemedi');
        return redirectTo('/admin/flats/show/' . $getResident->flat_id);

    }
}