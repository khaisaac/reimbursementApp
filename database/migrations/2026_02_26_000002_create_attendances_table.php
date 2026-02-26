<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->string('location_link')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['date', 'user_id', 'project_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
