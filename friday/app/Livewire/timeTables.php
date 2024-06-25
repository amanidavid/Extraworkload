<?php

namespace App\Livewire;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request; // Import Request class
use Carbon\Carbon; 
use App\Models\Attandencesheet;
use App\Models\Schedule;
use App\Models\Enrollment;

class timeTables extends Component
{
    public function render()
    {
        return view('livewire.time-table');
    }

    public function create(Request $request){
        try {
            // Retrieve the UID from the request
            $cardUID = $request->getContent();
            $RESULT=Tag::all()->first();
            // $values = explode(':', $request->input() );
            if($cardUID==$RESULT->rfid_card){
                $response = true;
            }else{
                $response = false;
    
            }
            return response()->json([$cardUID,$RESULT->rfid_card,$response]);
     } catch (\Exception $e) {
            // Return error response if something goes wrong
            return response()->json(['error' => 'Failed to process RFID scan', 'message' => $e->getMessage()], 500);
        }
     
    
    }

    // public function store(Request $request){
    //      $response = DB::select('call getModule()');

    //     $moduleEnrolls = DB::select('call getEnrolls()');
            
    //     $enrollmentModule = array_map(function($items) {
    //             return $items->module_code;
    //         }, $moduleEnrolls);



    //     $moduleCodes = array_map(function($item) {
    //             return $item->module_code;
    //         }, $response);

    //         $isMatchFound = false;
    //         foreach ($moduleCodes as $moduleCode) {
    //             if (in_array($moduleCode, $enrollmentModule)) {
    //                  // Return the matching module code
    //                 // return response()->json(["module_code" => $moduleCode]);
    //                             // Example: Determine schedule_id (replace with actual logic)
            
    //         // Example: Find schedule with the module code
    //         $schedule = Schedule::whereHas('module', function ($query) use ($moduleCode) {
    //             $query->where('module_code', $moduleCode);
    //         })->first();

    //         if (!$schedule) {
    //             return response()->json(["error" => "Schedule not found for module code: $moduleCode"]);
    //         }
    //         $scheduleId = $schedule->id;

    //         // Example: Find enrollment with the module code
    //         $enrollment = Enrollment::whereHas('module', function ($query) use ($moduleCode) {
    //             $query->where('module_code', $moduleCode);
    //         })->first();

    //         if (!$enrollment) {
    //             return response()->json(["error" => "Enrollment not found for module code: $moduleCode"]);
    //         }
    //         $enrollmentId = $enrollment->id;

    //         // Create attendance sheet
    //         $attendanceSheet = new Attandencesheet();
    //         $attendanceSheet->schedule_id = $scheduleId;
    //         $attendanceSheet->enrollment_id = $enrollmentId;
    //         // $attendanceSheet->save();
    //         if($attendanceSheet->save()){
    //             return response()->json(["saved"]);

    //         }else{
    //             return response()->json([" not saved"]);

    //         }
    //             }else {
    //                 return response()->json(["match not found"]);
                
    //         }
    //     }
    // }
    public function store(Request $request)
{
    // Call stored procedures
    $response = DB::select('call getModule()');
    $moduleEnrolls = DB::select('call getEnrolls()');

    // Extract module codes from getEnrolls() result
    $enrollmentModule = array_map(function ($item) {
        return $item->module_code;
    }, $moduleEnrolls);

    // Extract module codes from getModule() result
    $moduleCodes = array_map(function ($item) {
        return $item->module_code;
    }, $response);

    $matches = [];

    foreach ($moduleCodes as $moduleCode) {
        if (in_array($moduleCode, $enrollmentModule)) {
            // Example: Find schedule with the module code
            $schedule = Schedule::whereHas('module', function ($query) use ($moduleCode) {
                $query->where('module_code', $moduleCode);
            })->first();

            if (!$schedule) {
                $matches[] = [
                    "module_code" => $moduleCode,
                    "message" => "Schedule not found for module code: $moduleCode"
                ];
                continue;
            }
            $scheduleId = $schedule->id;

            // Example: Find enrollment with the module code
            $enrollment = Enrollment::whereHas('module', function ($query) use ($moduleCode) {
                $query->where('module_code', $moduleCode);
            })->first();

            if (!$enrollment) {
                $matches[] = [
                    "module_code" => $moduleCode,
                    "message" => "Enrollment not found for module code: $moduleCode"
                ];
                continue;
            }
            $enrollmentId = $enrollment->id;

            // Create attendance sheet
            $attendanceSheet = new Attandencesheet();
            $attendanceSheet->schedule_id = $scheduleId;
            $attendanceSheet->enrollment_id = $enrollmentId;

            if ($attendanceSheet->save()) {
                $matches[] = [
                    "module_code" => $moduleCode,
                    "message" => "Attendance sheet created successfully"
                ];
            } else {
                $matches[] = [
                    "module_code" => $moduleCode,
                    "message" => "Failed to save attendance sheet"
                ];
            }
        } else {
            $matches[] = [
                "module_code" => $moduleCode,
                "message" => "Match not found"
            ];
        }
    }

    return response()->json($matches);
}

}
