<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDpsData;
use App\Stat;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class DpsController extends Controller
{
    public function takeDpsSubmit(StoreDpsData $request)
    {
        $data = json_decode($request->getContent());

        $statEntity = new Stat();

        $statEntity->encounter_unix = $data->encounterUnixEpoch;
        $statEntity->area_id = $data->areaId;
        $statEntity->boss_id = $data->bossId;
        $statEntity->meter_name = $data->meterName;
        $statEntity->meter_version = $data->meterVersion;
        $statEntity->data = $data;

        try {
            $statEntity->save();
        } catch (QueryException $queryException) {
            if ($queryException->getCode() == 23000) { // Duplicate key
                $existing = Stat::findExisting($statEntity);

                return response()->json([
                    'message' => 'This encounter has already been saved',
                    'id' => $existing->id,
                ]);
            }
            throw $queryException;
        }

        return response()->json([
            'message' => 'Encounter saved! Thanks!',
            'id' => $statEntity->id,
        ], Response::HTTP_CREATED);
    }

    public function allowedInfo()
    {
        return response()->json(array_map(function ($regionId) {
            return ['AreaId' => $regionId, 'BossIds' => []];
        }, config('tera.allowedRegions')));
    }

    public function overviewPage(Request $request)
    {
        if ($request->has('since')) {
            try {
                $statsSince = new \Carbon\Carbon($request->get('since'));
            } catch (\Exception $e) {
                return response('Invalid date format', 400);
            }
        } else {
            $statsSince = \Carbon\Carbon::now()->subDays(10)->startOfDay();
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
        foreach ($byBoss as $boss) {
            foreach (\array_slice($boss, 0, 5) as $i => $member) { // TODO: Move to conf/const
                if ($member->stat->isRecent()) {
                    $member->rank = $i + 1;
                    $recentEvents[] = $member;
                }
            }
        }

        $byMap = [];
        foreach ($byBoss as $key => $boss) {
            $mapBossSplit = explode('_', $key);
            $byMap[$mapBossSplit[0]][$mapBossSplit[1]] = $boss;
        }

        // Map Sort?
        foreach ($byMap as &$map) {
            ksort($map);
        }

        return view('index', [
            'encounters' => null !== $stats ? $stats->slice(0, 50) : $service->getLatest(),
            'byBoss' => $byBoss,
            'byMap' => $byMap,
            'statsSince' => $statsSince,
            'recentEvents' => $recentEvents,
        ]);
    }
}
