<?php
// app/Livewire/Newsfeed.php

namespace App\Livewire;

use App\Queries\FeedQuery;
use Livewire\Attributes\On;
use Livewire\Component;

class Newsfeed extends Component
{
    #[On('post-created')]
    #[On('post-deleted')]
    #[On('post-shared')]
    public function refreshFeed(): void
    {
        // Re-rendering is enough — the query in render() below
        // always reflects current data. This listener just makes
        // sure that re-render actually happens after those events.
    }

    public function render(FeedQuery $feedQuery)
    {
        return view('livewire.newsfeed', [
            'posts' => $feedQuery->forViewer(auth()->user()),
        ]);
    }
}