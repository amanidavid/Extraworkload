<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Livewire\AttandenceRecords;
use Illuminate\Support\Facades\DB;
use App\Models\Clock;
use App\Models\Schedule;
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
Route::post('/tests',[timeTables::class,'create']);
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
        $RESULT=Tag::all()->first();
        // $values = explode(':', $request->input() );
        // Verify the card UID
        if ($cardUID == $RESULT->rfid_card) {
            // Get current day of the week (1 for Monday through 7 for Sunday)
             // SQL Query to retrieve the schedule details
                // Get current day of the week (1 for Monday through 7 for Sunday)
            $currentDay = now()->format('l'); // Get the current weekday name
            $currentTime = now()->format('H:i:s'); // Get the current time

            // Query to retrieve the schedule details
            $scheduleDetails = DB::table('schedules')
                ->join('modules', 'schedules.module_id', '=', 'modules.id')
                ->join('seasons', 'schedules.wdays_id', '=', 'seasons.id')
                ->join('clocks as start_clock', 'schedules.start_time_id', '=', 'start_clock.id')
                ->join('clocks as end_clock', 'schedules.end_time_id', '=', 'end_clock.id')
                ->join('venues', 'schedules.venue_id', '=', 'venues.id')
                ->select(
                    'seasons.weekday_name',
                    'modules.module_code',
                    'start_clock.clock as start_time',
                    'end_clock.clock as end_time',
                    'venues.venue'
                )
                ->where('seasons.weekday_name', $currentDay)
                ->whereTime('start_clock.clock', '<=', $currentTime)
                ->whereTime('end_clock.clock', '>=', $currentTime)
                ->orderBy('weekday_name')
                ->orderBy('start_time')
                ->orderBy('end_time')
                ->get();

            return Response::json([
               $scheduleDetails
            ]);
        } else {
            return response()->json(['error' => 'Invalid card UID'], 400);
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
