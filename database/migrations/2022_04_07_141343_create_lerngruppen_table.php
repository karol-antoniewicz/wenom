<?php

use App\Models\Fach;
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
        Schema::create('lerngruppen', function (Blueprint $table): void {
            $table->id();
            $table->foreignIdFor(Klasse::class)->nullable();
            $table->foreignIdFor(Fach::class);
            $table->string('kID');
            $table->integer('kursartID')->nullable();
            $table->string('bezeichnung');
            $table->string('kursartKuerzel')->nullable();
            $table->string('bilingualeSprache')->nullable();
            $table->integer('wochenstunden');
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
        Schema::dropIfExists('lerngruppen');
    }
};
