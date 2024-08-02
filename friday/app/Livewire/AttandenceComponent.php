<?php
namespace App\Livewire;

use Livewire\Component;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Module;
use App\Models\Event;
use PDF;

class AttandenceComponent extends Component

{
    public function render()
    {
        
        $user = auth()->user();
        // $modules = Module::all();
        $modules=DB::table('lecture_ins_tables')
        ->join('modules','lecture_ins_tables.module_id','modules.id')
        ->join('lecturerlevels','lecture_ins_tables.lecturerlevels_id','lecturerlevels.id')
        ->join('users','lecturerlevels.user_id','users.id')
        ->where('users.id',$user['id'])
        ->select("modules.module_code")
        ->get();
        $events = Event::all();
        return view('livewire.attandence-component', compact('modules', 'events'));
    }

    public function generatePDF(Request $request)
    {
        try {
            $module_code = $request->input('module_code');
            $month = $request->input('month');
            $event_name = $request->input('event_name');

         $user = auth()->user();
            $department = DB::table('user_departments_tables')
                ->join('departments', 'departments.id', '=', 'user_departments_tables.department_id')
                ->join('users', 'users.id', '=', 'user_departments_tables.user_id')
                ->where('users.id', $user->id)
                ->select('departments.dname as dept_name')
                ->first();

            $module = Module::where('module_code', $module_code)->first();

            $results = DB::table('records')
                ->join('enrollments', 'records.enrollment_id', '=', 'enrollments.id')
                ->join('rfids', 'enrollments.uid_id', '=', 'rfids.id')
                ->join('users AS students', 'rfids.user_id', '=', 'students.id')
                ->join('ratibas', 'records.ratiba_id', '=', 'ratibas.id')
                ->join('modules', 'ratibas.module_id', '=', 'modules.id')
                ->join('events', 'ratibas.event_id', '=', 'events.id')
                ->where('modules.module_code', $module_code)
                ->whereMonth('records.created_at', $month)
                ->where('events.event_name', $event_name)
                ->select(
                    'students.name AS student_name',
                    'rfids.reg_number as registration_number',
                    'records.created_at'
                )
                ->get();

            if ($results->isEmpty()) {
                // Render the view with a message saying no attendance records found
                $pdf = PDF::loadView('attandence.pdf', [
                    'results' => [],
                    'month' => $month,
                    'module_code' => $module_code,
                    'modulename' => $module ? $module->modulename : '',
                    'event_name' => $event_name,
                    'user' => $user,
                    'department' => $department,
                    'dates' => [],
                    'attendance' => [],
                ]);

                $pdfPath = 'pdf/attendance.pdf';
                $fullPdfPath = public_path($pdfPath);

                if (!is_dir(dirname($fullPdfPath))) {
                    mkdir(dirname($fullPdfPath), 0755, true);
                }

                $pdf->save($fullPdfPath);

                return redirect()->route('attandence.pdf-viewer', ['pdfPath' => $pdfPath]);
            }

            // Ensure unique dates
            $dates = $results->pluck('created_at')->map(function ($date) {
                return \Carbon\Carbon::parse($date)->format('Y-m-d');
            })->unique()->sort()->values();

            // Group by student and collect their unique attendance dates
            $attendance = $results->groupBy('registration_number')->map(function ($records) {
                return [
                    'student_name' => $records->first()->student_name,
                    'registration_number' => $records->first()->registration_number,
                    'attendance_dates' => $records->pluck('created_at')->map(function ($date) {
                        return \Carbon\Carbon::parse($date)->format('Y-m-d');
                    })->unique()->sort()->values(),
                ];
            })->values();

            $pdf = PDF::loadView('attandence.pdf', [
                'results' => $results,
                'month' => $month,
                'module_code' => $module_code,
                'modulename' => $module ? $module->modulename : '',
                'event_name' => $event_name,
                'user' => $user,
                'department' => $department,
                'dates' => $dates,
                'attendance' => $attendance,
            ]);

            $pdfPath = 'pdf/attendance.pdf';
            $fullPdfPath = public_path($pdfPath);

            if (!is_dir(dirname($fullPdfPath))) {
                mkdir(dirname($fullPdfPath), 0755, true);
            }

            $pdf->save($fullPdfPath);

            return redirect()->route('attandence.pdf-viewer', ['pdfPath' => $pdfPath]);

        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
