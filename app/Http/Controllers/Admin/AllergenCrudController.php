<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\AllergenRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class AllergenCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class AllergenCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation {
        show as protected parentShow;
    }
    
    public function setup()
    {
        CRUD::setModel(\App\Models\Allergen::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/allergen');
        CRUD::setEntityNameStrings('allergen', 'allergens');
    }

    protected function setupListOperation()
    {
        CRUD::column('name');
    }

    protected function setupCreateOperation()
    {
        CRUD::setValidation(AllergenRequest::class);
        CRUD::field('name');
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
