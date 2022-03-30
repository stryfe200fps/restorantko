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
        CRUD::setModel(\App\Models\Product::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/product');
        CRUD::setEntityNameStrings('product', 'products');
        // CRUD::enableExportButtons();
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupShowOperation()
    {
        // CRUD::column('wew')->value('this is a custom value baby ');
        $this->setupListOperation();
        // dd( $this->crud->getEntries()[0]->productImages );
    //  CRUD::column('productImage')->height('300px')->width('200px')->type('image')->disk('local')->name('productImages')->model('App\Models\ProductImage')->value(
    //         fn ($q)  => $q
    //     );
        //  dd($this->id->getModel);
    }

    protected function setupListOperation()
    {

        CRUD::column('productImages')->height('300px')->width('200px')->type('image')->disk('local')->name('productImages')->model('App\Models\ProductImage')->value(
            fn ($q)  => $q->productImages[0]->file_path ?? ''
        );
        // CRUD::column('productBanner')->name('productBanner')->type('image');
        CRUD::column('SKU');
        CRUD::column('name');
        CRUD::column('price')->type('number')->thousands_sep(',')->prefix('₱');
        CRUD::column('allergens')->name('allergens')->model("App\Models\Allergen");
        // CRUD::column('file_path')->name('file_path')->model("App\Models\ProductImage");
        $this->crud->addButtonFromView('line', 'moderate', 'moderate', 'beginning');
        // CRUD::column('description');

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
        CRUD::setValidation(ProductRequest::class);
         

        CRUD::field('name');
        CRUD::field('price')->type('number');
        CRUD::field('description')->type('summernote');

        CRUD::addField([
            'name' => 'allergens',
            'type' => 'checklist',
            'model' => "App\Models\Allergen"
        ]);
       
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
            'product_id' => $id
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
