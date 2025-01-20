@extends('layouts.app')

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif


@section('content')
    <div class="flex flex-col">
        <div class="mb-3 flex flex-row justify-between">
            <div class="col-10">
                <h1 class="text-3xl font-bold mb-3">Search Results</h1>
            </div>
        </div>

        <div>
            <form action="{{ route('search.index') }}" method="GET">
                <div class="flex flex-row gap-2 w-full justify-end items-end">
                    <x-form.input 
                        label="Search" 
                        name="query" 
                        type="text" 
                        min="3"
                        value="{{ old('query', e($query ?? '')) }}"
                    />
                    <button
                        type="submit" 
                        class="
                            rounded-md
                            bg-gray-600
                            px-3
                            py-2
                            text-sm
                            font-semibold
                            text-white
                            shadow-sm
                            hover:bg-indigo-500
                            focus-visible:outline
                            focus-visible:outline-2
                            focus-visible:outline-offset-2
                            focus-visible:outline-indigo-600"
                        >
                        Search
                    </button>
                </div>
            </form>
        </div>

        <div class="row flex flex-col gap-4">
            @if(isset($results))
                @if($results->isEmpty() && isset($query))
                    <p>No results found for "{{ $query }}"</p>
                @else
                    <p>Found {{ $results->count() }} results for "{{ $query }}"</p>
                    <div id="results">
                        @foreach($results as $result)
                            @include('components.contacts.list-item', ['contact' => $result])
                        @endforeach
                    </div>
                @endif
            @endif
        </div>

        @if(isset($results))
            <div class="mt-4">
                {{ $results->withQueryString()->links() }}
            </div>
        @endif
    </div>
@endsection

<script>
document.addEventListener('DOMContentLoaded', function() {
    const query = "{{ e($query ?? '') }}"
    const results = document.getElementById('results')

    if (query && results) {
        // Split query into individual terms
        const terms = query.trim().split(/\s+/).filter(term => term.length > 0);

        // Create regex pattern that matches any of the terms
        const pattern = new RegExp(`(${terms.map(term => term.replace(/[.*+?^${}()|[\]\\]/g, '\\$&')).join('|')})`, 'gi');

        // Function to highlight text in a node
        function highlightText(node) {
            if (node.nodeType === 3) { // Text node
                const matches = node.nodeValue.match(pattern);
                if (matches) {
                    const span = document.createElement('span');
                    span.innerHTML = node.nodeValue.replace(pattern, '<mark>$1</mark>');
                    node.parentNode.replaceChild(span, node);
                }
            } else if (node.nodeType === 1 && // Element node
                      node.nodeName !== 'SCRIPT' && 
                      node.nodeName !== 'STYLE' && 
                      node.nodeName !== 'MARK') {
                Array.from(node.childNodes).forEach(highlightText);
            }
        }

        // Start highlighting from the results container
        const resultsContainer = document.getElementById('results');
        if (resultsContainer) {
            highlightText(resultsContainer);
        }
    }
});
</script>