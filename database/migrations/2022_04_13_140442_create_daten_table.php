<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('daten', function (Blueprint $table): void {
            $table->id();
            $table->integer('enmRevision')->nullable();
            $table->integer('schulnummer')->nullable();
            $table->integer('schuljahr')->nullable();
            $table->integer('anzahlAbschnitte')->nullable();
            $table->integer('aktuellerAbschnitt')->nullable();
            $table->string('publicKey')->nullable();
			$table->integer('lehrerID')->nullable();
            $table->boolean('fehlstundenEingabe')->nullable();
            $table->boolean('fehlstundenSIFachbezogen')->nullable();
            $table->boolean('fehlstundenSIIFachbezogen')->nullable();
            $table->string('schulform')->nullable();
            $table->string('mailadresse')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('daten');
    }
};
