<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;


class faculty extends Controller
{
    public function getdepartment(Request $request)
    {
        $data = DB::connection('RAIT')->table('Department')->get()->toJson();
        return response($data);
    }

    public function get_all_faculty(Request $request)
    {
        $courses = DB::connection('RAIT')->table('course')->select('Subject_name')->get();
        $course = array();
        $faculty = array();
        $faculties = DB::connection('RAIT')->table('faculty')->select('sdrn', DB::raw('concat(First_name," ",Last_name) AS name'))->get();
        foreach ($courses as $c) {
            array_push($course, $c->Subject_name);
        }

        foreach ($faculties as $f) {
            if ($f->name == null) {
                continue;
            } else {
                array_push($faculty, $f);
            }
        }

        return json_encode([
            'course' => $course,
            'faculty' => $faculty
        ]);
    }

    public function get_faculty(Request $request, Response $response)
    {
        //echo (string)$request->all();
        $courses = DB::connection('RAIT')->table('course')->select('Subject_name')
            ->where('Year', "=", $request->all()['params']['year'])
            ->where('Department', '=', $request->all()['params']['department'])
            ->get();
        $course = array();
        $faculty = array();
        $faculties = DB::connection('RAIT')->table('faculty')
            ->select('sdrn', DB::raw('concat(First_name," ",Last_name) AS name'))
            ->where('Department', '=', $request . all()['params']['department'])
            ->get();
        foreach ($courses as $c) {
            array_push($course, $c->Subject_name);
        }

        foreach ($faculties as $f) {
            if ($f->name == null) {
                continue;
            } else {
                array_push($faculty, $f);
            }
        }

        return json_encode([
            'course' => $course,
            'faculty' => $faculty
        ]);
    }
}
