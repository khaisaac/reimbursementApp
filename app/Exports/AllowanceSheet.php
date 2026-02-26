<?php

namespace App\Exports;

use App\Models\Allowance;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class AllowanceSheet implements FromCollection, WithHeadings, WithMapping, WithTitle, ShouldAutoSize
{
    public function __construct(
        private int $month,
        private int $year,
    ) {}

    public function collection()
    {
        return Allowance::with(['user', 'project'])
            ->whereMonth('created_at', $this->month)
            ->whereYear('created_at', $this->year)
            ->get();
    }

    public function headings(): array
    {
        return [
            'Allowance Number',
            'User',
            'Project No',
            'Project Name',
            'Date',
            'Description',
            'Amount (Rp)',
            'Status',
            'Created Date',
        ];
    }

    /** @param Allowance $allowance */
    public function map($allowance): array
    {
        return [
            $allowance->allowance_number,
            $allowance->user->name,
            $allowance->project->project_no,
            $allowance->project->project_name,
            $allowance->date->format('Y-m-d'),
            $allowance->description,
            $allowance->amount,
            $allowance->status_label,
            $allowance->created_at->format('Y-m-d'),
        ];
    }

    public function title(): string
    {
        return 'Allowances';
    }
}
