<?php

namespace App\Http\Controllers;
use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;


class faculty extends Controller
{
    public function getdepartment(Request $request){
       $data=DB::connection('RAIT')->table('Department')->get()->toJson();
       return response($data);
    }

    public function get_faculty(Request $request,Response $response){
        //echo (string)$request->all();
        $data=DB::connection('RAIT')->table('course')->get('Subject_name');
        $result=array(array());
        $temp=array();
        foreach($data as $d){
            echo $d->Subject_name;
            array_push($temp,$d->Subject_name);
        }
        array_push($result,$temp);

        

        return response($result);

    }
}


