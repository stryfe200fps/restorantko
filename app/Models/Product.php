<?php

namespace App\Models;

use App\Models\Allergen;
use BinaryCats\Sku\HasSku;
use App\Models\ProductImage;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\app\Models\Traits\CrudTrait;

class Product extends Model
{
    use CrudTrait;
    use HasSku;
    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'products';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
    // protected $fillable = [];
    // protected $hidden = [];
    // protected $dates = [];
    protected $cats = ['image' => 'array'];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    public function allergens()
    {
        return $this->belongsToMany(Allergen::class);
    }

    public function productImages()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function productBanner()
    {
        return $this->hasMany(ProductImage::class);
    }


    public function getItNow()
    {
        return 'attr';
    }
    
    public function setImageAttribute($value)
    {
        return [1,2,5];
    }
    
    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
