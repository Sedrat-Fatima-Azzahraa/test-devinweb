<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DeliveryTime extends Model
{

    protected $fillable = ['span'];
    public function cities()
    {
        return $this->belongsToMany('App\City', 'city_delivery_times');
    }
    public function cityDeliveryTimes()
    {
        return $this->hasMany('App\CityDeliveryTime');
    }
}
