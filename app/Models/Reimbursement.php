<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Reimbursement extends Model
{
    use HasFactory;

    public const CATEGORIES = [
        'transport' => 'Transport',
        'konsumsi' => 'Konsumsi',
        'perjadin' => 'Perjadin',
        'akomodasi' => 'Akomodasi',
        'entertain' => 'Entertain',
        'material' => 'Material',
        'ekspedisi' => 'Ekspedisi',
        'ipl' => 'IPL',
        'komunikasi' => 'Komunikasi',
        'atk' => 'ATK',
        'safety' => 'Safety',
        'pemeliharaan' => 'Pemeliharaan',
    ];

    protected $fillable = [
        'reimbursement_number',
        'user_id',
        'project_id',
        'type',
        'description',
        'total_amount',
        'status',
        'rejection_reason',
        'approved_by_pic_id',
        'approved_by_pic_at',
        'approved_by_finance_id',
        'approved_by_finance_at',
        'payment_evidence',
    ];

    protected function casts(): array
    {
        return [
            'total_amount' => 'decimal:2',
            'approved_by_pic_at' => 'datetime',
            'approved_by_finance_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Reimbursement $r) {
            if (empty($r->reimbursement_number)) {
                $r->reimbursement_number = self::generateNumber();
            }
        });
    }

    public static function generateNumber(): string
    {
        $prefix = 'RMB-' . date('Ym');
        $last = self::where('reimbursement_number', 'like', $prefix . '%')
            ->orderByDesc('reimbursement_number')
            ->value('reimbursement_number');

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

    public function items(): HasMany
    {
        return $this->hasMany(ReimbursementItem::class);
    }

    public function cashAdvances(): BelongsToMany
    {
        return $this->belongsToMany(CashAdvance::class, 'cash_advance_reimbursement')
            ->withPivot('settled_amount')
            ->withTimestamps();
    }

    // ── Business Logic ───────────────────────────────────────────

    /**
     * Recalculate total_amount from items.
     */
    public function recalculateTotal(): void
    {
        $this->total_amount = $this->items()->sum('amount');
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
            'rejected' => 'bg-red-100 text-red-800',
            'paid' => 'bg-emerald-100 text-emerald-800',
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
            'rejected' => 'Rejected',
            'paid' => 'Paid',
            default => ucfirst($this->status),
        };
    }
}
