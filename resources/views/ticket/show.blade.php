<x-app-layout>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100 dark:bg-gray-900">
        <h3 class="dark:text-gray-300">{{ $ticket->title }}</h3>
        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg">
            <div class="dark:text-gray-300 flex justify-between items-center p-4">
                <div>
                    <p>{{ $ticket->description }}</p>
                    <p class="text-sm">{{ $ticket->created_at->diffForHumans() }}</p>
                </div>
                @if ($ticket->attachment)
                    <a href="{{ '/storage/' . $ticket->attachment }}" target="_blank">File</a>
                @endif
            </div>
            <div class="flex items-center justify-between">
                <div class="flex mt-3">
                    <a href="{{ route('ticket.edit', $ticket->id) }}" class="mr-1">
                        <x-secondary-button>Edit</x-secondary-button>
                    </a>

                    <form action="{{ route('ticket.destroy', $ticket->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <x-danger-button onclick="return confirm('Are you sure to delete permenantly?')">Delete
                        </x-danger-button>
                    </form>
                </div>
                @if (auth()->user()->isAdmin)
                    <div class="flex mt-4 ">
                        <form action="{{ route('ticket.update', $ticket->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="resolved" />
                            <x-primary-button class="mr-1">Resolve</x-primary-button>
                        </form>
                        <form action="{{ route('ticket.update', $ticket->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="rejected" />
                            <x-danger-button class="mr-1">Reject</x-danger-button>
                        </form>
                    </div>
                @else
                    <p class="dark:text-white">Status: {{ $ticket->status }}</p>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
