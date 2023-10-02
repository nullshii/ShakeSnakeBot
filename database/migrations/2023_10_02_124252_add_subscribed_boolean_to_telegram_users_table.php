<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('telegram_users', function (Blueprint $table) {
            $table->boolean('is_subscribed');
        });
    }

    public function down(): void
    {
        Schema::table('telegram_users', function (Blueprint $table) {
            $table->dropColumn('is_subscribed');
        });
    }
};
