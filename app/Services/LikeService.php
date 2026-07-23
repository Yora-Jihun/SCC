<?php
// app/Services/LikeService.php

namespace App\Services;

use App\Models\Like;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;

class LikeService
{
    /**
     * Toggle a like on/off for the given user. Returns the new state:
     * true if now liked, false if the like was just removed.
     */
    public function toggle(Model $likeable, User $user): bool
    {
        $existing = Like::query()
            ->where('likeable_type', $likeable::class)
            ->where('likeable_id', $likeable->id)
            ->where('user_id', $user->id)
            ->first();

        if ($existing) {
            $existing->delete();
            return false;
        }

        try {
            Like::create([
                'user_id' => $user->id,
                'likeable_id' => $likeable->id,
                'likeable_type' => $likeable::class,
            ]);
        } catch (QueryException $e) {
            // Two rapid clicks (or a network retry) both passed the
            // "does a like exist?" check before either had inserted its
            // row — the unique constraint from the migration catches the
            // second insert. That's fine: the like already exists, so
            // the end state we wanted (liked = true) is already true.
        }

        return true;
    }
}