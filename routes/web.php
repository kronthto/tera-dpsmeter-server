<?php

use App\Stat;

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

Route::get('/', function (\Illuminate\Http\Request $request) {
    if ($request->has('since')) {
        try {
            $statsSince = new \Carbon\Carbon($request->get('since'));
        } catch (Exception $e) {
            return response('Invalid date format', 400);
        }
    } else {
        $statsSince = \Carbon\Carbon::now()->subWeeks(1)->startOfDay();
    }

    $service = app(\App\Service\StatService::class);
    $params = $service->parseParams($request);
    $stats = null;
    if (!empty($params)) {
        $stats = $service->getRawStatsByParams($params, $statsSince);
    }

    return view('index', [
        'encounters' => null !== $stats ? $stats->slice(0, 50) : $service->getLatest(),
        'byBoss' => $service->getByBossSince($statsSince, $stats, $params),
        'statsSince' => $statsSince,
    ]);
});

Route::get('/encounter/{stat}', function (Stat $stat) {
    dd($stat);
})->name('statDetail');

Route::get('/shared/servertime', function () {
    return response()->json(['serverTime' => time()]);
});
