<?php

use App\Models\Fach;
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
        Schema::create('sprachenfolge', function (Blueprint $table): void {
            $table->id();            
            $table->foreignIdFor(Schueler::class);
            $table->foreignIdFor(Fach::class);
            $table->integer('reihenfolge');
            $table->integer('belegungVonJahrgang')->nullable();
            $table->integer('belegungVonAbschnitt')->nullable();
            $table->integer('belegungBisJahrgang')->nullable();
            $table->integer('belegungBisAbschnitt')->nullable();
            $table->string('referenzniveau')->nullable();
            $table->integer('belegungSekI')->nullable();
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
        Schema::dropIfExists('sprachenfolge');
    }
};
