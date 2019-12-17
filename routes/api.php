<?php

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use App\City;
use App\DeliveryTime;
use App\CityDeliveryTime;
use App\ExcludedDeliveryDate;

Route::domain('admin.localhost')->group(function () {

    // Endpoint to add a city
    Route::post('/city', function (Request $request)
    {
        // Check if JSON payload contains name
        if($request->input('name')== null){
            // Return 400 Bad Request response if no name
            return response('Bad Request', 400);
        }
        $name = $request->input('name');
        //Create City if it doesn't already exist
        $city = City::updateOrCreate([
            'name' => $name,
            'slug' => Str::slug($name, '-')
        ]);
        //Return Created city as a JSON payload
        return response()->json($city);
    });

    // Endpoint to add a delivery time
    Route::post('/delivery-times', function (Request $request)
    {
        // Check if JSON payload contains span
        if($request->input('span')== null){
            // Return 400 Bad Request response if no span
            return response('Bad Request', 400);
        }
        //Create Delivery Time if it doesn't already exist
        $deliveryTime = DeliveryTime::updateOrCreate([
            'span' => $request->input('span')
        ]);
        //Return Created delivery time as a JSON payload
        return response()->json($deliveryTime);
    });

    // Endpoint to attach delivery times to city
    Route::post('/city/{city_id}/delivery-times',
                function ($city_id, Request $request)
    {
        // Check if JSON payload contains an array of Delivery Times
        if($request->input('deliveryTimes') == null ||
           !is_array($request->input('deliveryTimes')) )
        {
            // Return 400 error if no delivery times array
            return response('Bad Request', 400);
        }
        try{
            // check if city with is $city_id exists
            $city = City::findOrFail($city_id);
        }catch(ModelNotFoundException $e){
            // return 404 error if city doesn't exist
            return response('City not found', 404);
        }
        //
        $deliveryTimes = $request->input('deliveryTimes');

        foreach($deliveryTimes as $dt){
            // check if a delivery time with $dt as id exists
            if(DeliveryTime::where('id', $dt)->first()){
                // attach delivery time to city if it doesn't already exist
                CityDeliveryTime::updateOrCreate(
                    ['city_id' => $city_id, 'delivery_time_id' => $dt]
                );
            }
        }
        // return all of city's delivery times
        return response()->json($city->deliveryTimes);
    });

    // Endpoint to exclude a delivery time from a city's delivery dates
    Route::post('/city/{city_id}/delivery-times/{delivery_time_id}',
                function ($city_id, $delivery_time_id, Request $request)
    {
        //Check if date parameter exists in JSON payload
        if($request->input('date') == null ){
            // if not return 400 bad request
            return response('Bad Request', 400);
        }
        try{
            //check if City has delivery time attached to it
            $cityDeliveryTime = CityDeliveryTime::where([
                ['city_id', '=', $city_id],
                ['delivery_time_id', '=', $delivery_time_id]
            ])->firstOrFail();
        }catch(ModelNotFoundException $e){
            // return 404 if delivery time not attached to city
            return response('Delivery time not attached to city', 404);
        }
        try{
            // exclude delivery time if not already excluded
            $excludedDeliveryDate = ExcludedDeliveryDate::updateOrCreate([
                'city_delivery_time_id' => $cityDeliveryTime->id,
                'date' => $request->input('date')
            ]);
            // return JSON Payload containing excluded date info
            return response()->json($excludedDeliveryDate);
        }catch(QueryException $e){
            // return 400 error if date format is invalid
            return response('Wrong Date Format', 400);
        }
    });

    // Endpoint to exclude all delivery times
    Route::post('/city/{city_id}/delivery-dates',
                function ($city_id, Request $request)
    {
        //Check if date parameter exists in JSON payload
        if($request->input('date') == null ){
            // if not return 400 bad request
            return response('Bad Request', 400);
        }
        //get all City's attached delivery times
        $cityDeliveryTimes = CityDeliveryTime::where('city_id', '=', $city_id)
                                             ->get();
            if(count($cityDeliveryTimes)==0){
                return response('Not found', 404);
                                            }
        foreach($cityDeliveryTimes as $cdt){
            try{
                // exclude delivery time if not already excluded
                ExcludedDeliveryDate::updateOrCreate([
                    'city_delivery_time_id' => $cdt->id,
                    'date' => $request->input('date')
                ]);
            }catch(QueryException $e){
                // return 400 error if date format is invalid
                return response('Wrong Date Format', 400);
            }
        }
        // return 200 success message
        return response('Excluded successfully', 200);
    });
});

Route::post('/city/{city_id}/delivery-dates-times/{days}',
            function ($city_id, $days, Request $request)
{
    // get current time
    $date = date('Y-m-d', time());
    // find city if exists
    try{
        $city = City::findOrFail($city_id);
    }catch(ModelNotFoundException $e){
        // city not found error response
        return response('city not found', 404);
    }
    // init response payload
    $res = ["dates"=>[]];
    for($i=0; $i<$days; $i++){
        // push day's date into response payload
        array_push($res["dates"], [
            "day_name"=>date('l', strtotime($date)),
            "date"=>$date,
            "delivery_times"=> []
        ]);
        // add delivery times while skipping excluded times
        foreach($city->cityDeliveryTimes as $cdt){
            foreach($cdt->excludedDeliveryDates as $edd){
                if($edd->date == $date)
                {
                    // continues the outer loop to next iteration
                    continue 2;
                }
            }
            // create clone of city delivery time var
            $cdtClone = clone $cdt;
            // unset excluded delivery dates attribute
            unset($cdtClone->excludedDeliveryDates);
            // push delivery time to payload
            array_push($res["dates"][$i]["delivery_times"], $cdtClone);
        }
        // increment date by 1 day
        $date = date('Y-m-d', strtotime($date.' + 1 day'));
    }
    // return JSON payload
    return response()->json($res);
});

