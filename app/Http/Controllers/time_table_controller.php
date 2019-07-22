<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\time_table;

use DB;
class time_table_controller extends Controller
{
    
    
    public function class_time_table(Request $request,Response $response){
        $time_table = new time_table($request->all()['college']);
        // echo $request;
        $keys = array_keys((array)$request->all()['params']);
        $query=$time_table->get();

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
        $time_table=new time_table($request->all()['college']);
        $time_table->department=$request->all()['params']['department'];
        $time_table->start_time=$request->all()['params']['start_time'];
        $time_table->room=$request->all()['params']['room'];
        $time_table->division=$request->all()['params']['division'];
        $time_table->sdrn=$request->all()['params']['sdrn'];
        $time_table->end_time=$request->all()['params']['end_time'];
        $time_table->batch=$request->all()['params']['batch'];
        $time_table->subject=$request->all()['params']['subject'];
        $time_table->day=$request->all()['params']['day'];
        $time_table->year=$request->all()['params']['year'];
        $time_table->save();
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
