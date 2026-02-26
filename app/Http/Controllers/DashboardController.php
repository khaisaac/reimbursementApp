<?php

namespace App\Http\Controllers;

use App\Models\Allowance;
use App\Models\CashAdvance;
use App\Models\Reimbursement;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        // Stats
        $totalCa = CashAdvance::when(! $user->isAdmin() && ! $user->isFinance(), fn ($q) => $q->where('user_id', $user->id))->count();
        $pendingCa = CashAdvance::when(! $user->isAdmin() && ! $user->isFinance(), fn ($q) => $q->where('user_id', $user->id))
            ->whereIn('status', ['submitted', 'approved_by_pic'])->count();
        $totalReimbursements = Reimbursement::when(! $user->isAdmin() && ! $user->isFinance(), fn ($q) => $q->where('user_id', $user->id))->count();
        $pendingReimbursements = Reimbursement::when(! $user->isAdmin() && ! $user->isFinance(), fn ($q) => $q->where('user_id', $user->id))
            ->whereIn('status', ['submitted', 'approved_by_pic'])->count();
        $totalAllowances = Allowance::when(! $user->isAdmin() && ! $user->isFinance(), fn ($q) => $q->where('user_id', $user->id))->count();
        $outstandingAmount = $user->isAdmin() || $user->isFinance()
            ? CashAdvance::whereNotIn('status', ['draft', 'rejected', 'fully_settled'])->sum('outstanding_amount')
            : $user->totalOutstandingCashAdvance();

        // Recent items
        $recentCa = CashAdvance::with(['user', 'project'])
            ->when(! $user->isAdmin() && ! $user->isFinance() && ! $user->isPicProject(), fn ($q) => $q->where('user_id', $user->id))
            ->latest()->take(5)->get();

        $recentReimbursements = Reimbursement::with(['user', 'project'])
            ->when(! $user->isAdmin() && ! $user->isFinance() && ! $user->isPicProject(), fn ($q) => $q->where('user_id', $user->id))
            ->latest()->take(5)->get();

        return view('dashboard', compact(
            'totalCa', 'pendingCa', 'totalReimbursements', 'pendingReimbursements',
            'totalAllowances', 'outstandingAmount', 'recentCa', 'recentReimbursements'
        ));
    }
}
