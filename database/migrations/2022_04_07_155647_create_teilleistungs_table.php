<?php

use App\Models\{Leistung, Note, Teilleistungsart};
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
        Schema::create('teilleistungen', function (Blueprint $table): void {
            $table->id();
            $table->foreignIdFor(Leistung::class);
            $table->foreignIdFor(Teilleistungsart::class);
			$table->timestamp('tsArtID', 3)->default(now());
            $table->date('datum')->nullable();
			$table->timestamp('tsDatum', 3)->default(now());
            $table->string('bemerkung')->nullable();
			$table->timestamp('tsBemerkung', 3)->default(now());
            $table->foreignIdFor(Note::class)->nullable();
			$table->timestamp('tsNote', 3)->default(now());
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
        Schema::dropIfExists('teilleistungen');
    }
};
