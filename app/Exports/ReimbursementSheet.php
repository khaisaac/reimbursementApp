<?php

namespace App\Exports;

use App\Models\Reimbursement;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class ReimbursementSheet implements FromCollection, WithHeadings, WithMapping, WithTitle, ShouldAutoSize
{
    public function __construct(
        private int $month,
        private int $year,
    ) {}

    public function collection()
    {
        return Reimbursement::with(['user', 'project', 'items'])
            ->whereMonth('created_at', $this->month)
            ->whereYear('created_at', $this->year)
            ->get();
    }

    public function headings(): array
    {
        return [
            'Reimbursement Number',
            'User',
            'Project No',
            'Project Name',
            'Type',
            'Description',
            'Total Amount (Rp)',
            'Item Count',
            'Status',
            'Created Date',
        ];
    }

    /** @param Reimbursement $reimbursement */
    public function map($reimbursement): array
    {
        return [
            $reimbursement->reimbursement_number,
            $reimbursement->user->name,
            $reimbursement->project->project_no,
            $reimbursement->project->project_name,
            $reimbursement->type === 'ca_settlement' ? 'CA Settlement' : 'Direct Claim',
            $reimbursement->description,
            $reimbursement->total_amount,
            $reimbursement->items->count(),
            $reimbursement->status_label,
            $reimbursement->created_at->format('Y-m-d'),
        ];
    }

    public function title(): string
    {
        return 'Reimbursements';
    }
}
