<div>
    <div class="max-w-2xl mx-auto mt-10 space-y-4">
        <h1 class="text-2xl font-bold">Welcome back, {{ auth()->user()->name }}!</h1>
        <p class="text-gray-600">Your feed will live here starting in Chapter 5.</p>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="text-sm text-red-600">Log out</button>
        </form>
    </div>

    <div class="space-y-4 py-6">
        <livewire:posts.create-post />

        @forelse ($posts as $post)
            <livewire:posts.post-card :post="$post" :key="'post-'.$post->id" />
        @empty
            <p class="text-center text-gray-500">No posts yet — be the first to share something!</p>
        @endforelse
 
        <div class="max-w-xl mx-auto">
            {{ $posts->links() }}
        </div>
    </div>
</div>