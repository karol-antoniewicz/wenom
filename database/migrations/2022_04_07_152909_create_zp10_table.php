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
        Schema::create('zp10', function (Blueprint $table): void {
            $table->id();
            $table->foreignIdFor(Schueler::class);
            $table->foreignIdFor(Fach::class);
            $table->unsignedBigInteger('vornote');
            $table->unsignedBigInteger('noteSchriftlichePruefung');            
            $table->boolean('muendlichePruefung')->default(false);
            $table->boolean('muendlichePruefungFreiwillig')->default(false);            
            $table->unsignedBigInteger('noteMuendlichePruefung');
            $table->unsignedBigInteger('abschlussnote');
            $table->timestamps();
            
            $table->foreign('vornote')->references('id')->on('noten');
            $table->foreign('noteSchriftlichePruefung')->references('id')->on('noten');
            $table->foreign('noteMuendlichePruefung')->references('id')->on('noten');
            $table->foreign('abschlussnote')->references('id')->on('noten');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('zp10');
    }
};