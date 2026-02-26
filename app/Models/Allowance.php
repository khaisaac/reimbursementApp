<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Allowance extends Model
{
    use HasFactory;

    protected $fillable = [
        'allowance_number',
        'user_id',
        'project_id',
        'date',
        'description',
        'amount',
        'status',
        'receipt',
        'rejection_reason',
        'approved_by_admin_id',
        'approved_by_admin_at',
        'approved_by_pic_id',
        'approved_by_pic_at',
        'approved_by_finance_id',
        'approved_by_finance_at',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'amount' => 'decimal:2',
            'approved_by_admin_at' => 'datetime',
            'approved_by_pic_at' => 'datetime',
            'approved_by_finance_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Allowance $allowance) {
            if (empty($allowance->allowance_number)) {
                $allowance->allowance_number = self::generateNumber();
            }
        });
    }

    public static function generateNumber(): string
    {
        $prefix = 'ALW-' . date('Ym');
        $last = self::where('allowance_number', 'like', $prefix . '%')
            ->orderByDesc('allowance_number')
            ->value('allowance_number');

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

    public function approvedByAdmin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by_admin_id');
    }

    public function approvedByPic(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by_pic_id');
    }

    public function approvedByFinance(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by_finance_id');
    }

    // ── Validation Logic ─────────────────────────────────────────

    /**
     * Check if there is a matching attendance record for this allowance.
     */
    public function hasMatchingAttendance(): bool
    {
        return Attendance::where('user_id', $this->user_id)
            ->where('project_id', $this->project_id)
            ->whereDate('date', $this->date)
            ->exists();
    }

    // ── Status helpers ───────────────────────────────────────────

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'draft' => 'bg-gray-100 text-gray-800',
            'submitted' => 'bg-blue-100 text-blue-800',
            'approved_by_admin' => 'bg-indigo-100 text-indigo-800',
            'approved_by_pic' => 'bg-purple-100 text-purple-800',
            'approved_by_finance' => 'bg-green-100 text-green-800',
            'rejected' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'draft' => 'Draft',
            'submitted' => 'Submitted',
            'approved_by_admin' => 'Approved by Admin',
            'approved_by_pic' => 'Approved by PIC',
            'approved_by_finance' => 'Approved by Finance',
            'rejected' => 'Rejected',
            default => ucfirst($this->status),
        };
    }
}
