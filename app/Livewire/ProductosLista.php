<?php

namespace App\Livewire;

use App\Models\Producto;
use Livewire\Component;

class ProductosLista extends Component
{
    public function render()
    {
        // Obtén todos los productos de la base de datos
        $productos = Producto::paginate();

        return view('livewire.productos-lista', compact('productos'));
    }
}
