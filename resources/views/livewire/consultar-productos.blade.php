<div>
    @if (session()->has('success'))
        <div class="bg-green-500 text-white p-2 mb-4 rounded">
            {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="bg-red-500 text-white p-2 mb-4 rounded">
            {{ session('error') }}
        </div>
    @endif

    <div class="p-3">
        <button wire:click="consultarApi" class="bg-blue-500 text-white font-bold py-2 px-4 rounded">
            1.- Consultar API y Guardar Productos
        </button>

        <button wire:click="actualizarImagenes" class="bg-green-500 text-white font-bold py-2 px-4 rounded mt-4">
            2.- Actualizar Imágenes
        </button>
    </div>

   {{--  <div class="mt-4">
        <form action="/sync-first-product-to-shopify" method="GET">
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Sincronizar Primer Producto con Shopify
            </button>
        </form>
    </div> --}}
</div>
