<?php

use App\Stat;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::any('/submitdps', 'DpsController@takeDpsSubmit');

Route::get('/allowed', 'DpsController@allowedInfo');

Route::get('/stat/{stat}', function (Stat $stat) {
    return $stat;
})->name('getStat');

Route::get('/shinra/servertime', function () {
    return response()->json(['serverTime' => time()]);
});
