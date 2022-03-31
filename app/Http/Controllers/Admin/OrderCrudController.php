<?php

namespace App\Http\Controllers\Admin;

use DateTime;
use App\Models\Order;
use App\Models\Address;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Requests\OrderRequest;
use App\Models\OrderRow;
use Backpack\CRUD\app\Library\Widget;
use Illuminate\Support\Facades\Cache;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
/**
 * Class OrderCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class OrderCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation {
        create as protected parentCreate;
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
        CRUD::setModel(\App\Models\Order::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/order');
        CRUD::setEntityNameStrings('order', 'orders');
        CRUD::denyAccess('update');
        CRUD::denyAccess('delete');
        //  \Alert::add('warning', 'This is a yellow/orange bubble.');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::column('user_id');
        CRUD::column('invoice_no');
        CRUD::column('first_name');
        CRUD::column('last_name');
        CRUD::column('shipping_last_name');
        CRUD::column('shipping_phone_number');
        CRUD::column('shipping_address');
        CRUD::column('shipping_country');
        CRUD::column('status');
        CRUD::column('ordered_at');
        CRUD::column('delivered_at');
        CRUD::column('total');

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

    protected function create()
    {
       $content =  $this->parentCreate();
      
       return $content;
    }

    protected function setupCreateOperation()
    {
        CRUD::setValidation(OrderRequest::class);

        CRUD::field('user_id');
        // CRUD::field('orders')->name('orders');
       
        CRUD::addField([
        'name'  => 'separator',
        'type'  => 'custom_html',
        'value' => '<b>your orders</b> <div style="margin-top:20px; margin-bottom:20px;" class="container "> <div class ="row"> <div class="col-md-12">  <table class="cart"> <thead> <tr> <th>quantity</th> <th>name</th> <th>price</th> </tr> </thead> <tbody class="item">  </tbody> </table> </div> </div>'
        ]);
        CRUD::field('invoice_no');
        CRUD::field('first_name');
        CRUD::field('last_name');
        CRUD::field('email');
        CRUD::addField([
            'name' => 'orders',
            'type' => 'hidden',
            'attributes' => [
                'class' => 'json-holder'
            ]
        ]);
        Widget::add()->to('before_content')->type('view')->view('product_order')->someAttr('buratching');

       }

    public function store(OrderRequest $request)
    {
        $address = Address::where('user_id', $request['user_id'])->first();
        if ($address === NULL) {
        \Alert::add('error', 'This user do not have any addresses')->flash();
        return redirect('/admin/order');
        }

        $this->crud->registerFieldEvents();
        $request['additional'] = 'this is an aditional data baby';
        $order = new Order; 

        
        $order->user_id = $request['user_id'] ;
        $order->invoice_no = $request['invoice_no'];
        $order->first_name =  $request['first_name'];
        $order->last_name =  $request['last_name'];
        $order->email =  $request['email'];
        $order->shipping_first_name =  $address->first_name;
        $order->shipping_last_name =  $address->last_name;
        $order->shipping_phone_number =  $address->phone_number;
        $order->shipping_zip_code =   $address->zip_code;
        $order->shipping_telephone_number =  $address->telephone;
        $order->shipping_company =  $address->company;
        $order->shipping_address =  $address->address;
        $order->shipping_street =  $address->street;
        $order->shipping_country =  $address->country;
        $order->status = 'new';
        $order->ordered_at = date('Y-m-d H:i:s');
        $order->total = 0 ;
        $order->save();

        foreach (json_decode( $request['orders'], true) as $id => $quantity) {
            $product = Product::where('id', $id)->first() ;
            $orderRow = new OrderRow;
            $orderRow->order_id =  $order->id ;
            $orderRow->product_id = $id;
            $orderRow->name = $product->name;
            $orderRow->price = $product->price;
            $orderRow->quantity = $quantity;
            $orderRow->save();
        }

        // \Alert::success(trans('backpack::crud.insert_success'))->flash();
        return redirect('/admin/order');
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
