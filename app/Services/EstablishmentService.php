<?php

namespace App\Services;

use App\Models\Establishment;

class EstablishmentService
{
    public function getAllEstablishments()
    {
        return Establishment::with('client', 'latestApplication.applicationStatuses');
    }

    public function store(array $data)
    {
        $data['client_id'] = optional(auth()->user())->client->id;
        $establishment = Establishment::create($data);

        return $establishment;
    }
}
