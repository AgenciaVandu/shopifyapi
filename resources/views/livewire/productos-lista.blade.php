<div class="p-6 bg-white rounded-lg shadow-md">
    <h1 class="text-2xl font-semibold mb-4">Lista de Productos</h1>

    <div class="p-3">
        {{ $productos->links() }}
    </div>
    <!-- Tabla para mostrar los productos -->
    <table class="w-full border-collapse border border-gray-200">
        <thead class="bg-gray-100">
            <tr>
                <th class="border border-gray-300 p-3 text-left">ID</th>
                <th class="border border-gray-300 p-3 text-left">Nombre</th>
                <th class="border border-gray-300 p-3 text-left">Descripción</th>
                <th class="border border-gray-300 p-3 text-left">Categoría</th>
                <th class="border border-gray-300 p-3 text-left">Stock</th>
                <th class="border border-gray-300 p-3 text-left">Precio Público</th>
                <th class="border border-gray-300 p-3 text-left">Precio de Cliente</th>
                <th class="border border-gray-300 p-3 text-left">Descuento</th>
                <th class="border border-gray-300 p-3 text-left">Imagen</th>
            </tr>
        </thead>
        <tbody>
            @forelse($productos as $producto)
                <tr>
                    <td class="border border-gray-300 p-3">{{ $producto->id_producto }}</td>
                    <td class="border border-gray-300 p-3">{{ $producto->nombre_producto }}</td>
                    <td class="border border-gray-300 p-3">{{ $producto->descripcion }}</td>
                    <td class="border border-gray-300 p-3">{{ $producto->categoria }}</td>
                    <td class="border border-gray-300 p-3">{{ $producto->stock }}</td>
                    <td class="border border-gray-300 p-3">${{ number_format($producto->precio_publico, 2) }}</td>
                    <td class="border border-gray-300 p-3">${{ number_format($producto->precio_cliente, 2) }}</td>
                    <td class="border border-gray-300 p-3">{{ number_format($producto->descuento, 2) }}</td>
                    <td class="border border-gray-300 p-3">
                        @if ($producto->img)
                            <img src="{{ $producto->img }}" alt="Imagen del producto"
                                class="w-24 h-24 object-cover rounded-md">
                        @else
                            <span class="text-gray-500">Sin Imagen</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="border border-gray-300 p-3 text-center text-gray-500">No hay productos
                        disponibles.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <div class="p-3">
        {{ $productos->links() }}
    </div>
</div>
