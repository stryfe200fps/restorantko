<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    use \Backpack\CRUD\app\Models\Traits\CrudTrait;
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'file_path',
        'sort_order'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}

