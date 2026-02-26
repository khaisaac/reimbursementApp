<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('allowances', function (Blueprint $table) {
            $table->id();
            $table->string('allowance_number')->unique();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->date('date');
            $table->text('description')->nullable();
            $table->decimal('amount', 15, 2);
            $table->enum('status', [
                'draft',
                'submitted',
                'approved_by_admin',
                'approved_by_pic',
                'approved_by_finance',
                'rejected',
            ])->default('draft');
            $table->string('receipt')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->foreignId('approved_by_admin_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_by_admin_at')->nullable();
            $table->foreignId('approved_by_pic_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_by_pic_at')->nullable();
            $table->foreignId('approved_by_finance_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_by_finance_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('allowances');
    }
};
