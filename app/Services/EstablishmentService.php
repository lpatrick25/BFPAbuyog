<?php

namespace App\Services;

use App\Models\Establishment;

class EstablishmentService
{
    public function getAllEstablishments()
    {
        $establishment = Establishment::with('client', 'latestApplication.applicationStatuses');
        $client = auth()->user()->client;
        if ($client) {
            $establishment->where('client_id', $client->id);
        }

        return $establishment;
    }

    public function store(array $data)
    {
        $data['client_id'] = optional(auth()->user())->client->id;
        $establishment = Establishment::create($data);

        return $establishment;
    }

    public function update(array $data, $id)
    {
        $establishment = Establishment::find($id);
        $establishment->update($data);

        return $establishment;
    }
}
