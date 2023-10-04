<x-app-layout>
    <div class="flex min-h-screen flex-col items-center bg-gray-100 pt-6 dark:bg-gray-900 sm:justify-center sm:pt-0">
        <div class="flex w-full justify-between sm:max-w-xl">
            <h1 class="text-lg font-bold text-white">Support Tickets</h1>
            <div class="">
                <a href="{{ route('ticket.create') }}" class="rounded-lg bg-white p-2">Add Ticket</a>
            </div>
        </div>
        <div class="mt-6 w-full overflow-hidden bg-white px-6 py-4 shadow-md dark:bg-gray-800 sm:max-w-xl sm:rounded-lg">
            @forelse ($tickets as $ticket)
                <div class="flex justify-between py-4 text-white">
                    <a href="{{ route('ticket.show', $ticket->id) }}">{{ $ticket->title }}</a>
                    <p>{{ $ticket->created_at->diffForHumans() }}</p>
                </div>
            @empty
                <p class="text-white">you dont have any support ticket</p>
            @endforelse

        </div>
    </div>
</x-app-layout>
