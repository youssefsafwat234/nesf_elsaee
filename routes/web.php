<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
//
//
Route::get('test', function () {
// تحديد النطاق الفرعي بناءً على البيئة
   $subdomain = config('app.env') === 'production' ? 'iamservices' : 'test-iamservices';

   // إعداد رابط API
   $url = "https://test-iamservices.semati.sa/nafath/api/v1/client/authorize/";

   // إعداد عميل Guzzle
   $client = new \GuzzleHttp\Client();

   try {
       // إرسال الطلب
       $response = $client->post($url, [
           'headers' => [
               "id" => "1000062537",
               'Content-Type' => 'application/json',
               'Authorization' => "apikey 8f4b3d9a-e931-478d-a994-28a725159ab9",
               "action" => "SpRequest",
               "service" => "Login"

           ],
           'timeout' => 120
       ]);

       // معالجة الاستجابة
       $data = json_decode($response->getBody()->getContents(), true);

       return response()->json($data);
   } catch (\Exception $e) {
       // التعامل مع الأخطاء
       return response()->json(['error' => $e->getMessage()], 500);
   }



});


