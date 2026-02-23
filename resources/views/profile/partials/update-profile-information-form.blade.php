<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            پروفائل کی معلومات
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            اپنے اکاؤنٹ کی پروفائل معلومات اور ای میل ایڈریس اپ ڈیٹ کریں۔
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" :value="'نام'" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name ?? '')" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="'ای میل'" />
            <!-- Do not pre-fill the email from the user; keep this visually empty unless there is a validation error -->
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email')" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user && $user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800 dark:text-gray-200">
                        آپ کا ای میل ایڈریس تصدیق شدہ نہیں ہے۔

                        <button form="send-verification" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                            تصدیقی ای میل دوبارہ بھیجنے کے لیے یہاں کلک کریں۔
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600 dark:text-green-400">
                            آپ کے ای میل ایڈریس پر ایک نیا تصدیقی لنک بھیج دیا گیا ہے۔
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>محفوظ کریں</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600 dark:text-gray-400"
                >محفوظ کر لیا گیا۔</p>
            @endif
        </div>
    </form>
</section>
