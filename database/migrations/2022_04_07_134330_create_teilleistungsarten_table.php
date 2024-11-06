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
        Schema::create('teilleistungsarten', function (Blueprint $table): void {
            $table->id();
            $table->string('bezeichnung');
            $table->integer('sortierung')->nullable();
            $table->decimal('gewichtung', 3, 1)->nullable();
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
        Schema::dropIfExists('teilleistungsarten');
    }
};
