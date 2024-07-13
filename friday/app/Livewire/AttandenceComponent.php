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

        $module_code = $request->input('module_code');
        $month = $request->input('month');
        $event_name = $request->input('event_name');


    // Convert month string to integer
    // $monthNumber = date_parse($month)['month'];

    // Call stored procedure to fetch attendance data
    $results = DB::select('CALL GetAttendance(?, ?, ?)', [ $event_name, $month,$module_code]);

    // Load view and generate PDF
    $pdf = PDF::loadView('attandence.pdf', compact('results', 'month', 'module_code', 'event_name'));

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
   
 
}
