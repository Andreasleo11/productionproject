<?php

use Livewire\Volt\Component;

new class extends Component {
    public string $href;
    public bool $active;
    public string $label;

    public function mount(string $href, string $label, bool $active = false)
    {
        $this->href = $href;
        $this->label = $label;
        $this->active = $active;
    }
}; ?>

<a href="{{ $href }}" class="{{ $active ? 'block w-full px-4 py-2 text-sm font-medium border-l-4 border-indigo-500 text-indigo-400 bg-indigo-100' : 'block w-full px-4 py-2 text-sm font-medium border-l-4 border-transparent text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
    {{ $label }}
</a>
