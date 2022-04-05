<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Http\Requests\AddressRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use App\Models\Countries;

class AddressCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation {
        store as protected parentStore;
        update as protected parentUpdate;
    }
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    private $userId;

    public function setup()
    {
        CRUD::setModel(\App\Models\Address::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/address');
        CRUD::setEntityNameStrings('address', 'addresses');

        $this->userId = \Route::current()->parameter('user_id');

        $this->crud->orderBy('is_primary_address', 'DESC');
         if ($this->userId != null && $this->userId !== "0") {
            $this->crud->setRoute(config('backpack.base.route_prefix') . '/address/'.$this->userId);

            $this->crud->operation('list', function () {
                $this->crud->addClause('where', 'user_id', $this->userId );
            });
         }
        else {
            $this->crud->setRoute(config('backpack.base.route_prefix') . '/address/0');
            $this->crud->denyAccess([ 'create', 'update', 'delete']);
        }
    }

     protected function store(AddressRequest $request)
     {
        if ($request['is_primary_address'] ===  "1") 
            $this->crud->model->setPrimaryAddress($this->userId);

        return $this->parentStore();
     }

     protected function update(AddressRequest $request)
     {
        if ( $request['is_primary_address'] ===  "1") 
            $this->crud->model->setPrimaryAddress($this->userId);

        return $this->parentUpdate();
     }

    protected function setupListOperation()
    {
        CRUD::addColumns([
            [
                'name' => 'user_id',
                'attribute' => 'name'
            ],
            [
                'name' => 'address',
                'label' => 'address'
            ],
            [
                'name' => 'country'
            ],
            [   'name' => 'phone_number' ],
            [   'name' => 'address' ],
            [   'name' => 'country']
        ]);

        CRUD::addColumn([
            'name' => 'is_primary_address',
            'label' => 'Status',
            'value' => function ($column) {
                if($column->is_primary_address === 1) 
                    return 'default';
                return 'inactive';
            },
            'wrapper' => [
                'element' => 'span',
                'class' => function ($crud, $column) {
                    if ($column['text'] === 'default')
                        return 'badge badge-success';
                    return 'badge badge-error';
                }
            ]
                
        ]);
    }

    protected function setupCreateOperation()
    {
      

        CRUD::setValidation(AddressRequest::class);
        CRUD::addFields([
            [
                'name' => 'User',
                'type' => 'text',
                'attributes' => [
                    'readonly' => 'readonly'
                ],
                'value' => User::find($this->userId)->name
            ],
            [
                'name' => 'user_id',
                'value' => $this->userId,
                'type' => 'hidden'
            ],
            [   'name' => 'first_name' ],
            [   'name' => 'last_name' ],
            [   'name' => 'phone_number' ],
            [   'name' => 'telephone' ],
            [   'name' => 'company' ],
            [   'name' => 'address' ],
            [   'name' => 'street' ],
            [   'name' => 'zip_code' ],
            [   'name' => 'country', 'type' => 'select_from_array' ,
                'options' => (new Countries)->getCountries() ,
                'attributes' => [
                  'id' => 'country',
          ] ],
            [   'name' => 'is_primary_address' ],
        ]);
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
