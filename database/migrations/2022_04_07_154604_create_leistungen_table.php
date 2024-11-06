<?php

use App\Models\{Lerngruppe, Note, Schueler};
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /** Run the migrations. */
    public function up(): void
    {
        Schema::create('leistungen', function (Blueprint $table): void {
            $table->id();
            $table->foreignIdFor(Schueler::class);
            $table->foreignIdFor(Lerngruppe::class);
            $table->foreignIdFor(Note::class)->nullable();
            $table->timestamp('tsNote', 3)->default(now());
            $table->foreignIdFor(Note::class, 'note_quartal_id')->nullable();
            $table->timestamp('tsNoteQuartal', 3)->default(now());
            $table->boolean('istSchriftlich')->default(false);
            $table->string('abiturfach')->nullable();
            $table->integer('fehlstundenFach')->nullable();
            $table->timestamp('tsFehlstundenFach', 3)->default(now());
            $table->integer('fehlstundenUnentschuldigtFach')->nullable();
            $table->timestamp('tsFehlstundenUnentschuldigtFach', 3)->default(now());
            $table->text('fachbezogeneBemerkungen')->nullable();
            $table->timestamp('tsFachbezogeneBemerkungen', 3)->default(now());
            $table->string('neueZuweisungKursart')->nullable();
            $table->boolean('istGemahnt')->default(false);
            $table->timestamp('tsIstGemahnt', 3)->default(now());
            $table->timestamp('mahndatum')->nullable();
            $table->timestamps();
        });
    }

    /** Reverse the migrations. */
    public function down(): void
    {
        Schema::dropIfExists('leistungen');
    }
};
