<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class CashAdvance extends Model
{
    use HasFactory;

    protected $fillable = [
        'ca_number',
        'user_id',
        'project_id',
        'description',
        'amount',
        'settled_amount',
        'outstanding_amount',
        'status',
        'transfer_evidence',
        'transfer_date',
        'rejection_reason',
        'approved_by_pic_id',
        'approved_by_pic_at',
        'approved_by_finance_id',
        'approved_by_finance_at',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'settled_amount' => 'decimal:2',
            'outstanding_amount' => 'decimal:2',
            'transfer_date' => 'date',
            'approved_by_pic_at' => 'datetime',
            'approved_by_finance_at' => 'datetime',
        ];
    }

    // ── Boot ─────────────────────────────────────────────────────

    protected static function booted(): void
    {
        static::creating(function (CashAdvance $ca) {
            if (empty($ca->ca_number)) {
                $ca->ca_number = self::generateNumber();
            }
            $ca->outstanding_amount = $ca->amount;
        });
    }

    public static function generateNumber(): string
    {
        $prefix = 'CA-' . date('Ym');
        $last = self::where('ca_number', 'like', $prefix . '%')
            ->orderByDesc('ca_number')
            ->value('ca_number');

        $sequence = $last ? ((int) substr($last, -4)) + 1 : 1;

        return $prefix . '-' . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }

    // ── Relationships ────────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function approvedByPic(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by_pic_id');
    }

    public function approvedByFinance(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by_finance_id');
    }

    public function reimbursements(): BelongsToMany
    {
        return $this->belongsToMany(Reimbursement::class, 'cash_advance_reimbursement')
            ->withPivot('settled_amount')
            ->withTimestamps();
    }

    // ── Business Logic ───────────────────────────────────────────

    /**
     * Recalculate the settled and outstanding amounts from linked reimbursements.
     */
    public function recalculateSettlement(): void
    {
        $settled = $this->reimbursements()
            ->wherePivotNotNull('settled_amount')
            ->sum('cash_advance_reimbursement.settled_amount');

        $this->settled_amount = $settled;
        $this->outstanding_amount = max(0, $this->amount - $settled);

        if ($this->outstanding_amount <= 0) {
            $this->status = 'fully_settled';
        } elseif ($settled > 0) {
            $this->status = 'partial_settlement';
        }

        $this->save();
    }

    // ── Status helpers ───────────────────────────────────────────

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'draft' => 'bg-gray-100 text-gray-800',
            'submitted' => 'bg-blue-100 text-blue-800',
            'approved_by_pic' => 'bg-indigo-100 text-indigo-800',
            'approved_by_finance' => 'bg-green-100 text-green-800',
            'partial_settlement' => 'bg-yellow-100 text-yellow-800',
            'fully_settled' => 'bg-emerald-100 text-emerald-800',
            'rejected' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'draft' => 'Draft',
            'submitted' => 'Submitted',
            'approved_by_pic' => 'Approved by PIC',
            'approved_by_finance' => 'Approved by Finance',
            'partial_settlement' => 'Partial Settlement',
            'fully_settled' => 'Fully Settled',
            'rejected' => 'Rejected',
            default => ucfirst($this->status),
        };
    }
}
