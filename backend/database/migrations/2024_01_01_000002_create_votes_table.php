<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('votes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('player_id')
                  ->constrained('players')
                  ->cascadeOnDelete();
            $table->string('ip_address', 45); // supports IPv6
            $table->timestamp('created_at')->useCurrent();

            // Prevent the same IP from voting for the same player more than once
            $table->unique(['player_id', 'ip_address']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('votes');
    }
};
