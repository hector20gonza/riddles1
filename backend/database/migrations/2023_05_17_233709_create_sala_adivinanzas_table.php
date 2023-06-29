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
    Schema::create('sala_adivinanzas', function (Blueprint $table) {
        $table->id();
        $table->bigInteger('sala_id')->unsigned();;
        $table->bigInteger('adivinanza_id')->unsigned();;
        $table->timestamps();

        $table->foreign('sala_id')->references('id')->on('salas')->onDelete('cascade');
        $table->foreign('adivinanza_id')->references('id')->on('adivinanzas')->onDelete('cascade');
    });
  }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sala_adivinanzas');
    }
};
