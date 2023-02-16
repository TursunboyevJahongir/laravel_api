<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->jsonb('title');
        });
    }

    public function down(): void
    {
        Schema::table('roles', function (Blueprint $table) {
        });
    }
};
