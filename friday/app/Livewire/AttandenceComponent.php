<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Module;
// use Carbon\Carbon;
use  PDF;
// use Dompdf\Options;

class AttandenceComponent extends Component
{
    public function render()
    {
        $module = Module::all();
        return view('livewire.attandence-component',compact('module'));
    }
    
    public function generatePDF(Request $request){

        $module_code = $request->input('module_code');
        $month = $request->input('month');

        // $pdf = new Dompdf();
        // $options = new Options();
        // $options->set('isHtml5ParserEnabled', true);
        // $pdf->setOptions($options);

         // Convert month string to integer
    // $monthNumber = date_parse($month)['month'];

        $results = DB::select('CALL getRecords(?, ?)', [$month, $module_code]);

        $pdf = PDF::loadView('attandence.pdf', compact('results', 'month', 'module_code'));

        // return $pdf->stream('attandence.pdf-viewer');
            // Save the PDF to the public path
  // Define the PDF path
  $pdfPath = 'pdf/attendance.pdf';
  $fullPdfPath = public_path($pdfPath);

  // Ensure the directory exists
  $directory = dirname($fullPdfPath);
  if (!is_dir($directory)) {
      mkdir($directory, 0755, true);
  }

  // Save the PDF to the public path
  $pdf->save($fullPdfPath);

  // Redirect to the PDF viewer with the path to the PDF
  return redirect()->route('attandence.pdf-viewer', ['pdfPath' => $pdfPath]);
    }
   
 
}
