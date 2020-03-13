<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetalleVentasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalle_ventas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('idventa')->unsigned();
            $table->foreign('idventa')->references('id')->on('ventas')->onDelete('cascade');
            $table->integer('idarticulo')->unsigned();
            $table->foreign('idarticulo')->references('id')->on('articulos');
            $table->string('logo');
            $table->string('leyenda1');
            $table->string('leyenda2');
            $table->string('leyenda3');
            $table->string('leyenda4');
            $table->integer('cantidad');
            $table->decimal('precio',19,4);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detalle_ventas');
    }
}
