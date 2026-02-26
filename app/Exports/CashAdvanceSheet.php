<?php

namespace App\Exports;

use App\Models\CashAdvance;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class CashAdvanceSheet implements FromCollection, WithHeadings, WithMapping, WithTitle, ShouldAutoSize
{
    public function __construct(
        private int $month,
        private int $year,
    ) {}

    public function collection()
    {
        return CashAdvance::with(['user', 'project'])
            ->whereMonth('created_at', $this->month)
            ->whereYear('created_at', $this->year)
            ->get();
    }

    public function headings(): array
    {
        return [
            'CA Number',
            'User',
            'Project No',
            'Project Name',
            'Description',
            'Amount (Rp)',
            'Settled (Rp)',
            'Outstanding (Rp)',
            'Status',
            'Created Date',
        ];
    }

    /** @param CashAdvance $ca */
    public function map($ca): array
    {
        return [
            $ca->ca_number,
            $ca->user->name,
            $ca->project->project_no,
            $ca->project->project_name,
            $ca->description,
            $ca->amount,
            $ca->settled_amount,
            $ca->outstanding_amount,
            $ca->status_label,
            $ca->created_at->format('Y-m-d'),
        ];
    }

    public function title(): string
    {
        return 'Cash Advances';
    }
}
