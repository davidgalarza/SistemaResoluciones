<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResolucionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('resoluciones', function (Blueprint $table) {
            $table->id();
            $table->integer('nummero_resolucion')->nullable();

            $table->unsignedBigInteger('estudiante_id');
            $table->unsignedBigInteger('consejo_id');
            $table->unsignedBigInteger('formato_id');
            $table->unsignedBigInteger('usuario_id');




            $table
                ->foreign('usuario_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table
                ->foreign('formato_id')
                ->references('id')
                ->on('formatos_resoluciones')
                ->onDelete('cascade');


            $table
                ->foreign('consejo_id')
                ->references('id')
                ->on('consejos')
                ->onDelete('cascade');

            $table
                ->foreign('estudiante_id')
                ->references('id')
                ->on('estudiantes')
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
        Schema::table('resoluciones', function (Blueprint $table) {
            //
        });
    }
}
