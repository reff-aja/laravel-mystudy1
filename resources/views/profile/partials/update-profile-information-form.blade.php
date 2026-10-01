<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Perbarui nama dan foto profil akunmu.') }}
        </p>
    </header>

    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div class="flex flex-col gap-4 sm:flex-row sm:items-center">
            <div class="shrink-0 rounded-full ring-4 ring-[#68C7EC]/25 ring-offset-2 ring-offset-white dark:ring-offset-[#001818]">
                @if ($user->profile_photo_path)
                    <img src="{{ asset('storage/' . $user->profile_photo_path) }}" alt="Foto profil {{ $user->name }}" class="h-20 w-20 rounded-full object-cover">
                @else
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=68C7EC&color=000F0F&bold=true" alt="Foto profil {{ $user->name }}" class="h-20 w-20 rounded-full object-cover">
                @endif
            </div>
            <div>
                <x-input-label for="profile_photo" value="Foto profil" />
                <input id="profile_photo" name="profile_photo" type="file" accept="image/jpeg,image/png,image/webp" class="mt-2 block w-full text-sm text-gray-600 file:mr-4 file:rounded-full file:border-0 file:bg-[#68C7EC] file:px-4 file:py-2 file:font-semibold file:text-[#000F0F] hover:file:opacity-90 dark:text-gray-300" />
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">JPG, PNG, atau WEBP. Maksimal 2 MB.</p>
                <x-input-error class="mt-2" :messages="$errors->get('profile_photo')" />
            </div>
        </div>

        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
