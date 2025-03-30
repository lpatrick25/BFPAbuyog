<?php

namespace App\Http\Resources\Fsic;

use App\Http\Resources\Resource;

class FsicResource extends Resource
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
            'fsic_no' => $this->fsic_no,
            'client' => $this->application->establishment->client->getFullName() ?? 'N/A',
            'application_number' => $this->application->application_number ?? 'N/A',
            'name' => $this->application->establishment->name ?? 'N/A',
            'nature_of_business' => $this->application->establishment->nature_of_business ?? 'N/A',
            'issue_date' => $this->issue_date,
            'expiration_date' => $this->expiration_date,
        ];
    }
}
