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
        $days = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "SaturdayOdd","SaturdayEven"];
        $keys = array_keys((array) $request->all()['params']);
        
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
        
        public function full_class(Request $request, Response $response)
        {
            $days = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "SaturdayOdd","SaturdayEven"];
            $final_time_table = array();
            $test_final_table=array();
            $sub_short = array();
            foreach ($days as $day) {
                $dayd = [
                    'day' => $day
                ];
                // get data of non practical times
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
                ->where('time_table.batch', "All")
                ->get();
            $temp_day_data = array();
            foreach ($data as $d) {
                if ($d->batch != "All") {
                    continue;
                }
                $temp_day_data = array_merge($temp_day_data, [$d->start_time . "-" . $d->end_time => [
                    "type" => "All",
                    "info" => $d,
                ]]);
                $dayd = array_merge($dayd, $temp_day_data);
                array_push($sub_short, $d->subject);
            }
            //ends here
            // // test function 
            // $test_day_data=array();
            // foreach($data as $d){
            //     if($test_day_data[$d->start_time . "-" . $d->end_time]){
            //         array_push($test_day_data[$d->start_time . "-" . $d->end_time]["info"],$d);
            //     }
            //     else{
            //         $test_day_data=[
            //             $d->start_time . "-" . $d->end_time => [
            //                 "type" => "All",
            //                 "info" => $d,
            //             ],
            //         ];
            //     }
            // }
            // array_push($test_final_table,$test_day_data);
            // test function ends
            // get data of practical times
            $time_scale = DB::connection($request->all()['college'])->table('time_table')->select('start_time', 'end_time')
                ->where('division', "=", $request->all()['params']['division'])
                ->where('department', "=", $request->all()['params']['department'])
                ->where('year', "=", $request->all()['params']['year'])
                ->where('day', $day)
                ->where('batch', "!=", "All")
                ->distinct('start_time')
                ->get();
            foreach ($time_scale as $ts) {
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
                    ->where('time_table.start_time', $ts->start_time)
                    ->get()->toArray();
                $temp_batch_day_data = [$ts->start_time . "-" . $ts->end_time => [
                    "type" => "batch",
                    "info" => $batch_data,
                ]];
                $dayd = array_merge($dayd, $temp_batch_day_data);
                array_push($sub_short, "Practicals");
            }
            //ends here
            array_push($final_time_table, $dayd);
        }
        // create header
        $header=array();
        $time=array();
        array_push($header,(array)[
            "text" => "Day/Time",
            "align" => "center",
            "sortable" => false,
            "value" => "day"]);
        $time_scale_header = DB::connection($request->all()['college'])->table('time_table')->select('start_time', 'end_time')
                ->where('division', "=", $request->all()['params']['division'])
                ->where('department', "=", $request->all()['params']['department'])
                ->where('year', "=", $request->all()['params']['year'])
                ->distinct('start_time')
                ->orderBy('start_time')
                ->get()
                ->toArray();
        foreach($time_scale_header as $tsh){
            array_push($header,(array)[
                "text" => substr($tsh->start_time,0,5)."-".substr($tsh->end_time,0,5),
                "sortable" => false,
                "value" => $tsh->start_time."-".$tsh->end_time,
                "align" => "center"]);
            array_push($time,$tsh->start_time."-".$tsh->end_time);
        }
        // ends here
        $sub_short = array_unique($sub_short);
        $full_time_table = [
            "time_table" => $final_time_table,
            "subjects" => $sub_short,
            "format"=>[
                "header" => $header,
                "time" => $time
            ],
            "test" => $test_final_table
        ]; 
        return response($full_time_table);
    }

    //aosidhaaosijdasa
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
