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
        Schema::create('bkabschluesse', function (Blueprint $table): void {
            $table->id();
            $table->foreignIdFor(Schueler::class);
            $table->boolean('hatZulassung')->default(false);
            $table->boolean('hatBestanden')->default(false);
            $table->boolean('hatZulassungErweiterteBeruflicheKenntnisse')->default(false);
            $table->boolean('hatErworbenErweiterteBeruflicheKenntnisse')->default(false);
            $table->unsignedBigInteger('notePraktischePruefung');
            $table->unsignedBigInteger('noteKolloqium');
            $table->boolean('hatZulassungBerufsabschlusspruefung')->default(false);
            $table->boolean('hatBestandenBerufsabschlusspruefung')->default(false);
            $table->string('themaAbschlussarbeit');
            $table->boolean('istVorhandenBerufsabschlusspruefung')->default(false);    
            $table->unsignedBigInteger('noteFachpraxis');        
            $table->boolean('istFachpraktischerTeilAusreichend')->default(false);
            $table->timestamps();
            
            $table->foreign('notePraktischePruefung')->references('id')->on('noten');
            $table->foreign('noteFachpraxis')->references('id')->on('noten');
            $table->foreign('noteKolloqium')->references('id')->on('noten');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('bkabschluesse');
    }
};
