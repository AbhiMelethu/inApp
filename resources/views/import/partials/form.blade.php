<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Import User Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update user informations in a csv format file.") }}
        </p>
    </header>

    <form method="post" action="{{ route('import.save') }}" enctype="multipart/form-data" class="mt-6 space-y-6">
        @csrf
        @method('post')

        <div>
            <x-input-label for="file" :value="__('Upload File')" />
            <input type="file" name="csv_file" accept=".csv" class="mt-1 block w-full">
            <x-input-error class="mt-2" :messages="$errors->get('csv_file')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Import') }}</x-primary-button>

            @if (session('error'))
                <b
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 5000)"
                    class="text-sm text-gray-600"
                style="color:red">{{ __(session('error')) }}<b/>
            @endif

            @if (session('success'))
                <b
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 5000)"
                    class="text-sm text-gray-600"
                style="color:green">{{ __(session('success')) }}<b/>
            @endif
        </div>
    </form>
</section>
