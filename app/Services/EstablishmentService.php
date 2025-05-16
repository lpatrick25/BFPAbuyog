<?php

namespace App\Services;

use App\Models\Establishment;
use Illuminate\Support\Facades\DB;

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
        try {
            DB::beginTransaction();
            $data['client_id'] = optional(auth()->user())->client->id;
            $establishment = Establishment::create($data);

            DB::commit();
            return $establishment;
        } catch (\Exception $e) {
            DB::rollBack();
            return null;
        }
    }

    public function update(array $data, $id)
    {
        try {
            DB::beginTransaction();

            $establishment = Establishment::find($id);
            $establishment->update($data);
            DB::commit();

            return $establishment;
        } catch (\Exception $e) {
            DB::rollBack();
            return null;
        }
    }
}
