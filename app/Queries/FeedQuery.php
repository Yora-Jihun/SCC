<?php
// app/Queries/FeedQuery.php

namespace App\Queries;

use App\Enums\PostVisibility;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

class FeedQuery
{
    public function forViewer(User $viewer, int $perPage = 10): LengthAwarePaginator
    {
        return $this->baseQuery($viewer)
            ->latest()
            ->paginate($perPage);
    }

   // app/Queries/FeedQuery.php — replace the existing baseQuery() method

    protected function baseQuery(User $viewer): Builder
    {
        return \App\Models\Post::query()
            ->with(['author', 'originalPost.author'])
            ->withCount('likes')
            ->withExists(['likes as liked_by_viewer' => function (Builder $query) use ($viewer) {
                $query->where('user_id', $viewer->id);
            }])
            ->where(function (Builder $query) use ($viewer) {
                $query->where('visibility', PostVisibility::Public)
                    ->orWhere('user_id', $viewer->id);
            });
    }
}