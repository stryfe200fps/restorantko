<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    public $timestamps = false;
    protected $table = 'addresses';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
    // protected $fillable = [];
    // protected $hidden = [];
    // protected $dates = [];



    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getFullNameAttribute()
    {
        return $this->attributes['first_name'] . ' ' . $this->attributes['last_name'] ;
    }

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    public function setPrimaryAddress(int $userId)
    {
        $checkAddressIfDefault = Address::where([
            'user_id' => $userId,
            'is_primary_address' => true
        ])->get();

        if($checkAddressIfDefault->count() === 0 ) 
            return false;
        
        $currentPrimaryAddress = $checkAddressIfDefault->first();
        $currentPrimaryAddress->is_primary_address = false;
        $currentPrimaryAddress->save();
    }

    public function setIsPrimaryAddressAttribute($value)
    {
        $this->attributes['is_primary_address'] = $value;

        if($this->userHasAddress((int)$this->attributes['user_id']) === 0) {
            $this->attributes['is_primary_address'] = true;
        }

    }

    public function userHasAddress(int $userId) 
    {
        $address = Address::where('user_id', $userId)->get()->count();
        return $address;
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
