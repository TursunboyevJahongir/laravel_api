<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('logger', function (Blueprint $table) {
            $table->id();
            $table->string('ip');
            $table->text('user_agent');
            $table->foreignId('user_id')
                ->nullable()->constrained()
                ->cascadeOnDelete()->cascadeOnUpdate();
            $table->json('action');
            $table->string('uri');
            $table->string('method');
            $table->json('headers');
            $table->json('payload')->nullable();
            $table->json('response')->nullable();
            $table->integer('response_status')->nullable();
            $table->text('response_message')->nullable();
            $table->dateTime('date')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('logger');
    }
};
