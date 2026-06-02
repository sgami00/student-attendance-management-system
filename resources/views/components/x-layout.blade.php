<x-layout>
    <x-slot:nav>
        <x-nav-link href="/dashboard">Dashboard</x-nav-link>
        <x-nav-link href="/analytics">Analytics Dashboard</x-nav-link>
    </x-slot:nav>

    {{ $slot }}
</x-layout>