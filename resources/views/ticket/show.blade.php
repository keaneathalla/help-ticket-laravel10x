<x-app-layout>
    <div class="flex min-h-screen flex-col items-center bg-gray-100 pt-6 dark:bg-gray-900 sm:justify-center sm:pt-0">
        <h1 class="text-lg font-bold text-white">Support Tickets</h1>
        <div class="mt-6 w-full overflow-hidden bg-white px-6 py-4 shadow-md dark:bg-gray-800 sm:max-w-xl sm:rounded-lg">
            <div class="flex justify-between py-4 text-white">
                <p>{{ $ticket->title }}</p>
                <p>{{ $ticket->description }}</p>
                <p>{{ $ticket->created_at->diffForHumans() }}</p>
                @if ($ticket->attachment)
                    <a href="{{ '/storage/' . $ticket->attachment }}" target="_blank">Attachment</a>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
