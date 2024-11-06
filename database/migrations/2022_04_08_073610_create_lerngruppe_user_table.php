<?php

use App\Models\Lerngruppe;
use App\Models\User;
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
        Schema::create('lerngruppe_user', function (Blueprint $table): void {
            $table->foreignIdFor(User::class);
            $table->foreignIdFor(Lerngruppe::class);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('lerngruppe_user');
    }
};
