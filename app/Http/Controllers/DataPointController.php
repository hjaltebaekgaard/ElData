<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

use Illuminate\Http\Request;
use App\Models\DataPoint;
use Carbon\Carbon;

class DataPointController extends Controller
{
    //

    /*   public function testFunction()
    {
        $data = DataPoint::where('metering_point_id', '=', '900000000760759414')->get();
        $time_start = $data[1]['time_start'];
        // $datetime =
        $time_end = $time_start->copy();
        return [$time_start, $time_end->addHour()];
    } */

    public function testFunction(Request $request)
    {
        // return json_decode(DataPoint::where('metering_point_id', '>', '900000000425132779')->get());

        $fileName = "eldata.csv";
        $dataPoints = DataPoint::all();
        // return $dataPoints[0]->metering_point_id;

        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $columns = ['MålepunktID', 'Fra_dato', 'Til_dato', 'Mængde', 'Måleenhed', 'Kvalitet', 'Type'];

        $callback = function () use ($dataPoints, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($dataPoints as $dataPoint) {

                $row[$columns[0]] = $dataPoint['metering_point_id'];
                $row[$columns[1]] = $dataPoint['time_start'];
                $row[$columns[2]] = $dataPoint['time_start']->copy()->addHour();
                $row[$columns[3]] = $dataPoint['quantity'];
                $row[$columns[4]] = "KWh";
                $row[$columns[5]] = $dataPoint['quality'];
                $row[$columns[6]] = "Tidsserie";

                fputcsv($file, array($row[$columns[0]], $row[$columns[1]], $row[$columns[2]], $row[$columns[3]], $row[$columns[4]], $row[$columns[5]], $row[$columns[6]]));
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
        /* $users = DB::table('users')->get();

        return  ['users' => $users]; */
    }
}
