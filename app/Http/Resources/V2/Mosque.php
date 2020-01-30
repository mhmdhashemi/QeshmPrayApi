<?php

namespace App\Http\Resources\V2;

use Illuminate\Http\Resources\Json\JsonResource;

class Mosque extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'         => $this->id,
            'city'       => $this->city->name,
            'name'       => $this->name,
            'imam'       => $this->imam,
            'address'    => $this->address,
            'favorite'   => $this->favorite_cont,
            'fajr'       => $this->fajr,
            'eq_fajr'    => $this->eq_fajr,
            'zohr'       => $this->zohr,
            'eq_zohr'    => $this->eq_zohr,
            'asr'        => $this->asr,
            'eq_asr'     => $this->eq_asr,
            'maghrib'    => $this->maghrib,
            'eq_maghrib' => $this->eq_maghrib,
            'isha'       => $this->isha,
            'eq_isha'    => $this->eq_isha,
            'update'     => date($this->updated_at)
        ];
    }

    public function with($request)
    {
        return [
            'message'   => '',
            'status'    => 'succeed'
        ];
    }
}
