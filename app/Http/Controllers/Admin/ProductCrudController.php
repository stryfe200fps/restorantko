<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use App\Models\Allergen;
use Illuminate\Http\Request;
use App\Http\Requests\ProductRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class ProductCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
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
            [
                'name' => 'productImages',
                'height' => '300px',
                'width' => '200px',
                'type' => 'image',
                'disk' => 'local',
                'name' => 'productImages',
                'model' => 'App\Models\ProductImage',
                'value' => fn ($q)  => $q->productImages[0]->file_path ?? ''
            ],
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
            ]
            ]);

        $this->crud->addButtonFromView('line', 'moderate', 'moderate', 'beginning');
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
            [
                'name' => 'image',
                'label' => 'Image',
                'type' => 'base64_image',
                'crop' => true
            ]
        ]);
      
    }

    public function store(Request $request)
    {
        dd($request);
        return $this->parentStore();
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
