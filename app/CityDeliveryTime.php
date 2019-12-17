<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CityDeliveryTime extends Model
{

    protected $fillable = ['city_id', 'delivery_time_id'];

    public function excludedDeliveryDates()
    {
        return $this->hasMany('App\ExcludedDeliveryDate');
    }
    public function city()
    {
        return $this->belongsTo('App\City');
    }
    public function deliveryTime()
    {
        return $this->belongsTo('App\DeliveryTime');
    }
}
