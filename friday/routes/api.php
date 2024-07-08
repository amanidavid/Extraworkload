<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Livewire\AttandenceRecords;
use Illuminate\Support\Facades\DB;

use App\Models\Ratiba;
use App\Models\Enrollment;
use App\Models\Record;
use App\Models\Module;
use App\Models\Tag;
use App\Models\Rfid;
use Carbon\Carbon;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::get('/testing', function (Request $request) {

    // Query to retrieve the schedule details
    $scheduleDetails = "hello";

    return Response::json([
       $scheduleDetails
    ]);
});

// Route::post('/test', function (Request $request) {
//     try {
//         // Retrieve the UID from the request
//         $cardUID = $request->getContent();
//         $RESULT=Tag::all()->first();
//         // $values = explode(':', $request->input() );
//         // Verify the card UID
//         if ($cardUID == $RESULT->rfid_card) {
//         }
//     } catch (\Exception $e) {
//         // Return error response if something goes wrong
//         return response()->json(['error' => 'Failed to process RFID scan', 'message' => $e->getMessage()], 500);
//     }
    
    

// });
;

// Route::post('/test', function (Request $request) {
//     try {
//         // Retrieve the UID from the request
//         $cardUID = $request->getContent();

//         // Retrieve the RFID tag from the database
//         $tag = Rfid::where('uid', $cardUID)->first();

//         // Verify the card UID
//         if ($tag && $cardUID == $tag->uid) {
//             // Call stored procedures
//             $response = DB::select('call getModule()');
//             $moduleEnrolls = DB::select('call getEnrolls()');

//             // Extract module codes from getEnrolls() result
//             $enrollmentModules = array_map(function ($item) {
//                 return $item->module_code;
//             }, $moduleEnrolls);

//             // Extract module codes from getModule() result
//             $moduleCodes = array_map(function ($item) {
//                 return $item->module_code;
//             }, $response);

//             $matches = [];

//             foreach ($moduleCodes as $moduleCode) {
//                 // Check if the RFID card is enrolled in the module
//                 $enrollment = Enrollment::whereHas('module', function ($query) use ($moduleCode) {
//                     $query->where('module_code', $moduleCode);
//                 })->where('uid_id', $tag->id)->first();

//                 if ($enrollment) {
//                     // Find schedule with the module code
//                     $schedule = Ratiba::whereHas('module', function ($query) use ($moduleCode) {
//                         $query->where('module_code', $moduleCode);
//                     })->first();

//                     if (!$schedule) {
//                         $matches[] = [
//                             "module_code" => $moduleCode,
//                             "message" => "Schedule not found for module code: $moduleCode"
//                         ];
//                         continue;
//                     }
//                     $scheduleId = $schedule->id;
//                     $enrollmentId = $enrollment->id;
                    
//                     // Check for existing attendance within the module's scheduled t

//                     // Create attendance sheet
//                     $attendanceSheet = new Record();
//                     $attendanceSheet->ratiba_id = $scheduleId;
//                     $attendanceSheet->enrollment_id = $enrollmentId;

//                     if ($attendanceSheet->save()) {
//                         $matches[] = [
//                             "module_code" => $moduleCode,
//                             "message" => "Attendance sheet created successfully"
//                         ];
//                     } else {
//                         $matches[] = [
//                             "module_code" => $moduleCode,
//                             "message" => "Failed to save attendance sheet"
//                         ];
//                     }
//                 } else {
//                     // If the RFID card is not enrolled in the module, add a message indicating this
//                     $matches[] = [
//                         "module_code" => $moduleCode,
//                         "message" => "RFID card not enrolled in this module"
//                     ];
//                 }
//             }

//             return response()->json($matches);
//         } else {
//             return response()->json(['error' => 'Invalid RFID card'], 400);
//         }
//     } catch (\Exception $e) {
//         // Return error response if something goes wrong
//         return response()->json(['error' => 'Failed to process RFID scan', 'message' => $e->getMessage()], 500);
//     }
// });
Route::post('/test', function (Request $request) {
    try {
        // Retrieve the UID from the request
        $cardUID = $request->getContent();

        // Retrieve the RFID tag from the database
        $tag = Rfid::where('uid', $cardUID)->first();

        // Verify the card UID
        if ($tag && $cardUID == $tag->uid) {
            // Call stored procedures or retrieve necessary data
            $response = DB::select('call getModule()');
            $moduleEnrolls = DB::select('call getEnrolls()');

            // Extract module codes from getEnrolls() result
            $enrollmentModules = array_map(function ($item) {
                return $item->module_code;
            }, $moduleEnrolls);

            // Extract module codes from getModule() result
            $moduleCodes = array_map(function ($item) {
                return $item->module_code;
            }, $response);

            $matches = [];

            foreach ($moduleCodes as $moduleCode) {
                // Check if the RFID card is enrolled in the module
                $enrollment = Enrollment::whereHas('module', function ($query) use ($moduleCode) {
                    $query->where('module_code', $moduleCode);
                })->where('uid_id', $tag->id)->first();

                if ($enrollment) {
                    // Find schedule with the module code
                    $schedule = Ratiba::whereHas('module', function ($query) use ($moduleCode) {
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
                    $enrollmentId = $enrollment->id;

                    // Check for existing attendance within the module's scheduled time
                    $existingAttendance  = DB::table('records')
                    ->select('records.ratiba_id', 'records.enrollment_id')
                    ->join('ratibas', 'records.ratiba_id', '=', 'ratibas.id')
                    ->join('enrollments', 'records.enrollment_id', '=', 'enrollments.id')
                    ->join('modules', 'enrollments.module_id', '=', 'modules.id')
                    ->join('rfids', 'enrollments.uid_id', '=', 'rfids.id')
                    ->join('clocks as start_clock', 'ratibas.start_time_id', '=', 'start_clock.id')
                    ->join('clocks as end_clock', 'ratibas.end_time_id', '=', 'end_clock.id')
                    ->where('enrollments.module_id', $enrollment->id)
                    ->where('enrollments.uid_id', $tag->id)
                    ->whereRaw('CURTIME() BETWEEN start_clock.clock AND end_clock.clock')
                    ->get();

                    if ($existingAttendance) {
                        $matches[] = [
                            "module_code" => $moduleCode,
                            "message" => "RFID card already scanned for this module within the scheduled time"
                        ];
                    } else {
                        // Create attendance sheet
                        $attendanceSheet = new Record();
                        $attendanceSheet->ratiba_id = $scheduleId;
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
                    }
                } else {
                    // If the RFID card is not enrolled in the module, add a message indicating this
                    $matches[] = [
                        "module_code" => $moduleCode,
                        "message" => "RFID card not enrolled in this module"
                    ];
                }
            }

            return response()->json($matches);
        } else {
            return response()->json(['error' => 'Invalid RFID card'], 400);
        }
    } catch (\Exception $e) {
        // Return error response if something goes wrong
        return response()->json(['error' => 'Failed to process RFID scan', 'message' => $e->getMessage()], 500);
    }
});



Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


// Route::post('/attandencerecords', [AttandenceRecords::class, 'ussdHandler']);
