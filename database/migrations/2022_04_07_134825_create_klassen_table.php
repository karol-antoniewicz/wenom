<?php

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
        Schema::create('klassen', function (Blueprint $table): void {
            $table->id();
			$table->foreignIdFor(Jahrgang::class, 'idJahrgang')->nullable();
            $table->string('kuerzel');
            $table->string('kuerzelAnzeige');
            $table->integer('sortierung');
			$table->boolean('edit_overrideable')->default(true);
			$table->boolean('editable_teilnoten')->default(true);
			$table->boolean('editable_noten')->default(true);
			$table->boolean('editable_mahnungen')->default(true);
			$table->boolean('editable_fehlstunden')->default(true);
			$table->boolean('toggleable_fehlstunden')->default(true);
			$table->boolean('editable_fb')->default(true);
			$table->boolean('editable_asv')->default(true);
			$table->boolean('editable_aue')->default(true);
			$table->boolean('editable_zb')->default(true);
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('klassen');
    }
};
