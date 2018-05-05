<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property \Carbon\Carbon|int $encounter_unix
 * @property int $area_id
 * @property int $boss_id
 * @property \stdClass $data
 * @property string $meter_name
 * @property string|null $meter_version
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 */
class Stat extends Model
{
    protected $perPage = 50;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $casts = [
        'encounter_unix' => 'datetime',
        'data' => 'object',
    ];

    /**
     * Returns the paginator to list latest encounters.
     *
     * @return \Illuminate\Contracts\Pagination\Paginator|static[]
     */
    public static function getLatestPaginator()
    {
        return static::query()
            ->latest()
            ->paginate();
    }

    /**
     * Returns the string representation of this encounters area.
     *
     * @return string
     */
    public function getAreaName()
    {
        return app('tera.data')->getAreaNameById($this->area_id);
    }

    /**
     * Returns the string representation of this encounters boss.
     *
     * @return string
     */
    public function getMonsterName()
    {
        return app('tera.data')->getMonsterNameByAreaAndId($this->area_id, $this->boss_id);
    }

    public function toString()
    {
        return $this->getMonsterName();
    }

    public function getTitle()
    {
        return $this->toString().' Encounter';
    }

    public function getDescription()
    {
        return sprintf('%s Fight @ %s Party DPS. Uploaded on %s.', gmdate('i:s', $this->data->fightDuration), static::damageFormat($this->data->partyDps), $this->encounter_unix->format('F jS'));
    }

    /**
     * Formats a number to a string with k m for thousands.
     *
     * @param int $num
     *
     * @return string
     */
    public static function damageFormat(int $num): string
    {
        $x_number_format = number_format($num);
        $x_array = explode(',', $x_number_format);
        $x_parts = array('k', 'M', 'b');
        $x_count_parts = count($x_array) - 1;
        if ($x_count_parts === 0) {
            return $num;
        }
        $x_display = $x_array[0].((int) $x_array[1][0] !== 0 ? '.'.$x_array[1][0] : '');
        $x_display .= $x_parts[$x_count_parts - 1];

        return $x_display;
    }

    /**
     * Sets the encounter time.
     *
     * @param \DateTimeInterface|int $data
     */
    public function setEncounterUnixAttribute($data)
    {
        $this->attributes['encounter_unix'] = $this->asTimestamp($data);
    }

    /**
     * Find an existing Stat based on unique constraint.
     *
     * @param Stat $stat
     *
     * @return static|null
     */
    public static function findExisting(Stat $stat)
    {
        return static::query()
            ->where('area_id', $stat->area_id)
            ->where('boss_id', $stat->boss_id)
            ->where('encounter_unix', $stat->encounter_unix->getTimestamp())
            ->get()->first();
    }
}
