<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\time_table;


class time_table_controller extends Controller
{
    
    
    public function class_time_table(Request $request,Response $response){
        $time_table = new time_table();
        $data= $time_table->all()->where(function($query){
            $query->where('department',$request->all()["department"])
                  ->where('year',$request->all()['year'])
                  ->where('division',$request->all()['division']);
        });
        return response($data); 
    }

    public function facultytimetable(Request $request,Response $response){
        $time_table = new time_table();
        //echo $request->all()['sdrn'];
        $data= $time_table->all()->where('sdrn',$request->all()['sdrn'])->toJson();
        return response($data); 
    }

    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $table_data = new time_table();

        $table_data->department=Request("department");
        $table_data->year=Request("year");
        $table_data->division=Request("division");
        $table_data->day=Request("day");
        $table_data->subject=Request("subject");
        $table_data->sdrn=Request("sdrn");
        $table_data->end_time=Request("end_time");
        $table_data->start_time=Request("start_time");
        $table_data->batch=Request("batch");
        $table_data->save();

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
