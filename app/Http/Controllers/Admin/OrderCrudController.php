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
use Carbon\Carbon;
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
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation {
        show as protected parentShow;
    }

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

    public function show($request)
    {
        $content = $this->parentShow($request);
        CRUD::column('total')->type('number')->prefix('₱')->thousands_sep(',')->wrapper([ 
            'element' => 'b'
        ]) ;

        // $this->crud->addButtonFromView('top', 'add_address', 'add_address', 'end');
        Widget::add()->to('after_content')->type('view')->view('product_order_list')->total($this->data['entry']->total)->orderId((int)\Route::current()->parameter('id'));
        Widget::add()->to('after_content')->type('view')->view('vendor.backpack.crud.order_status.set_status')->status($this->data['entry']->status)->orderId((int)\Route::current()->parameter('id'));
        return $content;

    }

    public function status(Request $request)
    {
       $order = Order::where('id', $request['id'])->first();
       if ($request['status'] === 'delivered') {
        $order->delivered_at = Carbon::now();
       }
       $order->status = $request['status'];
       $order->save();
       return 'saved';
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */


    protected function setupListOperation()
    {
        CRUD::column('user_id')->attribute('name');
        CRUD::column('invoice_no');
        CRUD::column('full_name')->label('receiver');
        $this->crud->addColumn([
        'name'    => 'status',
        'label'   => 'status',
        'wrapper' => [
            'element' => 'span',
            'class' => function ($crud, $column, $entry, $related_key) {
                if ($column['text'] == 'delivered') {
                    return 'badge badge-success';
                }
                if ($column['text'] == 'new') {
                    return 'badge badge-info';
                }
                if ($column['text'] == 'rejected') {
                    return 'badge badge-error';
                }
                return 'badge badge-warning';
            },
        ],
    ]);
        CRUD::column('ordered_at')->value( fn ($x) => (Carbon::parse($x->ordered_at))->diffForHumans()  );
        CRUD::column('delivered_at')->value( fn ($x) => $x->delivered_at !== NULL ? (Carbon::parse($x->delivered_at))->diffForHumans() : ''  );
        CRUD::column('total')->type('number')->prefix('₱')->thousands_sep(',');
    }

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
        'value' => '<b>your orders</b> <div style="margin-top:20px; margin-bottom:20px;" class=""> <div class ="row"> <div class="col-md-12">  <table class="cart"> <thead> <tr> <th>quantity</th> <th>name</th> <th>price</th> </tr> </thead> <tbody class="item">  </tbody> </table> </div> </div>'
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
        CRUD::addField([
            'name' => 'total',
            'type' => 'hidden',
            'attributes' => [
                'class' => 'total'
            ]
        ]);
        Widget::add()->to('before_content')->type('view')->view('product_order');

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
        $order->total = (float)$request['total'];
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

        

        \Alert::success(trans('backpack::crud.insert_success'))->flash();
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
