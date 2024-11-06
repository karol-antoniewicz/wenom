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
        Schema::create('floskelgruppen', function (Blueprint $table): void {
            $table->id();
            $table->string('kuerzel')->unique();
            $table->string('bezeichnung');
            $table->string('hauptgruppe');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('floskelgruppen');
    }
};
