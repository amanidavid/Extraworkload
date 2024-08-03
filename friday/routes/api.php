<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Livewire\AttandenceRecords;
use Illuminate\Support\Facades\DB;

use App\Models\Ratiba;
use App\Models\Enrollment;
use App\Models\lecture_in;
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
                // Check if the RFID card belongs to a student enrolled in the module
                $enrollment = Enrollment::whereHas('module', function ($query) use ($moduleCode) {
                    $query->where('module_code', $moduleCode);
                })->where('uid_id', $tag->id)->first();

                // Check if the RFID card belongs to a lecturer assigned to the module
                $lecturer = DB::table('lecture_ins_tables')
                    ->join('lecturerlevels', 'lecture_ins_tables.lecturerlevels_id', '=', 'lecturerlevels.id')
                    ->where('lecture_ins_tables.module_id', function ($query) use ($moduleCode) {
                        $query->select('id')
                            ->from('modules')
                            ->where('module_code', $moduleCode);
                    })
                    ->where('lecturerlevels.user_id', $tag->user_id)
                    ->first();

                if ($enrollment) {
                    // Student scan: proceed with student-specific logic
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
                    $existingAttendance = DB::table('records')
                        ->select('records.ratiba_id', 'records.enrollment_id')
                        ->join('ratibas', 'records.ratiba_id', '=', 'ratibas.id')
                        ->join('enrollments', 'records.enrollment_id', '=', 'enrollments.id')
                        ->join('modules', 'enrollments.module_id', '=', 'modules.id')
                        ->join('rfids', 'enrollments.uid_id', '=', 'rfids.id')
                        ->join('clocks as start_clock', 'ratibas.start_time_id', '=', 'start_clock.id')
                        ->join('clocks as end_clock', 'ratibas.end_time_id', '=', 'end_clock.id')
                        ->where('enrollments.module_id', $enrollment->module_id)
                        ->where('enrollments.uid_id', $tag->id)
                        ->whereRaw('CURTIME() BETWEEN start_clock.clock AND end_clock.clock')
                        ->exists();

                    if ($existingAttendance) {
                        $matches[] = [
                            "module_code" => $moduleCode,
                            "message" => "RFID card already scanned for this module within the scheduled time"
                        ];
                    } else {
                        // Create attendance sheet for student
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
                } elseif ($lecturer) {
                    // Lecturer scan: proceed with lecturer-specific logic
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
                    $lecturerId = $lecturer->id;

                    // Check for existing attendance within the module's scheduled time
                    $existingAttendances = DB::table('records')
                        ->select('records.ratiba_id', 'records.lecture_id')
                        ->join('ratibas', 'records.ratiba_id', '=', 'ratibas.id')
                        ->join('lecture_ins_tables', 'records.lecture_id', '=', 'lecture_ins_tables.id')
                        ->join('modules', 'lecture_ins_tables.module_id', '=', 'modules.id')
                        ->join('lecturerlevels', 'lecture_ins_tables.lecturerlevels_id', '=', 'lecturerlevels.id')
                        ->join('users', 'lecturerlevels.user_id', '=', 'users.id')
                        ->join('rfids', 'users.id', '=', 'rfids.user_id')
                        ->join('clocks as start_clock', 'ratibas.start_time_id', '=', 'start_clock.id')
                        ->join('clocks as end_clock', 'ratibas.end_time_id', '=', 'end_clock.id')
                        ->where('lecture_ins_tables.module_id', $lecturer->module_id)
                        ->where('lecturerlevels.user_id', $tag->user_id)
                        ->where('rfids.id', $tag->id)
                        ->whereRaw('CURTIME() BETWEEN start_clock.clock AND end_clock.clock')
                        ->exists();

                    if ($existingAttendances) {
                        $matches[] = [
                            "module_code" => $moduleCode,
                            "message" => "RFID card already scanned for this module within the scheduled time"
                        ];
                    } else {
                        // Create attendance sheet for lecturer
                        $attendanceSheet = new Record();
                        $attendanceSheet->ratiba_id = $scheduleId;
                        $attendanceSheet->lecture_id = $lecturerId;

                        if ($attendanceSheet->save()) {
                            $matches[] = [
                                "module_code" => $moduleCode,
                                "message" => "Lecturer attendance recorded successfully"
                            ];
                        } else {
                            $matches[] = [
                                "module_code" => $moduleCode,
                                "message" => "Failed to record lecturer attendance"
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
