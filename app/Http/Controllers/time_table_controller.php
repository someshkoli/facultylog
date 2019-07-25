<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\time_table;

use DB;

class time_table_controller extends Controller
{

    // give class timetable to ttc
    public function class_time_table(Request $request, Response $response)
    {
        $time_table = new time_table($request->all()['college']);
        // echo $request;
        $keys = array_keys((array) $request->all()['params']);
        $query = DB::connection($request->all()['college'])->table('time_table')->get();
        $data = array();
        $faculty = DB::connection('RAIT')->table('faculty')
            ->select('sdrn', DB::raw('concat(First_name," ",Middle_name," ",Last_name) AS name'))
            ->get();

        foreach ($keys as $key) {
            if ($request->all()['params'][$key] == "") {
                continue;
            }
            $query = $query->where($key, $request->all()['params'][$key]);
        }

        foreach ($query as $q) {
            foreach ($faculty as $f) {
                if ($f->sdrn == $q->sdrn) {
                    $q->sdrn = $f->name;
                }
            }
        }
        return response($query);
    }

    //give faculty timtable to principal
    public function facultytimetable(Request $request, Response $response)
    {
        $time_table = new time_table();
        //echo $request->all()['sdrn'];
        $data = $time_table->all()->where('sdrn', $request->all()['sdrn'])->toJson();
        return response($data);
    }

    //give faculty curent time to principal
    public function faculty_current_time(Request $request, Response $response)
    {
        $time_table = new time_table($request->all()['college']);
        $data = $time_table->all()->where(function ($query) {
            $query->where('sdrn', $request->all()['sdrn']);
        });
    }

    // public function class_time_table_view(Request $request, Response $response)
    // {
    //     $time_table = new time_table();
    // }

    public function test(Request $request, Response $response)
    {
        $days = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
        $final_time_table = array();
        foreach ($days as $day) {
            $dayd = [
                'day' => $day
            ];
            $data = DB::connection($request->all()['college'])->table("time_table", "faculty")
                ->select(
                    'time_table.subject',
                    'time_table.day',
                    'time_table.start_time',
                    'time_table.end_time',
                    'time_table.sdrn',
                    'time_table.room',
                    'time_table.batch',
                    DB::raw('concat(faculty.First_name," ",faculty.Last_name) AS name')
                )
                ->join('faculty', 'time_table.sdrn', "=", 'faculty.sdrn')
                ->where('division', "=", $request->all()['params']['division'])
                ->where('day', $day)
                ->get();
            //echo (string)$day;
            // response($data);
            foreach ($data as $d) {
                // echo "213131";
                // return json_encode($data);
                $temp = [
                    $d->start_time . "-" . $d->end_time => [
                        'subject' => $d->subject,
                        'class' => $d->room,
                        'faculty' => $d->name
                    ]
                ];
                $dayd = array_merge($dayd, $temp);
                //return json_encode($dayd);
            }

            array_push($final_time_table, $dayd);
        }
        return response($final_time_table);
    }

    //add a new schedule record
    public function store(Request $request)
    {
        $sub_short = DB::connection($request->all()['college'])
            ->table('course')
            ->select('Subject_shortname')
            ->where('Subject_name', '=', $request->all()['params']['subject'])
            ->get();
        $faculty_sdrn = DB::connection($request->all()['college'])
            ->table('faculty')
            ->select('Sdrn')
            ->where('Sdrn', '=', $request->all()['params']['sdrn'])
            ->get();
        $keys = array_keys((array) $request->all()['params']);
        $time_table = new time_table($request->all()['college']);
        $time_table->department = $request->all()['params']['department'];
        $time_table->start_time = $request->all()['params']['start_time'];
        $time_table->room = $request->all()['params']['room'];
        $time_table->division = $request->all()['params']['division'];
        $time_table->sdrn = $faculty_sdrn[0]->Sdrn;
        $time_table->end_time = $request->all()['params']['end_time'];
        $time_table->batch = $request->all()['params']['batch'];
        $time_table->subject = $sub_short[0]->Subject_shortname;
        $time_table->day = $request->all()['params']['day'];
        $time_table->year = $request->all()['params']['year'];
        $time_table->save();
        return Response($faculty_sdrn);
    }

    public function destroy(Request $request, Response $response)
    {
        DB::connection($request->all()['college'])->table('time_table')->where('srno', $request->all()['params']['srno'])->delete();
    }
}
