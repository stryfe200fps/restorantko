<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Http\Requests\AddressRequest;
use App\Models\Address;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Http\Request;

/**
 * Class AddressCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class AddressCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation {
        store as protected parentStore;
    }
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
        $passed_league_id = \Route::current()->parameter('user_id');

         if ($passed_league_id != null && $passed_league_id !== "0") {
            $this->crud->setRoute(config('backpack.base.route_prefix') . '/address/'.$passed_league_id);
            $this->crud->operation('list', function () use ($passed_league_id) {
            $this->crud->addClause('where', 'user_id', $passed_league_id );
            });
         }
        else {
            $this->crud->setRoute(config('backpack.base.route_prefix') . '/address/0');
            $this->crud->denyAccess('create');
        }
    }

      /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */

     protected function store(Request $request)
     {

        $user_id = \Route::current()->parameter('user_id');
        //this is not working..
        // if ( $request['is_primary_address'] ===  "1") {
        //     foreach(Address::find($user_id)->get() as $address) {
        //         $address->is_primary_address = 0;
        //         $address->save();
        //     }
        //  }
         $content = $this->parentStore();

        return $content;
     }

    protected function setupListOperation()
    {

        CRUD::column('user_id')->attribute('name');
        CRUD::column('fullName');
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
        // CRUD::field('id');
        $user_id = \Route::current()->parameter('user_id');
        CRUD::addField([
            'name' => 'User',
            'type' => 'text',
            'attributes' => [
                'readonly' => 'readonly'
            ],
            'value' => User::find($user_id)->name
        ]);
        CRUD::field('user_id')->value( $user_id )->type('hidden');
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
