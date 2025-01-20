@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <h1 class="text-3xl font-bold mb-3">{{ $contact->full_name }}</h1>
        </div>

        <div>
            {{-- Reusable Input Components --}}
            <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                <x-form.input 
                    label="First Name" 
                    name="first_name" 
                    type="text" 
                    value="{{ $contact->first_name }}" 
                    readonly
                />

                <x-form.input 
                    label="Last Name" 
                    name="last_name" 
                    type="text" 
                    value="{{ $contact->last_name }}" 
                    readonly
                />

                <x-form.input 
                    label="Date of Birth" 
                    name="DOB" 
                    type="date" 
                    value="{{ $contact->DOB }}" 
                    readonly
                />

                <x-form.input 
                    label="Company" 
                    name="company_name" 
                    type="text" 
                    value="{{ $contact->company_name }}" 
                    readonly
                />

                <x-form.input 
                    label="Position" 
                    name="position" 
                    type="text" 
                    value="{{ $contact->position }}" 
                    readonly
                />

                <x-form.input 
                    label="Email" 
                    name="email" 
                    type="email" 
                    value="{{ $contact->email }}" 
                    readonly
                />

                <h3>Phone Numbers</h3>

                <div class="col-span-6 flex justify-end"></div>

                @foreach($contact->phoneNumbers as $index => $phoneNumber)
                    <x-form.input 
                        label="Phone Number {{ $index + 1 }}" 
                        name="number[]" 
                        type="text" 
                        value="{{ $phoneNumber->number }}" 
                        readonly
                    />
                @endforeach
            </div>

            <div class="mt-6 flex items-center justify-end gap-x-6">
                <a href="{{ route('contacts.edit', ['contact' => $contact]) }}" class="text-sm/6 font-semibold text-gray-900">Edit</a>
                <form method="POST" action="{{ route('contacts.destroy', ['contact' => $contact]) }}">
                    @csrf
                    @method('delete')
                    <x-button type="submit" color="danger" class="bg-red-600" label="Delete" />
                </form>
            </div>
        </div>
    </div>
@endsection