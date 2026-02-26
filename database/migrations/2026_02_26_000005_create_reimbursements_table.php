<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reimbursements', function (Blueprint $table) {
            $table->id();
            $table->string('reimbursement_number')->unique();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['direct_claim', 'ca_settlement'])->default('direct_claim');
            $table->text('description')->nullable();
            $table->decimal('total_amount', 15, 2)->default(0);
            $table->enum('status', [
                'draft',
                'submitted',
                'approved_by_pic',
                'approved_by_finance',
                'rejected',
                'paid',
            ])->default('draft');
            $table->text('rejection_reason')->nullable();
            $table->foreignId('approved_by_pic_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_by_pic_at')->nullable();
            $table->foreignId('approved_by_finance_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_by_finance_at')->nullable();
            $table->string('payment_evidence')->nullable();
            $table->timestamps();
        });

        // Pivot table for reimbursement <-> cash_advance (settlement linking)
        Schema::create('cash_advance_reimbursement', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reimbursement_id')->constrained()->cascadeOnDelete();
            $table->foreignId('cash_advance_id')->constrained()->cascadeOnDelete();
            $table->decimal('settled_amount', 15, 2)->default(0);
            $table->timestamps();
        });

        // Reimbursement line items
        Schema::create('reimbursement_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reimbursement_id')->constrained()->cascadeOnDelete();
            $table->date('date');
            $table->enum('category', [
                'transport',
                'konsumsi',
                'perjadin',
                'akomodasi',
                'entertain',
                'material',
                'ekspedisi',
                'ipl',
                'komunikasi',
                'atk',
                'safety',
                'pemeliharaan',
            ]);
            $table->text('description');
            $table->decimal('amount', 15, 2);
            $table->string('receipt')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reimbursement_items');
        Schema::dropIfExists('cash_advance_reimbursement');
        Schema::dropIfExists('reimbursements');
    }
};
