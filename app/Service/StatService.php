<?php

namespace App\Service;

use App\Stat;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class StatService
{
    public function parseParams(Request $request)
    {
        $params = [];

        if ($request->has('guild')) {
            $params['guild'] = explode(',', $request->get('guild'));
        }
        if ($request->has('name')) {
            $params['name'] = explode(',', $request->get('name'));
        }

        return $params;
    }

    /**
     * @param array              $params
     * @param \DateTimeInterface $since
     *
     * @return Collection
     */
    public function getRawStatsByParams(array $params, \DateTimeInterface $since)
    {
        $stats = Stat::query()
            ->where('encounter_unix', '>=', $since->getTimestamp())
            ->latest()
            ->get();

        return $stats->filter(function (Stat $stat) use ($params) {
            $members = $stat->data->members;

            if (isset($params['guild']) && empty(array_intersect($params['guild'], array_pluck($members, 'guild')))) {
                return false;
            }

            if (isset($params['name']) && empty(array_intersect(
                $params['name'],
                array_pluck($members, 'playerName')
            ))) {
                return false;
            }

            return true;
        });
    }

    public function getLatest()
    {
        return Stat::getLatestPaginator();
    }

    public function getByBossSince(\DateTimeInterface $since, Collection $stats = null, array $params = [])
    {
        if (null === $stats) {
            $stats = Stat::query()
                ->where('encounter_unix', '>=', $since->getTimestamp())
                ->get();
        }

        $byBoss = [];

        $stats->each(function (Stat $stat) use (&$byBoss, $params) {
            $key = $stat->area_id.'_'.$stat->boss_id;
            foreach ($stat->data->members as $member) {
                if (isset($params['guild']) && !\in_array($member->guild ?? null, $params['guild'], true)) {
                    continue;
                }

                if (isset($params['name']) && !\in_array($member->playerName, $params['name'], true)) {
                    continue;
                }

                $member->stat = $stat;
                $byBoss[$key][] = $member;
            }
        });

        foreach ($byBoss as $key => $boss) {
            usort($boss, function ($a, $b) {
                return $b->playerDps - $a->playerDps;
            });
            $byBoss[$key] = collect($boss)->unique('playerName')->toArray();
        }

        return $byBoss;
    }
}
