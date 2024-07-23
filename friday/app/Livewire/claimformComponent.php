<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Module;
use App\Models\Department;
use App\Models\Faculty;
use App\Models\Facultydepartment;
use App\Models\Userdeparment;
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
        $extraworkload = DB::select('CALL GetLecturerStatistics(?, ?)', [$user_id, $month]);
        // $extraworkloadses = Facultydepartment::join('departments','facultys_departments.department_id', '=','departments.id')
        // ->join('facultys','facultys_departments.faculty_id', '=','facultys.id')
        // // ->join('user_departments_table','departments.id', '=','user_departments_table.department_id')
        // ->join('user_departments_tables','departments.id', '=','user_departments_tables.department_id')
        // ->join('users','user_departments_tables.user_id', '=','users.id')
        // ->join('lecturerlevels','users.id', '=','lecturerlevels.user_id')
        // ->join('level','lecturerlevels.level_id', '=','level.id')
        // ->join('intervals','level.interval_id', '=','intervals.id')
        // ->select('users.name AS lecturer',
        //         'facultys.faculty_name AS Faculty',
        //          'departments.dname AS Department',
        //          'level.levels_of_teaching AS Rank',)
        // ->where('users.id',  $user_id)
        // ->get();
        
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