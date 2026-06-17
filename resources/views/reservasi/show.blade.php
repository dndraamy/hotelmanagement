<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Detail Reservasi
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg p-6">
                <p class="text-gray-600 dark:text-gray-400">Detail reservasi akan diimplementasi di PBI-29.</p>
                <a href="{{ route('reservasi.index') }}"
                   class="mt-4 inline-block bg-gray-500 hover:bg-gray-600 text-white text-sm px-4 py-2 rounded">
                    ← Kembali
                </a>
            </div>
        </div>
    </div>
</x-app-layout>