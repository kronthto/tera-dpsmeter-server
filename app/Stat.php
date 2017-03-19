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
    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $casts = [
        'encounter_unix' => 'datetime',
        'data' => 'object',
    ];

    /**
     * Sets the encounter time.
     *
     * @param \DateTimeInterface|int $data
     */
    public function setEncounterUnixAttribute($data)
    {
        $this->attributes['encounter_unix'] = $this->asTimestamp($data);
    }
}
