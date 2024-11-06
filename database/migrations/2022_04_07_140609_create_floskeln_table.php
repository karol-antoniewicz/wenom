<?php

use App\Models\Fach;
use App\Models\Floskelgruppe;
use App\Models\Jahrgang;
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
        Schema::create('floskeln', function (Blueprint $table): void {
            $table->id();
            $table->foreignIdFor(Floskelgruppe::class);
            $table->string('kuerzel');
            $table->text('text');
            $table->foreignIdFor(Fach::class)->nullable();
            $table->foreignIdFor(Jahrgang::class)->nullable();
            $table->integer('niveau')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('floskeln');
    }
};
