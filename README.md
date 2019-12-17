<p  align="center"><img  src="https://res.cloudinary.com/dtfbvvkyp/image/upload/v1566331377/laravel-logolockup-cmyk-red.svg"  width="400"></p>

# Devinweb project : delivery problems. 

This is the backend part of the Devinweb project. It's built using the framework **Laravel**.


# Usage

To run locally, clone this GitHub repository to your machine. 
In order to manage its dependencies, Laravel utilizes **Composer** dependencies which is can be installed via the command:

    composer install
  Once installed, you need to generate the `.env` file using the command:
  

    cp .env.example .env
 The next thing you should do is set the application key via the command:

     php artisan key:generate
In order to create the tables, you need to create a database in your database server and configure it in the Laravel's `.env` file.

Then to run the migrations, execute the `migrate` Artisan command:

     php artisan migrate
To run the project, use the following `serve` command:

    php artisan serve --host=127.0.0.1

  

# Backend Documentation

## API

All endpoints verify the request's payload and return a `400` in case of a bad request (absence of parameter, invalid date format...) or a `404` in case of a resource not found or similar error (City not found, Delivery time not attached to city ...). in case of success, server returns a `200` code.

**Admin Endpoints:** 

API calls should be made to:

    http://admin.localhost:8000/api

**-- POST /api/city**

This endpoint is used to create a new city. It takes the "name" as a parameter.
### Example
Request Payload:
``` json
{
    "name": "Tanger"
}
```
Response:
``` json
{
	"id": 1,
	"name": "Tanger",
	"slug": "tanger",
	"created_at": "2019-12-17 15:46:39",
	"updated_at": "2019-12-17 15:46:39"
}
```
**-- POST /api/delivery-times**

This endpoint is used to create delivery time spans. It takes the "span" as a parameter.
### Example
Request Payload:
``` json
{
    "span": "12->14PM"
}
```
Response:
``` json
{
   "id":1,
   "span":"12->14PM",
   "created_at":"2019-12-17 17:31:18",
   "updated_at":"2019-12-17 17:31:18"
}
```
**-- POST /api/city/{city_id}/delivery-times**

This endpoint is used to attach delivery times to a city. It takes an array of "delivery times" as a parameter.
### Example
Request Payload:
``` json
{
   "deliveryTimes":[
      1,
      3,
      4,
      5000
   ]
}
``` 
Response:
``` json
[
   {
      "id":1,
      "span":"12->14PM",
      "created_at":"2019-12-17 17:31:18",
      "updated_at":"2019-12-17 17:31:18",
      "pivot":{
         "city_id":1,
         "delivery_time_id":1
      }
   }
]
```
**-- POST /api/city/{city_id}/delivery-times/{delivery_time_id}**

This endpoint is used to exclude a delivery time from a city's delivery dates.

### Example

Request Payload:
``` json
{
    "date": "2019-12-17"
}
```
Response:
```json
{
   "id":1,
   "city_delivery_time_id":1,
   "date":"2019-12-17",
   "created_at":"2019-12-17 18:04:25",
   "updated_at":"2019-12-17 18:04:25"
}
```
**-- POST /api/city/{city_id}/delivery-dates**

This endpoint is used to exclude all delivery times.

### Example

Request Payload:
```json
{
    "date": "2018-09-14"
}
```
Response:

    Excluded successfully.

##

 **Website**

API calls should be sent to:

     http://localhost:8000/api

**-- POST api/city/{city_id}/delivery-dates-times/{number_of_days_to_get}**

This endpoint is used to return all the city delivery dates times except for the excluded ones in a specific format.

### Response Example:
```json
{
   "dates":[
      {
         "day_name":"Tuesday",
         "date":"2019-12-17",
         "delivery_times":[

         ]
      },
      {
         "day_name":"Wednesday",
         "date":"2019-12-18",
         "delivery_times":[
            {
               "id":1,
               "city_id":1,
               "delivery_time_id":1,
               "created_at":"2019-12-17 17:49:51",
               "updated_at":"2019-12-17 17:49:51"
            }
         ]
      },
      {
         "day_name":"Thursday",
         "date":"2019-12-19",
         "delivery_times":[
            {
               "id":1,
               "city_id":1,
               "delivery_time_id":1,
               "created_at":"2019-12-17 17:49:51",
               "updated_at":"2019-12-17 17:49:51"
            }
         ]
      },
      {
         "day_name":"Friday",
         "date":"2019-12-20",
         "delivery_times":[
            {
               "id":1,
               "city_id":1,
               "delivery_time_id":1,
               "created_at":"2019-12-17 17:49:51",
               "updated_at":"2019-12-17 17:49:51"
            }
         ]
      },
      {
         "day_name":"Saturday",
         "date":"2019-12-21",
         "delivery_times":[
            {
               "id":1,
               "city_id":1,
               "delivery_time_id":1,
               "created_at":"2019-12-17 17:49:51",
               "updated_at":"2019-12-17 17:49:51"
            }
         ]
      }
   ]
}
```
