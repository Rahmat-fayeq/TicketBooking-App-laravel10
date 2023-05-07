<x-app-layout>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100 dark:bg-gray-900">
        <h3 class="dark:text-gray-300">All support tickets</h3>
        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg">
            <a href="{{ route('ticket.create') }}" class="dark:text-white flex justify-end">
                <x-secondary-button>Create New ticket</x-secondary-button>
            </a>
            @forelse ($tickets as $ticket)
                <div class="dark:text-gray-300 flex justify-between items-center p-4">
                    <a href="{{ route('ticket.show', $ticket->id) }}">{{ $ticket->title }}</a>
                    <p>{{ $ticket->created_at->diffForHumans() }}</p>
                    @if (auth()->user()->isAdmin)
                        <p>{{ $ticket->status }}</p>
                    @endif
                </div>
            @empty
                <p class="dark:text-red-400">No data avaliable</p>
            @endforelse
        </div>
    </div>
</x-app-layout>
