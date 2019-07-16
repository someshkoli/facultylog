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
}
