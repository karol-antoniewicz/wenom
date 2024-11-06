<?php

use App\Models\Daten;
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
        Schema::create('foerderschwerpunkte', function (Blueprint $table): void {
            $table->id();
            $table->string('kuerzel')->unique();
            $table->text('beschreibung');
            $table->integer('sortierung');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('foerderschwerpunkte');
    }
};
