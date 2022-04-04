<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\OrderRowRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

class OrderRowCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    
    public function setup()
    {
        CRUD::setModel(\App\Models\OrderRow::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/order-row');
        CRUD::setEntityNameStrings('order row', 'order rows');
        CRUD::denyAccess('create');
        CRUD::denyAccess('update');
        CRUD::denyAccess('delete');
    }

    protected function setupShowOperation()
    {
        CRUD::column('price')->type('number')->thousands_sep(',')->prefix('₱');
    }

    protected function setupListOperation()
    {
        CRUD::column('product_id');
        CRUD::column('order_id');
        CRUD::column('name');
        CRUD::column('price')->type('number')->thousands_sep(',')->prefix('₱');


    }

    protected function setupCreateOperation()
    {
        CRUD::setValidation(OrderRowRequest::class);

        CRUD::field('product_id');
        CRUD::field('order_id');
        CRUD::field('name');
        CRUD::field('price');

    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
