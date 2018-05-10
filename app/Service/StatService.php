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
            ->latest();

        $resultStats = new Collection();

        $stats->each(function (Stat $stat) use ($params, &$resultStats) {
            $members = &$stat->data->members;

            if (isset($params['guild']) && empty(array_intersect($params['guild'], array_pluck($members, 'guild')))) {
                return;
            }

            if (isset($params['name']) && empty(array_intersect(
                $params['name'],
                array_pluck($members, 'playerName')
            ))) {
                return;
            }

            $resultStats->push($stat);
        }, 400);

        return $resultStats;
    }

    public function getLatest()
    {
        return Stat::getLatestPaginator();
    }

    public function getByBossSince(
        \DateTimeInterface $since,
        Collection $stats = null,
        array $params = [],
        $byBossThreshold = 20
    ) {
        if (null === $stats) {
            $stats = Stat::query()
                ->where('encounter_unix', '>=', $since->getTimestamp());
        }

        $byBoss = [];

        $stats->each(function (Stat $stat) use (&$byBoss, $params, $byBossThreshold) {
            $key = $stat->area_id.'_'.$stat->boss_id;
            foreach ($stat->data->members as $member) {
                // Performance
                unset($member->buffDetail, $member->buffUptime, $member->skillLog, $member->skillCasts);

                if (isset($params['guild']) && !\in_array($member->guild ?? null, $params['guild'], true)) {
                    continue;
                }

                if (isset($params['name']) && !\in_array($member->playerName, $params['name'], true)) {
                    continue;
                }

                $member->stat = &$stat;
                $byBoss[$key][] = $member;

                // Performance: Eliminate those that are certainly out to save memory - Final sorting/slicing is done later
                if (\count($byBoss[$key]) >= $byBossThreshold * 2) {
                    $byBoss[$key] = $this->sortUniqueLimitByBoss($byBoss[$key], $byBossThreshold);
                }
            }
        });

        foreach ($byBoss as $key => &$boss) {
            $byBoss[$key] = $this->sortUniqueLimitByBoss($boss, $byBossThreshold);
        }

        return $byBoss;
    }

    protected function sortUniqueLimitByBoss(array &$boss, $byBossThreshold)
    {
        usort($boss, function ($a, $b) {
            return $b->playerDps - $a->playerDps;
        });

        return collect($boss)->unique('playerName')->slice(0, $byBossThreshold)->toArray();
    }
}
