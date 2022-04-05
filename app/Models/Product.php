<?php

namespace App\Models;

use App\Models\Allergen;
use BinaryCats\Sku\HasSku;
use App\Models\ProductImage;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Support\Str;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Storage;

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
    protected $appends = ['photo'];
    // protected $hidden = [];
    // protected $dates = [];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    public function allergens()
    {
        return $this->belongsToMany(Allergen::class);
    }
    
    public function setImageAttribute($value)
    {
        $attribute_name = "image";
        $disk = "public";

        $this->uploadFileToDisk($value, $attribute_name, $disk, '');
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