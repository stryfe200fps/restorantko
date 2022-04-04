<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ProductImageRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class ProductImageCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ProductImageCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        CRUD::setModel(\App\Models\ProductImage::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/product-image');
        CRUD::setEntityNameStrings('product image', 'product images');
        CRUD::denyAccess('create');
    }

    protected function setupShowOperation()
    {
        $this->crud->denyAccess('delete');
        $this->crud->denyAccess('update');
        CRUD::column('file_path')->type('image')->disk('local')->height('1000px')->width('800px');
    }

    protected function setupListOperation()
    {
        CRUD::column('file_path')->type('image')->disk('local')->height('200px')->width('200px');
        CRUD::column('sort_order');
    }
    
    protected function setupCreateOperation()
    {
        CRUD::setValidation(ProductImageRequest::class);
        CRUD::field('file_path');
        CRUD::field('sort_order');
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
