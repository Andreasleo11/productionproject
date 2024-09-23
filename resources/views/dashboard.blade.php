<x-app-layout>
    {{-- Header Slot --}}
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    {{-- Content --}}
    @if (auth()->user()->specification->name === 'Admin')
        @include('partials.dashboard-operator')
    @elseif (auth()->user()->specification->name === 'Admin' || auth()->user()->specification->name === 'PE')
        <div class="flex justify-center items-center">PE USER</div>
    @elseif (auth()->user()->specification->name === 'Operator')
        @include('partials.dashboard-machine')
    @endif

    <script type="module">
        Fancybox.bind('[data-fancybox="gallery"]', {
            Thumbs: {
                autoStart: true,
            },
            Image: {
                zoom: true,
            },
            transitionEffect: 'fade',
        });
    </script>

    {{-- JS for focusing on form inputs --}}
    {{--
        <script>
        document.addEventListener('DOMContentLoaded', (event) => {
        const spkCodeElement = document.getElementById('spk_code');
        const itemCodeElement = document.getElementById('item_code');
        
        if (spkCodeElement) {
        // If spk_code element is present, focus on it
        spkCodeElement.focus();
        } else if (itemCodeElement) {
        // If spk_code is not present but item_code is, focus on item_code
        itemCodeElement.focus();
        }
        });
        </script>
    --}}
</x-app-layout>
