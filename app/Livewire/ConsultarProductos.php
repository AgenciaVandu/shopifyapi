<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Http;
use App\Models\Producto;
use Illuminate\Support\Facades\Log;

class ConsultarProductos extends Component
{
    public function consultarApi()
    {
        $response = Http::get('https://dasnube.com/api/productos/stock/a77a9a00bc535a4b7d7cb15cce1f5356');

        if ($response->successful()) {
            $productos = $response->json();

            // Debugging: log the response
            Log::info('API Response:', $productos);

            // Iterar sobre cada producto en la respuesta
            foreach ($productos as $data) {
                if (isset($data['id_producto'])) {
                    Producto::updateOrCreate(
                        ['id_producto' => $data['id_producto']],
                        [
                            'codigo_barras' => $data['codigo_barras'] ?? '',
                            'descripcion' => $data['descripcion'] ?? '',
                            'nombre_producto' => $data['nombre_producto'] ?? '',
                            'categoria' => $data['categoria'] ?? '',
                            'id_familiar' => $data['id_familiar'] ?? '',
                            'id_linea_web' => $data['id_linea_web'] ?? '',
                            'stock' => isset($data['stock']) ? (float) $data['stock'] : 0.0,
                            'precio_publico' => isset($data['precio_publico']) ? (float) $data['precio_publico'] : 0.0,
                            'precio_cliente' => isset($data['precio_cliente']) ? (float) $data['precio_cliente'] : 0.0,
                            'cantidad' => isset($data['cantidad']) ? (float) $data['cantidad'] : 0.0,
                            'descuento' => isset($data['Descuento']) ? (float) $data['Descuento'] : 0.0,
                        ]
                    );
                } else {
                    // Manejar el caso cuando un producto no contiene los datos esperados
                    Log::warning('Producto sin id_producto:', $data);
                }
            }

            session()->flash('success', 'Productos actualizados exitosamente.');
        } else {
            // Manejar el caso cuando la respuesta de la API no es exitosa
            session()->flash('error', 'Error al consultar la API.');
        }
    }

    public function actualizarImagenes()
    {
        $productos = Producto::all();

        foreach ($productos as $producto) {
            $response = Http::get("https://dasnube.com/api/productos/id/{$producto->id_producto}/a77a9a00bc535a4b7d7cb15cce1f5356");

            if ($response->successful()) {
                $data = $response->json();

                // Debugging: log the response
                /* Log::info('API Response for Image:', $data); */

                // Asegurarse de que la respuesta contiene el campo 'img'
                if (is_array($data) && isset($data[0]['img'])) {
                    $producto->update([
                        'img' => $data[0]['img']
                    ]);
                } else {
                    // Manejar el caso cuando la respuesta no contiene el campo img
                    Log::warning('Campo img no encontrado para el producto ID: ' . $producto->id_producto, $data);
                }
            } else {
                // Manejar el caso cuando la respuesta de la API no es exitosa
                Log::error("Error al consultar la API para el producto ID: {$producto->id_producto}");
            }
        }

        session()->flash('success', 'Imágenes actualizadas exitosamente.');
    }

    public function testUpdateImg()
    {
        $producto = Producto::first();
        $response = Http::get("https://dasnube.com/api/productos/id/{$producto->id_producto}/a77a9a00bc535a4b7d7cb15cce1f5356");

        if ($response->successful()) {
            $data = $response->json();

            // Debugging: log the response
            //Log::info('API Response for Image:', $data);

            // Asegurarse de que la respuesta contiene el campo 'img'
            if (is_array($data) && isset($data[0]['img'])) {
                $producto->update([
                    'img' => $data[0]['img']
                ]);
            } else {
                // Manejar el caso cuando la respuesta no contiene el campo img
                Log::warning('Campo img no encontrado para el producto ID: ' . $producto->id_producto, $data);
            }
        } else {
            // Manejar el caso cuando la respuesta de la API no es exitosa
            Log::error("Error al consultar la API para el producto ID: {$producto->id_producto}");
        }

        session()->flash('success', 'Imágenes actualizadas exitosamente.');
    }

    public function render()
    {
        return view('livewire.consultar-productos');
    }
}
