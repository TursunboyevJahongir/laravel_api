<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    private string $tableName = 'resources';

    public function up(): void
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->id();
            $table->string('additional_identifier')->nullable();
            $table->string('type')->nullable();
            $table->string('path_original');
            $table->string('path_1024')->nullable();//if file not image it will be null
            $table->string('path_512')->nullable();
            $table->morphs('resource');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists($this->tableName);
    }
};
