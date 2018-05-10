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
    // Can we improve performance / memory management by returning a Generator with stats from getRawStatsByParams? Problem is, how to build a generator from inside the chunk lambdas.

    $byBoss = $service->getByBossSince($statsSince, $stats, $params);
    $recentEvents = [];
    foreach ($byBoss as $key => $boss) {
        foreach (array_slice($boss, 0, 5) as $i => $member) { // TODO: Move to conf/const
            if ($member->stat->isRecent()) {
                $member->rank = $i + 1;
                $recentEvents[] = $member;
            }
        }
    }

    return view('index', [
        'encounters' => null !== $stats ? $stats->slice(0, 50) : $service->getLatest(),
        'byBoss' => $byBoss,
        'statsSince' => $statsSince,
        'recentEvents' => $recentEvents,
    ]);
});

Route::get('/encounter/{stat}', function (Stat $stat) {
    return view('encounter', ['stat' => $stat]);
})->name('statDetail');

Route::get('/shared/servertime', function () {
    return response()->json(['serverTime' => time()]);
});
