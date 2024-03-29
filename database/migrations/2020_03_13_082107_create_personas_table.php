<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePersonasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('personas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre',100)->nullable();
            $table->string('apellido',100)->nullable();
            $table->string('tipo_documento',20)->nullable();
            $table->string('num_documento',20)->nullable();
            $table->string('email',50)->nullable();
            $table->string('telefono',20)->nullable();
            $table->string('direccion',20)->nullable();
            $table->string('razon_social',20);
            $table->string('nombre_empresa',30);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('personas');
    }
}
