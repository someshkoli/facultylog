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
        $result = array();
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
    public function faculty_time_table(Request $request, Response $response)
    {
        $result = array();
        $days = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
        $keys = array_keys((array) $request->all()['params']);
        //$query = DB::connection($request->all()['college'])->table('time_table')->get();
        $faculty = DB::connection('RAIT')->table('faculty')
            ->select('sdrn', DB::raw('concat(First_name," ",Last_name) AS name'))
            ->get();

        foreach ($days as $d) {

            $query = DB::connection($request->all()['college'])->table('time_table')->get();

            $day1 = [
                'day' => $d,
            ];

            foreach ($keys as $key) {
                if ($request->all()['params'][$key] == "") {
                    continue;
                }

                if ($key == "time") {
                    $query = $query->where('start_time', "<=", $request->all()['params']['time']);
                    $query = $query->where('end_time', ">=", $request->all()['params']['time']);
                    continue;
                }
                $query = $query->where($key, $request->all()['params'][$key]);
            }
            $query = $query->where('day', $d);
            foreach ($query as $q) {
                foreach ($faculty as $f) {
                    if ($f->sdrn == $q->sdrn) {
                        $q->sdrn = $f->name;
                    }
                }
            }
            $day1 = array_merge($day1, ['timetable' => $query]);
            array_push($result, $day1);
        }
        return response($result);
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



    public function backup__full_class(Request $request, Response $response)
    {
        $days = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
        $final_time_table = array();
        $sub_short = array();
        $time_frame_array = array();
        foreach ($days as $day) {
            $day_data = [
                'day' => $day
            ];

            //+++++++++to retrieve all class data ++++++++++++//
                if(type == "ALL") {
                    // norma;
                } else {
                    // pracs
                }


            $data = DB::connection($request->all()['college'])->table("time_table", "faculty")
                ->select(
                    'time_table.subject',
                    'time_table.day',
                    'time_table.start_time',
                    'time_table.end_time',
                    'time_table.sdrn',
                    'time_table.room',
                    'time_table.department',
                    'time_table.year',
                    'time_table.division',
                    'time_table.batch',
                    DB::raw('concat(faculty.First_name," ",faculty.Last_name) AS name')
                )
                ->join('faculty', 'time_table.sdrn', "=", 'faculty.sdrn')
                ->where('time_table.division', "=", $request->all()['params']['division'])
                ->where('time_table.department', "=", $request->all()['params']['department'])
                ->where('time_table.year', "=", $request->all()['params']['year'])
                ->where('time_table.day', $day)
                ->where('time_table.batch', "!=", "All")
                ->get();
            $time_frame = DB::connection($request->all()['college'])->table("time_table")->select('start_time', "end_time")
                ->where('division', "=", $request->all()['params']['division'])
                ->where('department', "=", $request->all()['params']['department'])
                ->where('year', "=", $request->all()['params']['year'])
                ->where('day', $day)
                ->where('batch', "!=", "All")
                ->distinct('start_time')
                ->get()->toArray();
            $day_data_single = array();
            foreach ($data as $d) {
                $day_data_single = array_merge($day_data_single, [$d->start_time . "-" . $d->end_time => [
                    "type" => "All",
                    "info" => $d,
                    "time_frame" => $time_frame
                ]]);
                $day_data = array_merge($day_data, $day_data_single);
                array_push($sub_short, $d->subject);
            }
            //+++++++++++++ends here+++++++++++++++//

            //++++++++++++++++++to retrieve batch data +++++++++++++//
            foreach ($time_frame as $tf) {
                $batch_data_at_time = DB::connection($request->all()['college'])->table("time_table", "faculty")
                    ->select(
                        'time_table.subject',
                        'time_table.day',
                        'time_table.start_time',
                        'time_table.end_time',
                        'time_table.sdrn',
                        'time_table.room',
                        'time_table.department',
                        'time_table.year',
                        'time_table.division',
                        'time_table.batch',
                        DB::raw('concat(faculty.First_name," ",faculty.Last_name) AS name')
                    )
                    ->join('faculty', 'time_table.sdrn', "=", 'faculty.sdrn')
                    ->where('time_table.division', "=", $request->all()['params']['division'])
                    ->where('time_table.department', "=", $request->all()['params']['department'])
                    ->where('time_table.year', "=", $request->all()['params']['year'])
                    ->where('time_table.day', $day)
                    ->where('time_table.batch', "!=", "All")
                    ->where('start_time', "=", $tf->start_time)
                    ->get()->toArray();
                $day_data_single = array_merge($day_data_single, [$d->start_time . "-" . $d->end_time => [
                    "type" => "batch",
                    "info" => $batch_data_at_time,
                    "test" => $time_frame
                ]]);
                $day_data = array_merge($day_data, $day_data_single);
            }
            //+++++++++++++++++ ends +++++++++++++++//
            array_push($final_time_table, $day_data);
        }
        $sub_short=array_unique($sub_short);
        $full_time_table = [
            "time_table" => $final_time_table,
            "subjects" => $sub_short,
            
        ];
        return response($full_time_table);
    }






    public function full_class(Request $request, Response $response)
    {
        $days = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
        $final_time_table = array();
        $sub_short1 = array();
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
                    'time_table.department',
                    'time_table.year',
                    'time_table.division',
                    'time_table.batch',
                    DB::raw('concat(faculty.First_name," ",faculty.Last_name) AS name')
                )
                ->join('faculty', 'time_table.sdrn', "=", 'faculty.sdrn')
                ->where('time_table.division', "=", $request->all()['params']['division'])
                ->where('time_table.department', "=", $request->all()['params']['department'])
                ->where('time_table.year', "=", $request->all()['params']['year'])
                ->where('time_table.day', $day)
                ->where('time_table.batch', "!=", "All")
                ->get();

            $time_frame = DB::connection($request->all()['college'])->table("time_table")->select('start_time', "end_time")
                ->where('batch', "!=", "All")
                ->where('day', "=", $day)
                ->distinct('start_time')
                ->get();

            // print_r((array) $batch_data[3]->start_time);
            $temp = array();
            foreach ($data as $d) {
                $temp = array_merge($temp, [$d->start_time . "-" . $d->end_time => [
                    "type" => "All",
                    "info" => $d,
                ]]);
                $dayd = array_merge($dayd, $temp);
                array_push($sub_short1, $d->subject);
            }
            foreach ($time_frame as $tf) {
                $batch_data = DB::connection($request->all()['college'])->table("time_table", "faculty")
                    ->select(
                        'time_table.subject',
                        'time_table.day',
                        'time_table.start_time',
                        'time_table.end_time',
                        'time_table.sdrn',
                        'time_table.room',
                        'time_table.department',
                        'time_table.year',
                        'time_table.division',
                        'time_table.batch',
                        DB::raw('concat(faculty.First_name," ",faculty.Last_name) AS name')
                    )
                    ->join('faculty', 'time_table.sdrn', "=", 'faculty.sdrn')
                    ->where('time_table.division', "=", $request->all()['params']['division'])
                    ->where('time_table.department', "=", $request->all()['params']['department'])
                    ->where('time_table.year', "=", $request->all()['params']['year'])
                    ->where('time_table.day', $day)
                    ->where('time_table.batch', "!=", "All")
                    ->where('start_time', "=", $tf->start_time)
                    ->get()->toArray();

                $dayd = array_merge($dayd, [$tf->start_time . "-" . $tf->end_time => [
                    "type" => "batch",
                    "info" => $batch_data,
                ]]);
            }
            array_push($final_time_table, $dayd);
        }
        $sub_short1 = array_unique($sub_short1);
        $full_time_table = [
            "time_table" => $final_time_table,
            "subjects" => $sub_short1,
            "test" => $time_frame
        ];
        return response($full_time_table);
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
