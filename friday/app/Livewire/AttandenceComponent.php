<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Module;
use App\Models\Event;
use  PDF;


class AttandenceComponent extends Component
{
    public function render()
    {
        $module = Module::all();
        $events  = Event::all();
        return view('livewire.attandence-component',compact('module','events'));
    }
    
    public function generatePDF(Request $request){

      try {
        //code...
        $module_code = $request->input('module_code');
        $month = $request->input('month');
        $event_name = $request->input('event_name');

        // Call stored procedure to fetch attendance data
        // $results = DB::select('CALL GetAttendance(?, ?, ?)', [ $event_name, $month,$module_code]);
        $user = auth()->user();
        $depertment  = DB::table('user_departments_tables')->JOIN('departments','departments.id','user_departments_tables.department_id')
            ->JOIN('users','users.id','user_departments_tables.user_id')
            ->where('users.id',$user['id'])
            ->select('departments.dname as dept_name')
        ->first();

        $results=DB::table('records')
            ->JOIN ('enrollments', 'records.enrollment_id' ,'enrollments.id')
            ->JOIN ('rfids', 'enrollments.uid_id', 'rfids.id')
            ->JOIN ('users As students', 'rfids.user_id', 'students.id')
            ->JOIN ('ratibas', 'records.ratiba_id','ratibas.id')
            ->JOIN ('modules', 'ratibas.module_id' , 'modules.id')
            ->JOIN ('events', 'ratibas.event_id' , 'events.id')
            ->WHERE('modules.module_code', $module_code) 
            ->WhereMonth('records.created_at',$month)
            ->WHERE('events.event_name',$event_name)
           
            ->SELECT(
                'modules.modulename',
                'modules.module_code',
                'records.created_at',
                'records.id',
                'records.enrollment_id',
                'students.name AS student_name',
                'rfids.reg_number as reg_number',
                'rfids.uid as card_number',
                'events.event_name',
                )
            ->get();
            // dd($results);
            // Load view and generate PDF
            $pdf = PDF::loadView('attandence.pdf', compact('results', 'month', 'module_code', 'event_name', 'user','depertment'));

            // return $pdf->stream('attandence.pdf-viewer');
                // Save the PDF to the public path
            // Define the PDF path
            $pdfPath = 'pdf/attendance.pdf';
            $fullPdfPath = public_path($pdfPath);

            // Ensure the directory exists
            $directory = dirname($fullPdfPath);
            //IF not exist create directory,755 permission for users,true means recursive creation of directory
            if (!is_dir($directory)) {
                mkdir($directory, 0755, true);
                }

            // Save the PDF to the public path
            $pdf->save($fullPdfPath);

            // Redirect to the PDF viewer with the path to the PDF
            return redirect()->route('attandence.pdf-viewer', ['pdfPath' => $pdfPath]);

        } 
        catch (\Throwable $th) {
            throw $th;
        } 
    }
   
 
}
