<?php

namespace App\Service;

class TeraData
{
    /** @var array|string[] */
    public $zoneMap = [];
    /** @var array|string[][] */
    public $monsterMap = [];

    /**
     * TeraData constructor.
     */
    public function __construct()
    {
        $data = simplexml_load_file(base_path('teradata/'.config('tera.monstersDb')));

        foreach ($data->Zone as $zone) {
            $zoneId = (int) $zone->attributes()->id;
            $this->zoneMap[$zoneId] = (string) $zone->attributes()->name;
            foreach ($zone->Monster as $monster) {
                $this->monsterMap[$zoneId][(int) $monster->attributes()->id] = (string) $monster->attributes()->name;
            }
        }
    }
}
