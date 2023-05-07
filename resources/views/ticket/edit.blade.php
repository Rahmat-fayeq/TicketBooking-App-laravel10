<x-app-layout>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100 dark:bg-gray-900">
        <h3 class="dark:text-gray-300">Edit support ticket</h3>
        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg">
            <form method="POST" action="{{ route('ticket.update', $ticket->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PATCH')
                <div>
                    <x-input-label for="title" :value="__('Title')" />
                    <x-text-input id="title" name="title" type="text" value="{{ $ticket->title }}"
                        class="mt-1 block w-full" />
                    <x-input-error :messages="$errors->get('title')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="description" :value="__('Description')" />
                    <x-textarea name="description" id="description" rows=5 value="{{ $ticket->description }}" />
                    <x-input-error :messages="$errors->get('description')" class="mt-2" />
                </div>
                <div>
                    @if ($ticket->attachment)
                        <a href="{{ '/storage/' . $ticket->attachment }}" target="_blank" class="dark:text-white">See
                            File</a>
                    @endif
                    <x-input-label for="attachment" :value="__('Attachment (if any)')" />
                    <x-file-input id="attachment" name="attachment" />
                    <x-input-error :messages="$errors->get('attachment')" class="mt-2" />
                </div>
                <div class="flex justify-end">
                    <x-primary-button class="mt-4">
                        {{ __('Save') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
