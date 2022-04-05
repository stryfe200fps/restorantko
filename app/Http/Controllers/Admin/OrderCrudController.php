<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Order;
use App\Models\Address;
use App\Models\Product;
use App\Models\OrderRow;
use Illuminate\Http\Request;
use App\Http\Requests\OrderRequest;
use Backpack\CRUD\app\Library\Widget;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

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
    
    public function setup()
    {
        CRUD::setModel(\App\Models\Order::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/order');
        CRUD::setEntityNameStrings('order', 'orders');
        CRUD::denyAccess('update');
        CRUD::denyAccess('delete');
    }

    public function show($request)
    {
        $content = $this->parentShow($request);
        CRUD::column('total')->type('number')->prefix('₱')->thousands_sep(',')->wrapper([
            'element' => 'b'
        ]) ;

        Widget::add()->to('after_content')->type('view')->view('product_order_list')->total($this->data['entry']->total)->orderId((int)\Route::current()->parameter('id'));
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

    protected function setupListOperation()
    {
        CRUD::addColumns([
            [
                'name' => 'user_id',
                'attribute' => 'name'
            ],
            [   'name' => 'invoice_no' ],
            [
                'name' => 'full_name',
                'label' => 'receiver'
            ],
            [
                'name' => 'status',
                'label' => 'status',
                'wrapper' => [
                    'element' => 'span',
                    'class' => function ($crud, $column) {
                        if ($column['text'] === 'delivered') 
                            return 'badge badge-success';
                        if ($column['text'] == 'new') 
                            return 'badge badge-info';
                        if ($column['text'] == 'rejected') 
                        return 'badge badge-error';

                        return 'badge badge-warning';
                    }
                ]
            ],
            [
                'name' => 'ordered_at',
                'value' => fn ($order) => (Carbon::parse($order->ordered_at))->diffForHumans() 
            ],
            [
                'name' => 'delivered_at',
                'value' => fn ($order) => $order->delivered_at !== NULL ? Carbon::parse($order->delivered_at)->diffForHumans() : '' 
            ],
            [
                'name' => 'total',
                'type' => 'number',
                'prefix' => '₱',
                'thousands_sep' => ','
            ]
        ]);
    }

    protected function setupCreateOperation()
    {
        CRUD::setValidation(OrderRequest::class);
        Widget::add()->to('before_content')->type('view')->view('product_order'); // widgets to show the ordering card
        CRUD::addFields(
        [ 
            [
                'name'  => 'separator',
                'value' => '<table class="cart"> <thead> <tr> <th>quantity</th> <th>name</th> <th>price</th> 
                            </tr> </thead> <tbody class="item">  </tbody> </table> ',
                'type' => 'custom_html'
            ],
            [   'name' => 'user_id' ],
            [ 
                'name' => 'invoice_no', 
                'type' => 'text', 
                'value' => $this->crud->model->generateInvoiceNumber(),
                'attributes' => [
                    'readonly' => 'readonly'
                ]
            ],
            [   'name' => 'first_name' ],
            [   'name' => 'last_name' ],
            [   'name' => 'email' ],
            // HIDDEN FIELDS
            [ 
                'name' => 'orders', 
                'type' => 'hidden',
                'attributes' => [
                'class' => 'json-holder' // field where orders are stored via json format
                ]
            ],
            [
                'name' => 'total',
                'type' => 'hidden',
                'attributes' => [
                    'class' => 'total' //field where order total is stored
                ]
            ]
        ]);
    }

    public function store()
    {
        $request = $this->crud->validateRequest();
        $address = Address::where([
            'user_id' => $request['user_id'],
            'is_primary_address' => true
        ]
        )->first();

        if ($address === NULL) {
        \Alert::add('error', 'This user do not have any addresses or no primary address')->flash();
        return redirect('/admin/address/'. $request['user_id']);
        }

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

        foreach (json_decode( $request['orders'], true) as $cart) {
            $product = Product::where('id', $cart[0])->first() ;
            $orderRow = new OrderRow;
            $orderRow->order_id =  $order->id;
            $orderRow->product_id = $product->id;
            $orderRow->name = $product->name;
            $orderRow->price = $product->price;
            $orderRow->quantity = $cart[3];
            $orderRow->save();
        }
        \Alert::success(trans('backpack::crud.insert_success'))->flash();
        return redirect('/admin/order');
    }
   
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
