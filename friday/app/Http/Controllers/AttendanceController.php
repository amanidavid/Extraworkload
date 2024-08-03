<?php
namespace App\Http\Controllers;

use App\Models\Enrollment;
use App\Models\Module;
use App\Models\Record;
use App\Models\Event;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    public function showAttendance()
    {
        $attendanceRecords = Record::with('event')
            ->select('module_code', 'event_id', DB::raw('DATE(created_at) as created_at'))
            ->distinct()
            ->groupBy('module_code', 'event_id', 'created_at')
            ->get()
            ->map(function($record) {
                return [
                    'module_code' => $record->module_code,
                    'event_name' => $record->event ? $record->event->event_name : 'Unknown', // Access event_name
                    'created_at' => $record->created_at,
                ];
            })
            ->toArray();

        return view('attendance.my-attendance', [
            'attendanceRecords' => $attendanceRecords,
            'message' => null,
        ]);
    }

    // Other methods...

    

    public function index()
    {
        $user = Auth::user();

        $userWithAttendance = User::with(['rfids.enrollments.module', 'rfids.enrollments.records.event'])
            ->where('id', $user->id)
            ->first();

        if (!$userWithAttendance) {
            return view('attendance.index', ['message' => 'No Attendance', 'attendanceRecords' => []]);
        }

        $results = [];

        foreach ($userWithAttendance->rfids as $rfid) {
            foreach ($rfid->enrollments as $enrollment) {
                $module = $enrollment->module;

                foreach ($enrollment->records as $record) {
                    $date = \Carbon\Carbon::parse($record->created_at)->format('Y-m-d');
                    $event_name = $record->event ? $record->event->event_name : 'Unknown';

                    $results[$module->module_code . $date] = [
                        'id' => $record->id,
                        'module_code' => $module->module_code,
                        'created_at' => $date,
                        'event_name' => $event_name,
                    ];
                }
            }
        }

        if (empty($results)) {
            return view('attendance.index', ['message' => 'No attendance records', 'attendanceRecords' => []]);
        }

        return view('attendance.index', ['attendanceRecords' => array_values($results), 'message' => null]);
    }
    // public function showAttendance()
    // {
    //     $attendanceRecords = DB::table('attendances')
    //         ->join('events', 'attendances.event_id', '=', 'events.id')
    //         ->select('attendances.module_code', 'events.event_name', DB::raw('DATE(attendances.created_at) as created_at'))
    //         ->distinct()
    //         ->groupBy('attendances.module_code', 'events.event_name', 'created_at')
    //         ->get();

    //     return view('attendance.my-attendance', [
    //         'attendanceRecords' => $attendanceRecords,
    //         'message' => null,
    //     ]);
    // }

    // public function index()
    // {
    //     $user = Auth::user();

    //     $userWithAttendance = User::with(['rfids.enrollments.module', 'rfids.enrollments.records.event'])
    //         ->where('id', $user->id)
    //         ->first();

    //     if (!$userWithAttendance) {
    //         return view('attendance.index', ['message' => 'No Attendance', 'attendanceRecords' => []]);
    //     }

    //     $results = [];

    //     foreach ($userWithAttendance->rfids as $rfid) {
    //         foreach ($rfid->enrollments as $enrollment) {
    //             $module = $enrollment->module;

    //             foreach ($enrollment->records as $record) {
    //                 $date = \Carbon\Carbon::parse($record->created_at)->format('Y-m-d');
    //                 $event_name = $record->event ? $record->event->event_name : 'Unknown';

    //                 $results[$module->module_code . $date] = [
    //                     'id' => $record->id,
    //                     'module_code' => $module->module_code,
    //                     'created_at' => $date,
    //                     'event_name' => $event_name,
    //                 ];
    //             }
    //         }
    //     }

    //     if (empty($results)) {
    //         return view('attendance.index', ['message' => 'No attendance records', 'attendanceRecords' => []]);
    //     }

    //     return view('attendance.index', ['attendanceRecords' => array_values($results), 'message' => null]);
    // }

    // public function showAttendance()
    // {
    //     $attendanceRecords = DB::table('attendances')
    //         ->join('events', 'attendances.event_id', '=', 'events.id')
    //         ->select('module_code', DB::raw('DATE(created_at) as created_at'))
    //         ->distinct()
    //         ->groupBy('module_code',  'events.event_name','created_at')
    //         ->get();

    //     return view('attendance.my-attendance', [
    //         'attendanceRecords' => $attendanceRecords,
    //         'message' => null,
    //     ]);
    // }

    // public function index()
    // {
    //     $user = Auth::user();

    //     $userWithAttendance = User::with(['rfids.enrollments.module', 'rfids.enrollments.records.event'])
    //         ->where('id', $user->id)
    //         ->first();

    //     if (!$userWithAttendance) {
    //         return view('attendance.index', ['message' => 'No Attendance', 'attendanceRecords' => []]);
    //     }

    //     $results = [];

    //     foreach ($userWithAttendance->rfids as $rfid) {
    //         foreach ($rfid->enrollments as $enrollment) {
    //             $module = $enrollment->module;

    //             foreach ($enrollment->records as $record) {
    //                 $date = \Carbon\Carbon::parse($record->created_at)->format('Y-m-d');
    //                 // $event_name = $record->event->event_name;

    //                 $results[$module->module_code . $date] = [
    //                     'id' => $record->id,
    //                     'module_code' => $module->module_code,
    //                     'created_at' => $date,
    //                     'event_name'=> $record-> event_name
    //                 ];
    //             }
    //         }
    //     }

    //     if (empty($results)) {
    //         return view('attendance.index', ['message' => 'No attendance records', 'attendanceRecords' => []]);
    //     }

    //     return view('attendance.index', ['attendanceRecords' => array_values($results), 'message' => null]);
    // }


    // public function showAttendance()
    // {
    //     $attendanceRecords = DB::table('attendances')
    //         ->join('events', 'attendances.event_id', '=', 'events.id')
    //         ->select('attendances.module_code', 'events.event_name', DB::raw('DATE(attendances.created_at) as created_at'))
    //         ->distinct()
    //         ->groupBy('attendances.module_code', 'events.event_name', 'created_at')
    //         ->get();

    //     return view('attendance.my-attendance', [
    //         'attendanceRecords' => $attendanceRecords,
    //         'message' => null,
    //     ]);
    // }

    // public function index()
    // {
    //     $user = Auth::user();

    //     $userWithAttendance = User::with(['rfids.enrollments.module', 'rfids.enrollments.records.event'])
    //         ->where('id', $user->id)
    //         ->first();

    //     if (!$userWithAttendance) {
    //         return view('attendance.index', ['message' => 'No Attendance', 'attendanceRecords' => []]);
    //     }

    //     $results = [];

    //     foreach ($userWithAttendance->rfids as $rfid) {
    //         foreach ($rfid->enrollments as $enrollment) {
    //             $module = $enrollment->module;

    //             foreach ($enrollment->records as $record) {
    //                 $date = \Carbon\Carbon::parse($record->created_at)->format('Y-m-d');
    //                 $event_name = $record->event->event_name;

    //                 $results[$module->module_code . $date] = [
    //                     'id' => $record->id,
    //                     'module_code' => $module->module_code,
    //                     'created_at' => $date,
    //                     'event_name' => $event_name,
    //                 ];
    //             }
    //         }
    //     }

    //     if (empty($results)) {
    //         return view('attendance.index', ['message' => 'No attendance records', 'attendanceRecords' => []]);
    //     }

    //     return view('attendance.index', ['attendanceRecords' => array_values($results), 'message' => null]);
    // }
}





    // public function show()
    // {
    //     $this->middleware('role:lecturer|hod');
        
    // }

    // public function myAttendances()
    // {
    //     $this->middleware('role:student');
        
    // }
    // public function index()
    // {
    //     $user = Auth::user();
    //     $rfid = $user->rfids->first();
    //     $enrollments = Enrollment::where('uid_id', $rfid->id)->with('module', 'records.ratiba')->get();

    //     return view('attendance.index', compact('enrollments'));
    // }

