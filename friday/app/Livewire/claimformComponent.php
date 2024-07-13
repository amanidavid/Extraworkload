<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Module;
use  PDF;
use Carbon\Carbon;

class claimformComponent extends Component
{
    public function render()
    {
        return view('livewire.claimform-component');
    }

 

    public function getClaimForm(Request $request)
    {
        $user_id = auth()->user()->id;  // Replace with your authentication method
        $month = $request->input('month');

        //call stored procedure
        // $extraworkload = DB::select('CALL CalculateLectureTutorialHours(?, ?)', [$user_id, $month]);
        $extraworkload = DB::select('CALL GetLecturerReport(?, ?)', [$user_id, $month]);
        
        $pdf = PDF::loadView('claim.pdf', compact('extraworkload', 'user_id', 'month'));

        $pdfPath = 'pdf/claim.pdf';
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
     return redirect()->route('claim.pdf-viewer', ['pdfPath' => $pdfPath]);
}
}