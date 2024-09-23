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
    <aside
        class="bg-white w-64 h-screen border-r border-gray-200 fixed top-0 left-0 flex flex-col justify-between"
    >
        <!-- Top Content -->
        <div class="px-6 py-4 flex-grow overflow-y-auto">
            <!-- Logo -->
            <div class="flex items-center justify-center">
                <a href="{{ route('dashboard') }}" wire:navigate>
                    <x-application-logo
                        class="block h-9 w-auto fill-current text-gray-800"
                    />
                </a>
            </div>

            <!-- Navigation Links -->
            <div class="space-y-2 mt-4">
                <livewire:sidebar-link
                    href="{{ route('dashboard') }}"
                    label="Dashboard"
                    :active="request()->routeIs('dashboard')"
                    wire:navigate
                />

                @if (auth()->user()->specification->name === 'Admin')
                    <livewire:sidebar-link
                        href="{{ route('barcode.index') }}"
                        label="Generate Master Barcode"
                        :active="request()->routeIs('barcode.index')"
                        wire:navigate
                    />

                    <livewire:sidebar-link
                        href="{{ route('so.index') }}"
                        label="SO Index"
                        :active="request()->routeIs('so.index')"
                        wire:navigate
                    />

                    <livewire:sidebar-link
                        href="{{ route('daily-item-code.index') }}"
                        label="Daily Production Plan"
                        :active="request()->routeIs('daily-item-code.index')"
                        wire:navigate
                    />

                    <livewire:parent-dropdown
                        label="Store"
                        :childRoutes="[
                        ['name' => 'barcodeindex', 'label' => 'Create Barcode'],
                        ['name' => 'inandout.index', 'label' => 'Scan Barcode'],
                        ['name' => 'list.barcode', 'label' => 'Report History'],
                        ['name' => 'stockallbarcode', 'label' => 'Stock Item'],
                        ['name' => 'updated.barcode.item.position', 'label' => 'List All Item Barcode'],
                    ]"
                    />
                @endif

                @if (auth()->user()->specification->name === 'PE')
                    <livewire:sidebar-link
                        href="{{ route('master-item.index') }}"
                        label="Master Item"
                        :active="request()->routeIs('master-item.index')"
                        wire:navigate
                    />
                @endif
            </div>
        </div>

        <!-- Bottom Dropdown Menu -->
        <div class="px-6 py-4 border-t border-gray-200" x-cloak>
            <div x-data="{ open: false }" class="relative">
                <!-- Dropdown Trigger -->
                <button
                    @click="open = !open"
                    class="w-full inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150"
                >
                    <div
                        x-data="{{ json_encode(['name' => auth()->user()->name]) }}"
                        x-text="name"
                        x-on:profile-updated.window="name = $event.detail.name"
                    ></div>
                    <svg
                        class="ml-1 h-4 w-4"
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 20 20"
                        fill="currentColor"
                    >
                        <path
                            fill-rule="evenodd"
                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                            clip-rule="evenodd"
                        />
                    </svg>
                </button>

                <!-- Dropdown Menu -->
                <div
                    x-show="open"
                    @click.away="open = false"
                    x-transition
                    class="absolute right-0 bottom-full mb-2 w-48 bg-white shadow-lg z-10 rounded-md ring-1 ring-black ring-opacity-5"
                >
                    <div class="py-1">
                        @if (auth()->user()->specification->name === 'Operator')
                            <!-- Profile Link -->
                            <a
                                href="{{ route('profile') }}"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900"
                            >
                                {{ __('Profile') }}
                            </a>
                        @endif

                        <!-- Logout Button -->
                        <button
                            wire:click="logout"
                            class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900"
                        >
                            {{ __('Log Out') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </aside>
</div>
