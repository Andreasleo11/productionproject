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

<div class="flex">
    <!-- Sidebar -->
    <aside class="bg-white w-64 h-screen border-r border-gray-200 fixed top-0 left-0 flex flex-col justify-between">
        <!-- Top Content -->
        <div class="px-6 py-4 flex-grow overflow-y-auto">
            <!-- Logo -->
            <div class="flex items-center justify-between">
                <a href="{{ route('dashboard') }}" wire:navigate>
                    <x-application-logo class="block h-20 w-auto fill-current text-gray-800" />
                </a>
                <span class="ms-4 font-semibold text-sm">
                    Daijo Production Project
                </span>
            </div>

            <!-- Navigation Links -->
            <div class="space-y-2 mt-4">
                <livewire:sidebar-link href="{{ route('dashboard') }}" label="Dashboard" :active="request()->routeIs('dashboard')"
                    wire:navigate />

                <!-- Admin Links -->
                @if (auth()->user()->can('view-admin-links'))
                    <livewire:sidebar-link href="{{ route('barcode.index') }}" label="Generate Master Barcode"
                        :active="request()->routeIs('barcode.index')" wire:navigate />

                    <livewire:sidebar-link href="{{ route('so.index') }}" label="DO Index" :active="request()->routeIs('so.index')"
                        wire:navigate />
                @endif

                <!-- PE Links -->
                @if (auth()->user()->can('view-pe-links'))
                    <livewire:sidebar-link href="{{ route('master-item.index') }}" label="Master Item" :active="request()->routeIs('master-item.index')"
                        wire:navigate />
                @endif

                <!-- PPIC Links -->
                @if (auth()->user()->can('view-ppic-links'))
                    <livewire:sidebar-link href="{{ route('daily-item-code.index') }}" label="Daily Production Plan"
                        :active="request()->routeIs('daily-item-code.index')" wire:navigate />
                @endif

                <!-- Store Links -->
                @if (auth()->user()->can('view-store-links'))
                    <livewire:parent-dropdown label="Store" :childRoutes="[
                        ['name' => 'barcodeindex', 'label' => 'Create Barcode'],
                        ['name' => 'inandout.index', 'label' => 'Scan Barcode'],
                        ['name' => 'list.barcode', 'label' => 'Report History'],
                        ['name' => 'stockallbarcode', 'label' => 'Stock Item'],
                        ['name' => 'updated.barcode.item.position', 'label' => 'List All Item Barcode'],
                    ]" />
                @endif

            </div>
        </div>

        <!-- Bottom Dropdown Menu -->
        <div class="px-6 py-4 border-t border-gray-200" x-cloak>
            <div x-data="{ open: false }" class="relative">
                <!-- Dropdown Trigger -->
                <button @click="open = !open"
                    class="w-full inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                    <div x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name" x-on:profile-updated.window="name = $event.detail.name">
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="ms-1 size-2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                    </svg>

                </button>

                <!-- Dropdown Menu -->
                <div x-show="open" @click.away="open = false" x-transition
                    class="absolute right-0 bottom-full mb-2 w-48 bg-white shadow-lg z-10 rounded-md ring-1 ring-black ring-opacity-5">
                    <div class="py-1">
                        @if (auth()->user()->specification->name === 'Admin')
                            <!-- Profile Link -->
                            <a href="{{ route('profile') }}"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900">
                                {{ __('Profile') }}
                            </a>
                        @endif

                        <!-- Logout Button -->
                        <button wire:click="logout"
                            class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900">
                            {{ __('Log Out') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </aside>
</div>
