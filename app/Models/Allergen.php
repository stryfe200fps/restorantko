<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Allergen extends Model
{
    use \Backpack\CRUD\app\Models\Traits\CrudTrait;
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'name'
    ];

    public function index()
    {
        return '';
    }

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }
}
