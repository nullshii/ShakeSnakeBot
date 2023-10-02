<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->string('state')->nullable();
            $table->integer('vote_up')->default(0);
            $table->integer('vote_down')->default(0);
            $table->integer('vote_left')->default(0);
            $table->integer('vote_right')->default(0);
            $table->boolean('is_over')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('games');
    }
};
