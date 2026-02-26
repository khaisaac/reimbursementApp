<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCashAdvanceRequest;
use App\Models\CashAdvance;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class CashAdvanceController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        $cashAdvances = CashAdvance::with(['user', 'project'])
            ->when(! $user->isAdmin() && ! $user->isFinance() && ! $user->isPicProject(), fn ($q) => $q->where('user_id', $user->id))
            ->when($request->status, fn ($q, $s) => $q->where('status', $s))
            ->when($request->search, fn ($q, $s) => $q->where('ca_number', 'like', "%{$s}%"))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('cash-advances.index', compact('cashAdvances'));
    }

    public function create(): View
    {
        $projects = Project::where('status', 'active')->orderBy('project_no')->get();

        return view('cash-advances.create', compact('projects'));
    }

    public function store(StoreCashAdvanceRequest $request): RedirectResponse
    {
        CashAdvance::create([
            'user_id' => $request->user()->id,
            'project_id' => $request->validated('project_id'),
            'description' => $request->validated('description'),
            'amount' => $request->validated('amount'),
            'status' => 'draft',
        ]);

        return redirect()->route('cash-advances.index')->with('success', 'Cash Advance created successfully.');
    }

    public function show(CashAdvance $cashAdvance): View
    {
        $cashAdvance->load(['user', 'project', 'approvedByPic', 'approvedByFinance', 'reimbursements']);

        return view('cash-advances.show', compact('cashAdvance'));
    }

    public function submit(CashAdvance $cashAdvance): RedirectResponse
    {
        if ($cashAdvance->status !== 'draft') {
            return back()->with('error', 'Only draft Cash Advances can be submitted.');
        }

        $cashAdvance->update(['status' => 'submitted']);

        return back()->with('success', 'Cash Advance submitted for approval.');
    }

    public function approve(Request $request, CashAdvance $cashAdvance): RedirectResponse
    {
        $user = $request->user();

        if ($user->isPicProject() && $cashAdvance->status === 'submitted') {
            $cashAdvance->update([
                'status' => 'approved_by_pic',
                'approved_by_pic_id' => $user->id,
                'approved_by_pic_at' => now(),
            ]);

            return back()->with('success', 'Cash Advance approved by PIC.');
        }

        if ($user->isFinance() && $cashAdvance->status === 'approved_by_pic') {
            $validated = $request->validate([
                'transfer_evidence' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
                'transfer_date' => ['required', 'date'],
            ]);

            $path = $request->file('transfer_evidence')->store('transfer-evidence', 'public');

            $cashAdvance->update([
                'status' => 'approved_by_finance',
                'approved_by_finance_id' => $user->id,
                'approved_by_finance_at' => now(),
                'transfer_evidence' => $path,
                'transfer_date' => $validated['transfer_date'],
            ]);

            return back()->with('success', 'Cash Advance approved by Finance with transfer evidence.');
        }

        // Admin can also approve submitted
        if ($user->isAdmin() && $cashAdvance->status === 'submitted') {
            $cashAdvance->update([
                'status' => 'approved_by_pic',
                'approved_by_pic_id' => $user->id,
                'approved_by_pic_at' => now(),
            ]);

            return back()->with('success', 'Cash Advance approved.');
        }

        return back()->with('error', 'You cannot approve this Cash Advance at this stage.');
    }

    public function reject(Request $request, CashAdvance $cashAdvance): RedirectResponse
    {
        $validated = $request->validate([
            'rejection_reason' => ['required', 'string', 'max:500'],
        ]);

        if (! in_array($cashAdvance->status, ['submitted', 'approved_by_pic'])) {
            return back()->with('error', 'This Cash Advance cannot be rejected at this stage.');
        }

        $cashAdvance->update([
            'status' => 'rejected',
            'rejection_reason' => $validated['rejection_reason'],
        ]);

        return back()->with('success', 'Cash Advance rejected.');
    }
}
