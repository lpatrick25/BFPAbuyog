<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ApplicationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'application_number' => $this->application_number,
            'application_date' => $this->application_date,
            'fsic_type' => $this->fsic_type,
            'establishment' => [
                'id' => $this->establishment->id ?? null,
                'name' => $this->establishment->name ?? 'N/A',
            ],
            'latest_status' => $this->applicationStatuses
                ? $this->applicationStatuses->sortByDesc('updated_at')->first()->status ?? 'No Status'
                : 'No Status',
            'application_statuses' => $this->applicationStatuses->map(function ($status) {
                return [
                    'status' => $status->status,
                    'remarks' => $status->remarks,
                    'created_at' => $status->created_at,
                    'updated_at' => $status->updated_at,
                ];
            }),
        ];
    }
}
