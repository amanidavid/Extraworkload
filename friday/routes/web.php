<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\AttandenceComponent;
use Illuminate\Http\Request;
// use App\Livewire\timeTables;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    // return response()->json(['message' => 'Hello from Laravel!is complex']);
    return view('welcome');
});
Route::get('/testing', function (Request $request) {

    // Query to retrieve the schedule details
    $scheduleDetails = "hello";

    return Response::json([
       $scheduleDetails
    ]);
});


Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {


    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

 Route::get('/livewire/attandence-component', [AttandenceComponent::class, 'render'])->name('form.show');

//  Route::get('/attendance/pdf-viewer', function () {
//     return view('attendance.pdf-viewer');
// });
 Route::post('/attandance/generate', [AttandenceComponent::class, 'generatePDF'])->name('attendance.generate');
 
 Route::get('/attendance/pdf-viewer', function (Request $request) {
    $pdfPath = $request->query('pdfPath');
    return view('attandence.pdf-viewer', compact('pdfPath'));
})->name('attandence.pdf-viewer');

