<?php

namespace App\Http\Resources\V2;

use Illuminate\Http\Resources\Json\ResourceCollection;

class MosqueCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => $this->collection->map(function ($item) {
                return [
                    'id'            => $item->id,
                    'name'          => $item->name,
                    'imam'          => $item->imam,
                    'address'       => $item->address,
                    'city_id'       => $item->city->id,
                    'city_name'     => $item->city->name,
                    'responsibles'  => $item->responsible()->count()
                ];
            })
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
