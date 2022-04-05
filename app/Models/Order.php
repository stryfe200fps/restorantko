<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use \Backpack\CRUD\app\Models\Traits\CrudTrait;
    use HasFactory;

    protected $guarded = ['id'];

    public $timestamps = false;

    public function orderRows()
    {
        return $this->hasMany(OrderRow::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    protected array $cartItems = [];

    public function getCart()
    {
       return $this->cartItems; 
    }

    public function getFullNameAttribute()
    {
        return $this->attributes['first_name'] .' ' . $this->attributes['last_name'];
    }

    public function setCart($order_id)
    {
        $cartItems[] = $order_id;
    }

    public function generateInvoiceNumber()
    {
    $latest = Order::latest()->orderBy('id', 'desc')->first();
    if (! $latest) 
        return 'RES00001';
    return 'RES' . sprintf('%05d', preg_replace("/[^0-9\.]/", '', $latest->invoice_no) +1);
    }

    
}
