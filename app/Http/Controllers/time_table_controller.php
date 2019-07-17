<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\time_table;

use DB;
class time_table_controller extends Controller
{
    
    
    public function class_time_table(Request $request,Response $response){
        $time_table = new time_table();
        // echo $request;
        $keys = array_keys((array)$request->all()['params']);
        $query=DB::connection($request->all()['college'])->table("time_table")->get();

        foreach($keys as $key){
            $query=$query->where($key,$request->all()['params'][$key]);
        }
        return response($query); 
    }

    public function facultytimetable(Request $request,Response $response){
        $time_table = new time_table();
        //echo $request->all()['sdrn'];
        $data= $time_table->all()->where('sdrn',$request->all()['sdrn'])->toJson();
        return response($data); 
    }

    public function faculty_current_time(Request $request,Response $response){
        $time_table=new time_table();
        $data=$time_table->all()->where(function($query){
            $query->where('sdrn',$request->all()['sdrn']); 
        });
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $keys = array_keys((array)$request->all()['params']);
        $time_table=DB::connection($request->all()['college'])->time_table;
        $data=[];
       // echo json_encode($time_table);
        foreach($keys as $key){
            $temp=[$key => $request->all()['params'][$key]];
            $data=array_merge($data,$temp);
        }
       // echo $data;
        $time_table::insert($data);

        return Response("hello");
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
