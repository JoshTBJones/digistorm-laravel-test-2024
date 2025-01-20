<header class="bg-gray-900 text-white">
  <nav class="mx-auto flex max-w-7xl items-center justify-between p-6 lg:px-8" aria-label="Global">
    <div class="flex lg:flex-1">
        <a href="{{ url('/') }}" class="-m-1.5 p-1.5">
            <span class="sr-only">Digistorm test</span>
            <h1 class="text-2xl font-bold">{{ config('app.name', 'Digistorm test') }}</h1>
        </a>
    </div>
    <div class="flex gap-x-12">
      <a href="{{ route('contacts.index') }}" class="text-sm/6 font-semibold">Contacts</a>
      <a href="{{ route('search.index') }}" class="text-sm/6 font-semibold">Search</a>
    </div>
  </nav>
</header>
