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
        Schema::create('faecher', function (Blueprint $table): void {
            $table->id();
            $table->string('kuerzel');
            $table->string('kuerzelAnzeige');
            $table->integer('sortierung');
            $table->boolean('istFremdsprache')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('faecher');
    }
};
