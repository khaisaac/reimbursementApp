<?php

namespace App\Exports;

use App\Models\Allowance;
use App\Models\CashAdvance;
use App\Models\Reimbursement;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class MonthlyReportExport implements WithMultipleSheets
{
    use Exportable;

    public function __construct(
        private int $month,
        private int $year,
        private string $type = 'all',
    ) {}

    public function sheets(): array
    {
        $sheets = [];

        if ($this->type === 'all' || $this->type === 'cash_advance') {
            $sheets[] = new CashAdvanceSheet($this->month, $this->year);
        }

        if ($this->type === 'all' || $this->type === 'allowance') {
            $sheets[] = new AllowanceSheet($this->month, $this->year);
        }

        if ($this->type === 'all' || $this->type === 'reimbursement') {
            $sheets[] = new ReimbursementSheet($this->month, $this->year);
        }

        return $sheets;
    }
}
