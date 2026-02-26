<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAllowanceRequest;
use App\Models\Allowance;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AllowanceController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        $allowances = Allowance::with(['user', 'project'])
            ->when(! $user->isAdmin() && ! $user->isFinance() && ! $user->isPicProject(), fn ($q) => $q->where('user_id', $user->id))
            ->when($request->status, fn ($q, $s) => $q->where('status', $s))
            ->when($request->search, fn ($q, $s) => $q->where('allowance_number', 'like', "%{$s}%"))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('allowances.index', compact('allowances'));
    }

    public function create(): View
    {
        $projects = Project::where('status', 'active')->orderBy('project_no')->get();

        return view('allowances.create', compact('projects'));
    }

    public function store(StoreAllowanceRequest $request): RedirectResponse
    {
        $data = [
            'user_id' => $request->user()->id,
            'project_id' => $request->validated('project_id'),
            'date' => $request->validated('date'),
            'description' => $request->validated('description'),
            'amount' => $request->validated('amount'),
            'status' => 'draft',
        ];

        if ($request->hasFile('receipt')) {
            $data['receipt'] = $request->file('receipt')->store('allowance-receipts', 'public');
        }

        Allowance::create($data);

        return redirect()->route('allowances.index')->with('success', 'Allowance claim created successfully.');
    }

    public function show(Allowance $allowance): View
    {
        $allowance->load(['user', 'project', 'approvedByAdmin', 'approvedByPic', 'approvedByFinance']);

        return view('allowances.show', compact('allowance'));
    }

    public function submit(Allowance $allowance): RedirectResponse
    {
        if ($allowance->status !== 'draft') {
            return back()->with('error', 'Only draft allowances can be submitted.');
        }

        $allowance->update(['status' => 'submitted']);

        return back()->with('success', 'Allowance submitted for approval.');
    }

    public function approve(Request $request, Allowance $allowance): RedirectResponse
    {
        $user = $request->user();

        // Admin approves submitted allowances
        if ($user->isAdmin() && $allowance->status === 'submitted') {
            $allowance->update([
                'status' => 'approved_by_admin',
                'approved_by_admin_id' => $user->id,
                'approved_by_admin_at' => now(),
            ]);

            return back()->with('success', 'Allowance approved by Admin.');
        }

        // PIC approves after admin
        if ($user->isPicProject() && $allowance->status === 'approved_by_admin') {
            $allowance->update([
                'status' => 'approved_by_pic',
                'approved_by_pic_id' => $user->id,
                'approved_by_pic_at' => now(),
            ]);

            return back()->with('success', 'Allowance approved by PIC.');
        }

        // Finance approves after PIC
        if ($user->isFinance() && $allowance->status === 'approved_by_pic') {
            $allowance->update([
                'status' => 'approved_by_finance',
                'approved_by_finance_id' => $user->id,
                'approved_by_finance_at' => now(),
            ]);

            return back()->with('success', 'Allowance approved by Finance.');
        }

        return back()->with('error', 'You cannot approve this allowance at this stage.');
    }

    public function reject(Request $request, Allowance $allowance): RedirectResponse
    {
        $validated = $request->validate([
            'rejection_reason' => ['required', 'string', 'max:500'],
        ]);

        if (in_array($allowance->status, ['draft', 'approved_by_finance', 'rejected'])) {
            return back()->with('error', 'This allowance cannot be rejected at this stage.');
        }

        $allowance->update([
            'status' => 'rejected',
            'rejection_reason' => $validated['rejection_reason'],
        ]);

        return back()->with('success', 'Allowance rejected.');
    }
}
