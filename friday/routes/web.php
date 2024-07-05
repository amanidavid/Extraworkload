<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\AttandenceComponent;
use App\Livewire\timeTables;

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

Route::post('/attandencerecords', [AttandenceComponent::class, 'getEspData']);
Route::post('/time-schedule', [timeTables::class, 'create']);
Route::post('/time-schedule', [timeTables::class, 'store']);

