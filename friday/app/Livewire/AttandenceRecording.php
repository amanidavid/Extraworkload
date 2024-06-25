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

class AttendanceRecording extends Component
{
    public function render()
    {
        return view('livewire.attendance-records');
    }

    public function getEspData(Request $request)
    {
      // Get time and day from the request
      $time = $request->input('time');
      $day = $request->input('day');

      if (!$time) {
          return response()->json(['error' => 'Time parameter is missing'], 400);
      }

      if (!$day) {
          return response()->json(['error' => 'Day parameter is missing'], 400);
      }

      // Parse time and validate format
      try {
          $currentTime = Carbon::createFromFormat(Carbon::ISO8601, $time);
      } catch (\Exception $e) {
          return response()->json(['error' => 'Invalid time format'], 400);
      }

      // Get the current day if not provided
      $currentDay = $day ?: $currentTime->format('l'); // e.g., 'Monday'

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

    public function getScheduleData()
    {
        $scheduleData = Schedule::select([
            'schedules.id',
            'seasons.weekday_name',
            'modules.module_code',
            'start_clock.clock as start_time',
            'end_clock.clock as end_time',
            'venues.venue'
        ])
            ->join('seasons', 'schedules.wdays_id', '=', 'seasons.id')
            ->join('modules', 'schedules.module_id', '=', 'modules.id')
            ->join('clocks as start_clock', 'schedules.start_time_id', '=', 'start_clock.id')
            ->join('clocks as end_clock', 'schedules.end_time_id', '=', 'end_clock.id')
            ->join('venues', 'schedules.venue_id', '=', 'venues.id')
            ->groupBy('schedules.id', 'seasons.weekday_name', 'modules.module_code', 'start_time', 'end_time', 'venues.venue')
            ->orderBy('seasons.weekday_name')
            ->orderBy('start_time')
            ->orderBy('end_time')
            ->get();

        return response()->json($scheduleData, 200);
    }
}
