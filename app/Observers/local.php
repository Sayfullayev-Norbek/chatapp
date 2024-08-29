<?php

namespace App\Observers;

use App\Models\Optional;

class local
{
    /**
     * Handle the Optional "created" event.
     */
    public function created(Optional $optional): void
    {
        //
    }

    /**
     * Handle the Optional "updated" event.
     */
    public function updated(Optional $optional): void
    {
        //
    }

    /**
     * Handle the Optional "deleted" event.
     */
    public function deleted(Optional $optional): void
    {
        //
    }

    /**
     * Handle the Optional "restored" event.
     */
    public function restored(Optional $optional): void
    {
        //
    }

    /**
     * Handle the Optional "force deleted" event.
     */
    public function forceDeleted(Optional $optional): void
    {
        //
    }
}
