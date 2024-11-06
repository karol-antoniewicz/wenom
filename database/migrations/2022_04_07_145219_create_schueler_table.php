<?php

use App\Models\Daten;
use App\Models\Jahrgang;
use App\Models\Klasse;
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
        Schema::create('schueler', function (Blueprint $table): void {
            $table->id();
            $table->foreignIdFor(Jahrgang::class);
            $table->foreignIdFor(Klasse::class);
            $table->string('nachname');
            $table->string('vorname');
            $table->char('geschlecht', 1);
            $table->string('bilingualeSprache')->nullable();
            $table->boolean('istZieldifferent')->default(false);
            $table->boolean('istDaZFoerderung')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('schueler');
    }
};
