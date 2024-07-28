<?php

// database/migrations/xxxx_xx_xx_create_productos_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductosTable extends Migration
{
    public function up()
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->string('id_producto')->unique();
            $table->string('codigo_barras');
            $table->text('descripcion');
            $table->string('nombre_producto');
            $table->string('categoria');
            $table->string('id_familiar');
            $table->string('id_linea_web');
            $table->decimal('stock', 8, 3);
            $table->decimal('precio_publico', 10, 2);
            $table->decimal('precio_cliente', 10, 2);
            $table->decimal('cantidad', 8, 3);
            $table->decimal('descuento', 5, 2);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('productos');
    }
}
