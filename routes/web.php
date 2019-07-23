<?php
//use Illuminate\Routing\Route;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function (Request $request) {
    echo "hello";
    return response()->json([
        'stuffasdasd'=> phpinfo()
    ]);
});


Route::get('/monitor',function(Request $request){
    $data=[
        "timetable" => "timetable",
        "errcode" => "errcode"
    ];
    return response()->json($data);
});

Route::post('/monitor',function(Request $request){
    $data=[
        "errcode" => "errcode"];
    return response()->json($data);
});


Route::post('/timetable_view_class','time_table_controller@class_time_table');
Route::post('/timetable_view_faculty','time_table_controller@facultytimetable');
Route::post('/get_department','faculty@getdepartment');
Route::post('/department','faculty@getdepartment');
Route::post('/faculty_info','faculty@get_faculty');

Route::post('/timetable_enter','time_table_controller@store');


Route::post('/user',function(Request $request){
    $keys = array_keys((array)$request->all());
    return response()->json($keys);
});
