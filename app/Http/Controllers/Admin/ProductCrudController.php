<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use App\Models\Allergen;
use Illuminate\Http\Request;
use App\Http\Requests\ProductRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

class ProductCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation {
        store as protected parentStore;
    }
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation {
        show as protected parentShow;
    }
    
    public function setup()
    {
        CRUD::setModel(\App\Models\Product::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/product');
        CRUD::setEntityNameStrings('product', 'products');
    }

    protected function setupListOperation()
    {
        CRUD::addColumns([
         
            [   'name' => 'SKU' ],
            [   'name' => 'name' ],
            [   'name' => 'price',
                'type' => 'number',
                'thousands_sep' => ',',
                'prefix' => '₱'
            ],
            [
                'name' => 'allergens',
                'model' => 'App\Models\Allergen'
            ],
            [
                'name' => 'image',
                'type' => 'image',
                'prefix' => 'storage/',
                'height' => '70px',
                'width' => '100px'
            ]
            ]);

        $this->crud->addButtonFromView('line', 'moderate', 'moderate', 'beginning');
    }

    protected function store(Request $request)
    {
       return $this->parentStore();
    }

    protected function setupCreateOperation()
    {
        CRUD::setValidation(ProductRequest::class);

        CRUD::addFields([
            [   'name' => 'name'   ],
            [
                'name' => 'price',
                'type' => 'number'   
            ],
            [
                'name' => 'description',
                'type' => 'summernote'
            ],
            [
                'name' => 'allergens',
                'type' => 'checklist',
                'model' => 'App\Models\Allergen'
            ],
        ]);

     CRUD::addField(
        [
        'label' => "Product Main Image",
        'name' => "image",
        'type' => 'upload',
        'upload' => true,
        'crop' => true, // set to true to allow cropping, false to disable
        'aspect_ratio' => 1, // omit or set to 0 to allow any aspect ratio
        ] 
        ) ;
    }

    public function moderate($id)
     {
        $this->crud->addField([ // image
            'label' => "Thumbnail Image",
            'name' => "thumbnail_image",
            'type' => 'image',
            'upload' => true,
            'disk' => 'uploads'
        ],'both');

        return view('moderate', [
            'product_id' => $id,
            'redirect' => config('backpack.base.route_prefix') 
        ]);
     }
    
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }

    public function upload(Request $request)
    {
        $product = Product::find((int) $request->product_id);
        foreach ($request->file('file') as $img) { 
        $imageName = $product->sku."_".$img->getClientOriginalName();
        $img->move(public_path('storage'), $imageName);
        $product->productImages()->create([
            'file_path' => $imageName,
            'sort_order' =>  0
        ]);
        }
        return response()->json(['success'=>$imageName]);
    }
}
