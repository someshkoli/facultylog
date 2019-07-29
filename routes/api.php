<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// Route::post('/timetable_view_class','time_table_controller@class_time_table');
// Route::post('/timetable_view_faculty','time_table_controller@facultytimetable');
// Route::post('/get_department','faculty@getdepartment');
// Route::post('/department','faculty@getdepartment');
// Route::post('/faculty_info','faculty@get_faculty');
// Route::post('/all_faculty_info','faculty@get_all_faculty');
// Route::post('/timetable_class_view','time_table_controller@class_time_table_view');
// Route::post('/timetable_enter','time_table_controller@store');
// Route::post('/timetable_record_delete','time_table_controller@destroy');
// Route::post('/test','time_table_controller@test');
