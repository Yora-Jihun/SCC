<div class="max-w-2xl mx-auto mt-10 space-y-4">
    <h1 class="text-2xl font-bold">Welcome back, {{ auth()->user()->name }}!</h1>
    <p class="text-gray-600">Your feed will live here starting in Chapter 5.</p>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="text-sm text-red-600">Log out</button>
    </form>
</div>