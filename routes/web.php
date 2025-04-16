<?php

use App\Http\Controllers\ServiceController;
use App\Models\ServiceLog;
use App\Notifications\ServiceLogCompletedCustomer;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/send-test-email', function () {
    if(request()->get('key') == 'ab21071986') {
         Notification::route('mail',
                    'alexbirtwell@gmail.com')->notify(new ServiceLogCompletedCustomer(ServiceLog::latest()->first()));
    }

    return 'Test email sent!';

});

Route::get('/service', [ServiceController::class, 'show'])->name('service.show');
Route::post('/service/submit', [ServiceController::class, 'submit'])->name('service.submit');

