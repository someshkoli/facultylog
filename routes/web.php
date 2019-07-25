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
Route::post('/all_faculty_info','faculty@get_all_faculty');
Route::post('/timetable_class_view','time_table_controller@class_time_table_view');
Route::post('/timetable_enter','time_table_controller@store');
Route::post('/timetable_record_delete','time_table_controller@destroy');

Route::post('/test','time_table_controller@test');


Route::post('/user',function(Request $request){
    $keys = array_keys((array)$request->all());
    return response()->json($keys);
});
