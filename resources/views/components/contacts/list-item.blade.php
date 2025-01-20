<li class="flex justify-between gap-x-6 py-5 rounded-lg bg-white p-4 border border-gray-200 w-full items-center">
    <div class="flex min-w-0 gap-x-4">
      <div class="min-w-0 flex-auto">
        <p class="text-sm/6 font-semibold text-gray-900">{{ $contact->full_name }}</p>
        <p class="mt-1 truncate text-xs/5 text-gray-500">{{ $contact->company_name }} &ndash; {{ $contact->position }}</p>
        <p class="mt-1 truncate text-xs/5 text-gray-500">{{ $contact->email }}</p>
        <p class="mt-1 truncate text-xs/5 text-gray-500">{{ $contact->phoneNumbers->first()?->number }}</p>
        @if($contact->phoneNumbers->count() > 1)
            <p class="mt-1 truncate text-xs/5 text-gray-500">+{{ $contact->phoneNumbers->count() - 1 }} numbers</p>
        @endif
      </div>
    </div>
    <div class="hidden shrink-0 sm:flex sm:flex-col sm:items-end">

    <div class="inline-flex rounded-md shadow-sm" role="group">
        <a href="{{ route('contacts.show', ['contact' => $contact]) }}">
            <button type="button" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-900 bg-transparent border-t border-b border-l border-r border-gray-200 rounded-s-lg hover:bg-blue-500 hover:text-white focus:z-10 focus:ring-2 focus:ring-gray-500 focus:bg-gray-200 focus:text-white dark:border-white dark:text-white dark:hover:text-white dark:hover:bg-gray-700 dark:focus:bg-gray-700">
                View
            </button>
        </a>
        <a href="{{ route('contacts.edit', ['contact' => $contact]) }}">
            <button type="button" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-900 bg-transparent border-t border-b border-gray-200 hover:bg-blue-500 hover:text-white focus:z-10 focus:ring-2 focus:ring-gray-500 focus:bg-gray-200 focus:text-white dark:border-white dark:text-white dark:hover:text-white dark:hover:bg-gray-700 dark:focus:bg-gray-700">
                Edit
            </button>
        </a>
        <form action="{{ route('contacts.destroy', ['contact' => $contact]) }}" method="POST" class="inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-900 bg-transparent border border-gray-200 rounded-e-lg hover:bg-red-500 hover:text-white focus:z-10 focus:ring-2 focus:ring-gray-500 focus:bg-gray-200 focus:text-white dark:border-white dark:text-white dark:hover:text-white dark:hover:bg-gray-700 dark:focus:bg-gray-700">
                Delete
            </button>
        </form>
        </div>
    </div>
</li>