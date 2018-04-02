<?php

namespace App\Service;

use League\Csv\Reader;

class TeraData
{
    /** @var array|string[] */
    public $zoneMap = [];
    /** @var array|string[][] */
    public $monsterMap = [];
    /** @var array|array[] */
    public $hotdotMap = [];
    /** @var array|array[] */
    protected $skillMap = [];

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

    public function getHotdotById($id)
    {
        if (!array_key_exists($id, $this->hotdotMap)) {
            return null;
        }

        return $this->hotdotMap[$id];
    }

    public function getSkillById($id)
    {
        if (!array_key_exists($id, $this->skillMap)) {
            return null;
        }

        return $this->skillMap[$id];
    }

    /**
     * TeraData constructor.
     */
    public function __construct()
    {
        $data = \Cache::get('teradata');

        if (!$data) {
            $this->loadData();

            $data = new \stdClass();
            $data->monsterMap = $this->monsterMap;
            $data->zoneMap = $this->zoneMap;
            $data->hotdotMap = $this->hotdotMap;
            $data->skillMap = $this->skillMap;

            \Cache::put('teradata', $data, 180);

        } else {
            $this->zoneMap = $data->zoneMap;
            $this->monsterMap = $data->monsterMap;
            $this->hotdotMap = $data->hotdotMap;
            $this->skillMap = $data->skillMap;
        }
    }

    protected function loadData()
    {
        $data = simplexml_load_file(base_path('teradata/'.config('tera.monstersDb')));

        foreach ($data->Zone as $zone) {
            $zoneId = (int) $zone->attributes()->id;
            $this->zoneMap[$zoneId] = (string) $zone->attributes()->name;
            foreach ($zone->Monster as $monster) {
                $this->monsterMap[$zoneId][(int) $monster->attributes()->id] = (string) $monster->attributes()->name;
            }
        }

        $hotdot = Reader::createFromPath(base_path('teradata/'.config('tera.hotdotDb')));
        $hotdot->setDelimiter("\t");
        foreach ($hotdot as $row) {
            $this->hotdotMap[(int) $row[0]] = $row;
        }

        $skills = Reader::createFromPath(base_path('teradata/'.config('tera.skillsDb')));
        $skills->setDelimiter("\t");
        foreach ($skills as $row) {
            $this->skillMap[(int) $row[0]] = $row;
        }
    }
}
