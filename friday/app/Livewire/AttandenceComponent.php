<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
// use App\Models\Clock;
use App\Models\Schedule;
// use App\Models\Season;
// use App\Models\Module;
use Carbon\Carbon;

class AttandenceComponent extends Component
{
    public function render()
    {
        return view('livewire.attandence-component');
    }
    public function getEspData(Request $request)
    {
      // Get time and day from the request
      $time = $request->input('time');
    //   $day = $request->input('day');

      if (!$time) {
          return response()->json(['error' => 'Time parameter is missing'], 400);
      }

    //   if (!$day) {
    //       return response()->json(['error' => 'Day parameter is missing'], 400);
    //   }

      // Parse time and validate format
     // Parse time and validate format
    
     try {
        // Check if the time is in HH:MM format (e.g., 18:00)
        if (strlen($time) == 5 && substr($time, 2, 1) == ':') {
            // Parse the time assuming current date
            $currentTime = Carbon::createFromFormat('H:i', $time);
            $currentTime->setDate(Carbon::now()->year, Carbon::now()->month, Carbon::now()->day);
        } elseif (strlen($time) == 16 && substr($time, 10, 1) == 'T') {
            // Check if the time is in ISO8601 format (e.g., 2024-06-13T18:00:00Z)
            $currentTime = Carbon::createFromFormat('Y-m-d\TH:i:sP', $time);
        } else {
            throw new \Exception('Invalid time format');
        }
    
      } catch (\Exception $e) {
          return response()->json(['error' => 'Invalid time format'], 400);
      }

      // Get the current day if not provided
      $currentDay =  $currentTime->format('l'); // e.g., 'Monday'

      // Fetch schedules for the current day
      $schedules = Schedule::with(['module', 'season', 'startClock', 'endClock', 'venue'])
          ->whereHas('season', function ($query) use ($currentDay) {
              $query->where('weekday_name', $currentDay);
          })
          ->get();

      // Check if the current time falls within any of the schedule times
      foreach ($schedules as $schedule) {
          $start_time = Carbon::parse($schedule->startClock->clock);
          $end_time = Carbon::parse($schedule->endClock->clock);

          if ($currentTime->between($start_time, $end_time, true)) {
              return response()->json([
                  'weekday_name' => $schedule->season->weekday_name,
                  'module_code' => $schedule->module->module_code,
                  'start_time' => $schedule->startClock->clock,
                  'end_time' => $schedule->endClock->clock,
                  'venue' => $schedule->venue->venue,
              ], 200);
          }
      }

      return response()->json(['error' => 'Current time is not within any allowed session time'], 403);
 }
}
