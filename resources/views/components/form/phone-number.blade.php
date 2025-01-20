@props(['numbers' => []])

<div class="col-span-6 flex justify-end">
    <button type="button" onclick="addPhoneNumberInput(this)" class="rounded-md bg-gray-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-gray-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-600">
        Add Another Phone Number wow
    </button>
</div>

<div class="phone-number-container">
    @foreach(old('number', []) as $index => $number)
    <x-form.input 
            label="Phone Number {{ $index + 1 }}" 
        name="number[]" 
        type="text" 
        value="{{ $number }}" 
    />
    @endforeach

    @if(empty(old('number')))
    <x-form.input 
            label="Phone Number 1" 
            name="number[]" 
            type="text"
    />
@endif
</div>
<script>
    function addPhoneNumberInput(button) {

        const container = button.closest('.phone-number-container');
        const existingInputs = container.querySelectorAll('input[name="number[]"]');
        console.log(existingInputs);
        const newIndex = existingInputs.length;

        const div = document.createElement('div');
        div.className = 'phone-number-input sm:col-span-3';
        div.innerHTML = `
            <label for="phone-number-${newIndex}" class="block text-sm font-medium text-gray-900">Phone Number ${newIndex + 1}</label>
            <div class="mt-2">
                <input
                    type="text"
                    id="phone-number-${newIndex}"
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
                        outline-gray-300
                        placeholder:text-gray-400
                        focus:outline-2
                        focus:outline-indigo-600
                        sm:text-sm
                    "
                />
            </div>
        `;
        existingInputs.ap(div, button.closest('.col-span-6'));
    }
</script>
