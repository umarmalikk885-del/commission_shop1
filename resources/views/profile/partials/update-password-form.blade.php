<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            پاس ورڈ اپ ڈیٹ کریں
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            یقینی بنائیں کہ آپ کا اکاؤنٹ محفوظ رہنے کے لیے طویل اور بے ترتیب پاس ورڈ استعمال کر رہا ہے۔
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        <div>
            <x-input-label for="update_password_current_password" :value="'موجودہ پاس ورڈ'" />
            <x-text-input id="update_password_current_password" name="current_password" type="password" class="mt-1 block w-full" autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="update_password_password" :value="'نیا پاس ورڈ'" />
            <x-text-input id="update_password_password" name="password" type="password" class="mt-1 block w-full" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="update_password_password_confirmation" :value="'پاس ورڈ کی تصدیق کریں'" />
            <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>محفوظ کریں</x-primary-button>

            @if (session('status') === 'password-updated')
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
