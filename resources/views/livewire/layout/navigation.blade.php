<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component {
    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>

<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" wire:navigate>
                        <x-application-logo
                            class="block h-9 w-auto fill-current text-gray-800"
                        />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link
                        :href="route('dashboard')"
                        :active="request()->routeIs('dashboard')"
                        wire:navigate
                    >
                        {{ __('Dashboard') }}
                    </x-nav-link>
                </div>

                @if (auth()->user()->specification->name === 'Operator')
                    <!-- Navigation Links -->
                    <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                        <x-nav-link
                            :href="route('barcode.index')"
                            :active="request()->routeIs('barcode.index')"
                            wire:navigate
                        >
                            {{ __('Barcode Index') }}
                        </x-nav-link>
                    </div>

                    <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                        <x-nav-link
                            :href="route('so.index')"
                            :active="request()->routeIs('so.index')"
                            wire:navigate
                        >
                            {{ __('DO Index') }}
                        </x-nav-link>
                    </div>

                    <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                        <x-nav-link
                            :href="route('daily-item-code.index')"
                            :active="request()->routeIs('daily-item-code.index')"
                            wire:navigate
                        >
                            {{ __('Daily Item Codes') }}
                        </x-nav-link>
                    </div>
                @endif

                @if (auth()->user()->specification->name === 'Store')
                    <div
                        class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex relative"
                    >
                        <button
                            id="storeDropdownButton"
                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150"
                        >
                            Store
                            <svg
                                class="ml-2 -mr-0.5 h-4 w-4"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M19 9l-7 7-7-7"
                                />
                            </svg>
                        </button>
                        <!-- Dropdown Menu -->
                        <div
                            id="storeDropdownMenu"
                            class="absolute mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 hidden"
                        >
                            <div class="py-1">
                                <x-nav-link
                                    :href="route('barcodeindex')"
                                    :active="request()->routeIs('barcodeindex')"
                                    wire:navigate
                                >
                                    {{ __('Create Barcode') }}
                                </x-nav-link>
                                <x-nav-link
                                    :href="route('inandout.index')"
                                    :active="request()->routeIs('inandout.index')"
                                    wire:navigate
                                >
                                    {{ __('Scan Barcode') }}
                                </x-nav-link>
                                <x-nav-link
                                    :href="route('list.barcode')"
                                    :active="request()->routeIs('list.barcode')"
                                    wire:navigate
                                >
                                    {{ __('Report History') }}
                                </x-nav-link>
                                <x-nav-link
                                    :href="route('stockallbarcode')"
                                    :active="request()->routeIs('stockallbarcode')"
                                    wire:navigate
                                >
                                    {{ __('Stock Item') }}
                                </x-nav-link>
                                <x-nav-link
                                    :href="route('updated.barcode.item.position')"
                                    :active="request()->routeIs('updated.barcode.item.position')"
                                    wire:navigate
                                >
                                    {{ __('List All Item Barcode') }}
                                </x-nav-link>
                            </div>
                        </div>
                    </div>

                    <script>
                        document
                            .getElementById('storeDropdownButton')
                            .addEventListener('click', function () {
                                var dropdownMenu =
                                    document.getElementById(
                                        'storeDropdownMenu',
                                    );
                                dropdownMenu.classList.toggle('hidden');
                            });

                        // Close dropdown when clicking outside
                        window.onclick = function (event) {
                            if (!event.target.matches('#storeDropdownButton')) {
                                var dropdownMenu =
                                    document.getElementById(
                                        'storeDropdownMenu',
                                    );
                                if (
                                    !dropdownMenu.classList.contains('hidden')
                                ) {
                                    dropdownMenu.classList.add('hidden');
                                }
                            }
                        };
                    </script>
                @endif

                @if (auth()->user()->specification->name === 'PE')
                    <!-- Navigation Links -->
                    <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                        <x-nav-link
                            :href="route('master-item.index')"
                            :active="request()->routeIs('master-item.index')"
                            wire:navigate
                        >
                            {{ __('Master Item') }}
                        </x-nav-link>
                    </div>
                @endif
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150"
                        >
                            <div
                                x-data="{{ json_encode(['name' => auth()->user()->name]) }}"
                                x-text="name"
                                x-on:profile-updated.window="name = $event.detail.name"
                            ></div>

                            <div class="ms-1">
                                <svg
                                    class="fill-current h-4 w-4"
                                    xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd"
                                    />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link
                            :href="route('profile')"
                            wire:navigate
                        >
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <button wire:click="logout" class="w-full text-start">
                            <x-dropdown-link>
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </button>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button
                    @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out"
                >
                    <svg
                        class="h-6 w-6"
                        stroke="currentColor"
                        fill="none"
                        viewBox="0 0 24 24"
                    >
                        <path
                            :class="{ 'hidden': open, 'inline-flex': !open }"
                            class="inline-flex"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16"
                        />
                        <path
                            :class="{ 'hidden': !open, 'inline-flex': open }"
                            class="hidden"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M6 18L18 6M6 6l12 12"
                        />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link
                :href="route('dashboard')"
                :active="request()->routeIs('dashboard')"
                wire:navigate
            >
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div
                    class="font-medium text-base text-gray-800"
                    x-data="{{ json_encode(['name' => auth()->user()->name]) }}"
                    x-text="name"
                    x-on:profile-updated.window="name = $event.detail.name"
                ></div>
                <div class="font-medium text-sm text-gray-500">
                    {{ auth()->user()->email }}
                </div>
            </div>

            <div class="mt-3 space-y-1">
                <!-- <x-responsive-nav-link :href="route('profile')" wire:navigate >
                    {{ __('Profile') }}
                </x-responsive-nav-link> -->

                <!-- Authentication -->
                <button wire:click="logout" class="w-full text-start">
                    <x-responsive-nav-link>
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </button>
            </div>
        </div>
    </div>
</nav>

{{--
    <div x-data="{ open: false }" class="flex">
    <!-- Sidebar -->
    <aside class="bg-white w-64 h-screen border-r border-gray-200">
    <div class="px-6 py-4">
    <!-- Logo -->
    <div class="flex items-center">
    <a href="{{ route('dashboard') }}" wire:navigate>
    <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
    </a>
    </div>
    </div>
    
    <!-- Navigation Links -->
    <div class="space-y-4 px-6">
    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" wire:navigate>
    {{ __('Dashboard') }}
    </x-nav-link>
    
    @if (auth()->user()->specification->name === 'Operator')
    <x-nav-link :href="route('barcode.index')" :active="request()->routeIs('barcode.index')" wire:navigate>
    {{ __('Barcode Index') }}
    </x-nav-link>
    
    <x-nav-link :href="route('so.index')" :active="request()->routeIs('so.index')" wire:navigate>
    {{ __('SO Index') }}
    </x-nav-link>
    
    <x-nav-link :href="route('daily-item-code.index')" :active="request()->routeIs('daily-item-code.index')" wire:navigate>
    {{ __('Daily Item Codes') }}
    </x-nav-link>
    
    <!-- Dropdown for Store -->
    <div x-data="{ dropdownOpen: false }" class="relative">
    <button @click="dropdownOpen = ! dropdownOpen" class="w-full text-left inline-flex items-center px-3 py-2 bg-white border border-transparent text-gray-500 hover:text-gray-700">
    {{ __('Store') }}
    <svg class="ml-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
    </svg>
    </button>
    
    <div x-show="dropdownOpen" @click.away="dropdownOpen = false" class="absolute left-0 mt-2 w-full bg-white shadow-lg z-20">
    <x-nav-link :href="route('barcodeindex')" :active="request()->routeIs('barcodeindex')" wire:navigate>
    {{ __('Create Barcode') }}
    </x-nav-link>
    <x-nav-link :href="route('inandout.index')" :active="request()->routeIs('inandout.index')" wire:navigate>
    {{ __('Scan Barcode') }}
    </x-nav-link>
    <x-nav-link :href="route('list.barcode')" :active="request()->routeIs('list.barcode')" wire:navigate>
    {{ __('Report History') }}
    </x-nav-link>
    <x-nav-link :href="route('stockallbarcode')" :active="request()->routeIs('stockallbarcode')" wire:navigate>
    {{ __('Stock Item') }}
    </x-nav-link>
    <x-nav-link :href="route('updated.barcode.item.position')" :active="request()->routeIs('updated.barcode.item.position')" wire:navigate>
    {{ __('List All Item Barcode') }}
    </x-nav-link>
    </div>
    </div>
    @endif
    
    @if (auth()->user()->specification->name === 'PE')
    <x-nav-link :href="route('master-item.index')" :active="request()->routeIs('master-item.index')" wire:navigate>
    {{ __('Master Item') }}
    </x-nav-link>
    @endif
    </div>
    </aside>
    
    <!-- Page Content -->
    <div class="flex-1 min-h-screen bg-gray-100">
    <!-- Page Heading -->
    @if (isset($header))
    <header class="bg-white shadow">
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    {{ $header }}
    </div>
    </header>
    @endif
    
    <!-- Main Content -->
    <main>
    {{ $slot }}
    </main>
    </div>
    </div>
--}}
