<?php

namespace App\Observers;

use App\Models\User;
use App\Notifications\UserCreatedNotification;
use Illuminate\Support\Facades\Log;

class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        try {
            // Send Notification to the user
            $user->notify(new UserCreatedNotification($user));

            // Log success
            Log::info("User notification sent successfully", ['user_id' => $user->id]);
        } catch (\Exception $e) {
            Log::error("Failed to send user notification", [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        //
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        //
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        //
    }
}
