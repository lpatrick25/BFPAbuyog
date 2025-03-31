<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class UserService
{
    public function store(array $data, string $role): Model
    {
        $user = User::create([
            'password' => Hash::make($data['password']),
            'email' => $data['email'],
            'email_verified_at' => now(),
            'role' => $role,
            'is_active' => true,
        ]);

        $data['user_id'] = $user->id;

        $modelClass = $this->getModelClass($role);
        $model = $modelClass::create($data);

        return $model;
    }

    public function update(array $data, string $role, int $id)
    {
        $modelClass = $this->getModelClass($role);
        $model = $modelClass::findOrFail($id);

        $user = User::findOrFail($model->user_id);
        if (isset($data['email']) && $data['email'] !== $user->email) {
            $user->update(['email' => $data['email']]);
        }

        if (!empty($data['password'])) {
            $user->update(['password' => Hash::make($data['password'])]);
        }

        $model->update($data);

        return $model;
    }


    public function getAllUser(string $role)
    {
        $modelClass = $this->getModelClass($role);
        $model = $modelClass::with(['user']);

        return $model;
    }

    private function getModelClass(string $role): string
    {
        return match ($role) {
            'Client' => \App\Models\Client::class,
            'Inspector' => \App\Models\Inspector::class,
            'Marshall' => \App\Models\Marshall::class,
            default => throw new \InvalidArgumentException("Invalid role: $role"),
        };
    }
}
