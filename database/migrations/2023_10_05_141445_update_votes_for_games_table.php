<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('games', function (Blueprint $table) {
            $table->dropColumn('vote_up');
            $table->dropColumn('vote_down');
            $table->dropColumn('vote_left');
            $table->dropColumn('vote_right');
            $table->string('vote', 5)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('games', function (Blueprint $table) {
            $table->dropColumn('vote');
            $table->integer('vote_up')->default(0);
            $table->integer('vote_down')->default(0);
            $table->integer('vote_left')->default(0);
            $table->integer('vote_right')->default(0);
        });
    }
};
