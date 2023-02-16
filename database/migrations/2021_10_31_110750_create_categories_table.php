<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    private string $tableName = 'categories';

    public function up(): void
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->id();
            $table->jsonb('name');
            $table->jsonb('description')->nullable();
            $table->unsignedBigInteger('position')->default(0);
            $table->boolean('is_active')->default(true);
            $table->foreignId('author_id')->constrained('users');
            $table->foreignId('parent_id')->nullable()->constrained('categories');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists($this->tableName);
    }
};
