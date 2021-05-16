<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDpsData extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'encounterUnixEpoch' => 'required|integer',
            'areaId' => 'integer|required',
            'bossId' => 'integer|required',
            'meterName' => 'string|required',
            'meter_version' => 'string|nullable',
        ];
    }

    public function validationData()
    {
        return (array) json_decode($this->getContent());
    }
}
