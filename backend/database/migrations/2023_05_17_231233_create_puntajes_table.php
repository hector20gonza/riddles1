<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
  {
    Schema::create('puntajes', function (Blueprint $table) {
        $table->id();
        $table->bigInteger('usuario_id')->unsigned();;
        $table->bigInteger('sala_id')->unsigned();;
        $table->integer('puntos');
      

        $table->foreign('usuario_id')->references('id')->on('users')->onDelete('cascade');
        $table->foreign('sala_id')->references('id')->on('salas')->onDelete('cascade');
        $table->timestamps();
    });
  }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('puntajes');
    }
};
