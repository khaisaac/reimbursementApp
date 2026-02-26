<?php

namespace App\Http\Controllers;

use App\Exports\MonthlyReportExport;
use App\Models\Allowance;
use App\Models\CashAdvance;
use App\Models\Reimbursement;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        $month = $request->input('month', now()->month);
        $year = $request->input('year', now()->year);
        $type = $request->input('type', 'all');

        $data = null;

        if ($request->has('month')) {
            $data = $this->getReportData($month, $year, $type);
        }

        return view('reports.index', compact('data', 'month', 'year', 'type'));
    }

    public function export(Request $request): BinaryFileResponse
    {
        $month = $request->input('month', now()->month);
        $year = $request->input('year', now()->year);
        $type = $request->input('type', 'all');

        $filename = "HDS_Report_{$year}_{$month}_{$type}.xlsx";

        return Excel::download(new MonthlyReportExport($month, $year, $type), $filename);
    }

    private function getReportData(int $month, int $year, string $type): array
    {
        $data = [];

        if ($type === 'all' || $type === 'cash_advance') {
            $data['cash_advances'] = CashAdvance::with(['user', 'project'])
                ->whereMonth('created_at', $month)
                ->whereYear('created_at', $year)
                ->get();
        }

        if ($type === 'all' || $type === 'allowance') {
            $data['allowances'] = Allowance::with(['user', 'project'])
                ->whereMonth('created_at', $month)
                ->whereYear('created_at', $year)
                ->get();
        }

        if ($type === 'all' || $type === 'reimbursement') {
            $data['reimbursements'] = Reimbursement::with(['user', 'project', 'items'])
                ->whereMonth('created_at', $month)
                ->whereYear('created_at', $year)
                ->get();
        }

        return $data;
    }
}
