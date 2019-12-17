<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{

    protected $fillable = ['name', 'slug'];

    public function deliveryTimes()
    {
        return $this->belongsToMany('App\DeliveryTime', 'city_delivery_times');
    }
    public function cityDeliveryTimes()
    {
        return $this->hasMany('App\CityDeliveryTime');
    }
}
