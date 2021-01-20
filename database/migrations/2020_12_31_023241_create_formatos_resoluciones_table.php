<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormatosResolucionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('formatos_resoluciones', function (Blueprint $table) {
            $table->id();

            $table->string('nombre');

            $table->string('descripcion');

            $table->string('estado')->default('BORRADOR');

            $table->string('ubicacion_plantilla')->nullable();;

            $table->text('form_schema')->nullable();

            $table->unsignedBigInteger('carrera_id');



            $table
                ->foreign('carrera_id')
                ->references('id')
                ->on('carreras')
                ->onDelete('cascade');

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
        Schema::table('formatos_resoluciones', function (Blueprint $table) {
            //
        });
    }
}
