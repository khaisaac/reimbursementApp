<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cash_advances', function (Blueprint $table) {
            $table->id();
            $table->string('ca_number')->unique();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->text('description');
            $table->decimal('amount', 15, 2);
            $table->decimal('settled_amount', 15, 2)->default(0);
            $table->decimal('outstanding_amount', 15, 2)->default(0);
            $table->enum('status', [
                'draft',
                'submitted',
                'approved_by_pic',
                'approved_by_finance',
                'partial_settlement',
                'fully_settled',
                'rejected',
            ])->default('draft');
            $table->string('transfer_evidence')->nullable();
            $table->date('transfer_date')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->foreignId('approved_by_pic_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_by_pic_at')->nullable();
            $table->foreignId('approved_by_finance_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_by_finance_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cash_advances');
    }
};
