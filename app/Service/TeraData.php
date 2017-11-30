<?php

namespace App\Service;

class TeraData
{
    /** @var array|string[] */
    public $zoneMap = [];
    /** @var array|string[][] */
    public $monsterMap = [];

    public function getAreaNameById($id)
    {
        if (!array_key_exists($id, $this->zoneMap)) {
            return null;
        }

        return $this->zoneMap[$id];
    }

    public function getMonsterNameByAreaAndId($areaid, $monsterid)
    {
        if (!array_key_exists($areaid, $this->monsterMap)) {
            return null;
        }

        // Assume that if the map exists, we also have info about all the monsters.
        return $this->monsterMap[$areaid][$monsterid];
    }

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
