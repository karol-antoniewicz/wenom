<?php

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
        Schema::create('bemerkungen', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Schueler::class);
            $table->text('ASV', 3)->nullable();
			$table->timestamp('tsASV')->default(now())->comment('Timestamp');
            $table->text('AUE')->nullable();
			$table->timestamp('tsAUE', 3)->default(now())->comment('Timestamp');
            $table->text('ZB')->nullable();
			$table->timestamp('tsZB', 3)->default(now())->comment('Timestamp');
            $table->string('LELS')->nullable();
            $table->string('schulformEmpf')->nullable();
            $table->string('individuelleVersetzungsbemerkungen')->nullable();
			$table->timestamp('tsIndividuelleVersetzungsbemerkungen', 3)->default(now())->comment('Timestamp');
            $table->string('foerderbemerkungen')->nullable();
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
        Schema::dropIfExists('bemerkungen');
    }
};
