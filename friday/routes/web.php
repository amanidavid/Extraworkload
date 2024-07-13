<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\AttandenceComponent;
use App\Livewire\claimformComponent;
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
    return view('auth/login');
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
 Route::get('/livewire/attandence-records', [claimformComponent::class, 'render'])->name('form.shows');

 Route::post('/attandance/generate', [AttandenceComponent::class, 'generatePDF'])->name('attendance.generate');
 Route::post('/claim/generate', [claimformComponent::class, 'getClaimForm'])->name('claim.generate');
 
 Route::get('/attendance/pdf-viewer', function (Request $request) {
    $pdfPath = $request->query('pdfPath');
    return view('attandence.pdf-viewer', compact('pdfPath'));
})->name('attandence.pdf-viewer');

Route::get('/claim/pdf-viewer', function (Request $request) {
    $pdfPath = $request->query('pdfPath');
    return view('claim.pdf-viewer', compact('pdfPath'));
})->name('claim.pdf-viewer');

