<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\AddressRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class AddressCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class AddressCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Address::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/address');
        CRUD::setEntityNameStrings('address', 'addresses');
    }

      /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */

   
    protected function setupListOperation()
    {
        CRUD::column('id');
        CRUD::column('user_id');
        CRUD::column('first_name');
        CRUD::column('last_name');
        CRUD::column('phone_number');
        CRUD::column('telephone');
        CRUD::column('company');
        CRUD::column('address');
        CRUD::column('street');
        CRUD::column('zip_code');
        CRUD::column('country');
        CRUD::column('is_primary_address');

        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']); 
         */
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(AddressRequest::class);

        // dd($this->crud->entry);
        CRUD::field('id');
        CRUD::field('user_id');
        CRUD::field('first_name');
        CRUD::field('last_name');
        CRUD::field('phone_number');
        CRUD::field('telephone');
        CRUD::field('company');
        CRUD::field('address');
        CRUD::field('street');
        CRUD::field('zip_code');
        CRUD::field('country');
        CRUD::field('is_primary_address');

        /**
         * Fields can be defined using the fluent syntax or array syntax:
         * - CRUD::field('price')->type('number');
         * - CRUD::addField(['name' => 'price', 'type' => 'number'])); 
         */
    }

    /**
     * Define what happens when the Update operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
