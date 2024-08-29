<?php

use App\Livewire\Forms\LoginForm;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component {
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $this->redirectIntended(default: RouteServiceProvider::HOME, navigate: true);
    }
};
?>

<div>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form wire:submit.prevent="login">
        <!-- Operator Switch -->
        <div class="mb-4">
            <label for="isOperator" class="inline-flex items-center">
                <input id="isOperator" type="checkbox"
                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                <span class="ms-2 text-sm text-gray-600">{{ __('Are you an Operator?') }}</span>
            </label>
        </div>

        <!-- Machine Select -->
        <div class="mb-4">
            <x-input-label for="machine" :value="__('Select Machine')" />
            <select id="machine"
                class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                name="machine" disabled>
                <option value="">-- Select a machine --</option>
                <option value="A">Machine A</option>
                <option value="B">Machine B</option>
                <option value="C">Machine C</option>
            </select>
            <x-input-error :messages="$errors->get('selectedMachine')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input wire:model.defer="form.email" id="email" class="block mt-1 w-full" type="email"
                name="email" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('form.email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input wire:model.defer="form.password" id="password" class="block mt-1 w-full" type="password"
                name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('form.password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember" class="inline-flex items-center">
                <input wire:model.defer="form.remember" id="remember" type="checkbox"
                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    href="{{ route('password.request') }}" wire:navigate>
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button class="ms-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const isOperatorCheckbox = document.getElementById('isOperator');
            const machineSelect = document.getElementById('machine');
            const emailInput = document.getElementById('email');
            const passwordInput = document.getElementById('password');

            function handleOperatorToggle() {
                if (isOperatorCheckbox.checked) {
                    emailInput.readOnly = true;
                    passwordInput.readOnly = true;
                    machineSelect.disabled = false;
                } else {
                    emailInput.readOnly = false;
                    passwordInput.readOnly = false;
                    machineSelect.disabled = true;
                    emailInput.value = '';
                    passwordInput.value = '';
                    machineSelect.value = '';
                }
            }

            function handleMachineSelection() {
                if (isOperatorCheckbox.checked) {
                    let machineValue = machineSelect.value;
                    if (machineValue) {
                        let email = '';
                        let password = '';
                        switch (machineValue) {
                            case 'A':
                                email = 'operator_a@example.com';
                                password = 'password_a';
                                break;
                            case 'B':
                                email = 'operator_b@example.com';
                                password = 'password_b';
                                break;
                            case 'C':
                                email = 'operator_c@example.com';
                                password = 'password_c';
                                break;
                        }
                        emailInput.value = email;
                        passwordInput.value = password;

                        // Manually trigger a Livewire model update
                        emailInput.dispatchEvent(new Event('input'));
                        passwordInput.dispatchEvent(new Event('input'));
                    }
                }
            }

            isOperatorCheckbox.addEventListener('change', handleOperatorToggle);
            machineSelect.addEventListener('change', handleMachineSelection);
        });
    </script>
</div>
