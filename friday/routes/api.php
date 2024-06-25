<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Livewire\AttandenceRecords;
use Illuminate\Support\Facades\DB;

use App\Models\Schedule;
use App\Models\Enrollment;
use App\Models\Attandencesheet;
use App\Models\Module;
use App\Models\Tag;
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

Route::post('/test', function (Request $request) {
    try {
        // Retrieve the UID from the request
        $cardUID = $request->getContent();
        
        // Retrieve the RFID card from the database
        $tag = Tag::where('rfid_card', $cardUID)->first();

        // Verify the card UID
        if ($tag && $cardUID == $tag->rfid_card) {
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
                    // Find schedule with the module code
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

                    // Find enrollment with the module code
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
