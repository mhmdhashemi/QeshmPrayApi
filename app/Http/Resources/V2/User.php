<?php

namespace App\Http\Resources\V2;

use Illuminate\Http\Resources\Json\JsonResource;

class User extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'        => $this->id,
            'name'      => $this->name,
            'phone'     => $this->phone,
            'fajr'      => $this->fajr_id,
            'zohr'      => $this->zohr_id,
            'asr'       => $this->asr_id,
            'maghrib'   => $this->maghrib_id,
            'isha'      => $this->isha_id,
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
