<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-10xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-2 flex justify-end">
                    <!-- Botón para sincronizar productos con Shopify -->
                    <a href="{{ route('sync.products') }}"
                        class="inline-block px-6 py-2 text-white bg-blue-500 rounded-lg shadow hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                        3.- Sincronizar Productos con Shopify
                    </a>
                </div>

                @livewire('consultar-productos')

                @livewire('productos-lista')


            </div>
        </div>
    </div>
</x-app-layout>
