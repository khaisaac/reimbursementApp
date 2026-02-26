<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReimbursementItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'reimbursement_id',
        'date',
        'category',
        'description',
        'amount',
        'receipt',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'amount' => 'decimal:2',
        ];
    }

    public function reimbursement(): BelongsTo
    {
        return $this->belongsTo(Reimbursement::class);
    }

    public function getCategoryLabelAttribute(): string
    {
        return Reimbursement::CATEGORIES[$this->category] ?? ucfirst($this->category);
    }
}
