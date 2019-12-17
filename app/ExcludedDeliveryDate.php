<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExcludedDeliveryDate extends Model
{

    protected $fillable = ['city_delivery_time_id', 'date'];

    public function cityDeliveryTime()
    {
        return $this->belongsTo('App\CityDeliveryTime');
    }
}
