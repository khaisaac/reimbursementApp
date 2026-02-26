<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'position',
        'role',
        'bank_name',
        'account_number',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // ── Role Checks ──────────────────────────────────────────────

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isPicProject(): bool
    {
        return $this->role === 'pic_project';
    }

    public function isFinance(): bool
    {
        return $this->role === 'finance';
    }

    public function isUser(): bool
    {
        return $this->role === 'user';
    }

    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    // ── Relationships ────────────────────────────────────────────

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    public function cashAdvances(): HasMany
    {
        return $this->hasMany(CashAdvance::class);
    }

    public function allowances(): HasMany
    {
        return $this->hasMany(Allowance::class);
    }

    public function reimbursements(): HasMany
    {
        return $this->hasMany(Reimbursement::class);
    }

    // ── Business Logic ───────────────────────────────────────────

    /**
     * Total outstanding amount from all cash advances.
     */
    public function totalOutstandingCashAdvance(): float
    {
        return (float) $this->cashAdvances()
            ->whereNotIn('status', ['draft', 'rejected', 'fully_settled'])
            ->sum('outstanding_amount');
    }

    /**
     * Check whether the user can create a new CA with the given amount.
     * Limit: Rp 15,000,000 total outstanding.
     */
    public function canCreateCashAdvance(float $amount = 0): bool
    {
        return ($this->totalOutstandingCashAdvance() + $amount) <= 15_000_000;
    }
}
