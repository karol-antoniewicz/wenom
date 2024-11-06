<?php

use App\Models\Schueler;
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
        Schema::create('lernabschnitte', function (Blueprint $table): void {
            $table->id();
            $table->foreignIdFor(Schueler::class);
			$table->integer('fehlstundenGesamt')->nullable();
			$table->timestamp('tsFehlstundenGesamt', 3)->nullable();
			$table->integer('fehlstundenGesamtUnentschuldigt')->nullable();
			$table->timestamp('tsFehlstundenGesamtUnentschuldigt', 3)->nullable();
            $table->string('pruefungsordnung');
            $table->unsignedBigInteger('lernbereich1note')->nullable();
            $table->unsignedBigInteger('lernbereich2note')->nullable();
            $table->unsignedBigInteger('foerderschwerpunkt1')->nullable();
            $table->unsignedBigInteger('foerderschwerpunkt2')->nullable();
            $table->timestamps();

            $table->foreign('lernbereich1note')->references('id')->on('noten');
            $table->foreign('lernbereich2note')->references('id')->on('noten');
            $table->foreign('foerderschwerpunkt1')->references('id')->on('foerderschwerpunkte');
            $table->foreign('foerderschwerpunkt2')->references('id')->on('foerderschwerpunkte');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('lernabschnitte');
    }
};
