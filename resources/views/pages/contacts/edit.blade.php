@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <h1 class="text-3xl font-bold mb-3">Edit Contact</h1>
        </div>

        @if ($errors->any())
            <div class="rounded-md bg-yellow-50 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 5a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 5zm0 9a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-yellow-800">Attention needed</h3>
                        <div class="mt-2 text-sm text-yellow-700">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <form method="POST" action="{{ route('contacts.update', ['contact' => $contact]) }}">
            @csrf
            @method('put')

            {{-- Reusable Input Components --}}
            <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                <x-form.input 
                    label="First Name" 
                    name="first_name" 
                    type="text" 
                    value="{{ $contact->first_name }}" 
                />

                <x-form.input 
                    label="Last Name" 
                    name="last_name" 
                    type="text" 
                    value="{{ $contact->last_name }}" 
                />

                <x-form.input 
                    label="Date of Birth" 
                    name="DOB" 
                    type="date" 
                    value="{{ $contact->DOB }}" 
                />

                <x-form.input 
                    label="Company" 
                    name="company_name" 
                    type="text" 
                    value="{{ $contact->company_name }}" 
                />

                <x-form.input 
                    label="Position" 
                    name="position" 
                    type="text" 
                    value="{{ $contact->position }}" 
                />

                <x-form.input 
                    label="Email" 
                    name="email" 
                    type="email" 
                    value="{{ $contact->email }}" 
                />

                <h3>Phone Numbers</h3>

                <div class="col-span-6 flex justify-end">
                    <x-button
                        type="button"
                        color="secondary"
                        label="Add Another Phone Number"
                        onclick="addPhoneNumberInput(this)"
                    />
                </div>

                @foreach($contact->phoneNumbers as $index => $phoneNumber)
                    <x-form.input 
                        label="Phone Number {{ $index + 1 }}" 
                        name="number[]" 
                        type="text" 
                        value="{{ $phoneNumber->number }}" 
                        pattern="^\+?[1-9]\d{1,14}$"
                        title="Enter a valid phone number (e.g., +1234567890)"
                    />
                @endforeach

                @foreach(old('number', []) as $index => $number)
                    @if (!in_array($number, $contact->phoneNumbers->pluck('number')->toArray()))
                        <x-form.input 
                            label="Phone Number {{ $index + 1 }}" 
                            name="number[]" 
                            type="text" 
                            value="{{ $number }}" 
                            pattern="^\+?[1-9]\d{1,14}$"
                            title="Enter a valid phone number (e.g., +1234567890)"
                        />
                    @endif
                @endforeach
            </div>

            <div class="mt-6 flex items-center justify-end gap-x-6">
                <a href="{{ route('contacts.index') }}" class="text-sm/6 font-semibold text-gray-900">Cancel</a>
                <x-button type="submit" color="primary" label="Save" />
            </div>
        </form>
    </div>
@endsection

<script>
    function addPhoneNumberInput(button) {
        const container = button.closest('.grid');
        const newIndex = container.querySelectorAll('input[name="number[]"]').length + 1;

        const div = document.createElement('div');
        div.className = 'sm:col-span-3';
        div.innerHTML = `
            <label class="block text-sm font-medium leading-6 text-gray-900">Phone Number ${newIndex}</label>
            <div class="mt-2">
                <input
                    type="text"
                    name="number[]"
                    class="
                        block
                        w-full
                        rounded-md
                        bg-white
                        px-3
                        py-1.5
                        text-base
                        text-gray-900
                        outline
                        outline-1
                        -outline-offset-1
                        outline-gray-300
                        placeholder:text-gray-400
                        focus:outline
                        focus:outline-2
                        focus:-outline-offset-2
                        focus:outline-indigo-600
                        sm:text-sm/6
                    "
                >
            </div>
        `;
        container.appendChild(div);
    }
</script>