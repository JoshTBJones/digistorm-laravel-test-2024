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
                <h1 class="text-3xl font-bold mb-3">Contacts</h1>
            </div>
            <div class="col-2">
                <a href="{{ route('contacts.create') }}" class="btn btn-primary">Add Contact</a>
            </div>
        </div>

        <div class="row flex flex-col gap-4">
        @foreach($contacts as $contact)
            @include('components.contacts.list-item', ['contact' => $contact])
        @endforeach

        @if($contacts->isEmpty())
            <p class="text-gray-500">No contacts found</p>
        @endif
        </div>

        <div class="flex flex-row justify-center">
            {{ $contacts->links() }}
        </div>
    </div>
@endsection
