<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EstablishmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'nature_of_business' => $this->nature_of_business,
            'type_of_occupancy' => $this->type_of_occupancy,
            'type_of_building' => $this->type_of_building,
            'total_building_area' => $this->total_building_area,
            'location_latitude' => $this->location_latitude,
            'location_longitude' => $this->location_longitude,
            'application_id' => $this->latestApplication?->id, // Fix: Null-safe
            'latest_status' => $this->latestApplicationStatus?->status ?? 'No Status', // Fix: Null-safe
            'has_application' => $this->hasApplication(),
            'application_statuses' => $this->latestApplication?->applicationStatuses->map(function ($status) {
                return [
                    'status' => $status->status,
                    'remarks' => $status->remarks,
                    'created_at' => $status->created_at,
                    'updated_at' => $status->updated_at,
                ];
            }) ?? [], // Fix: Avoid errors if no statuses
        ];
    }
}
