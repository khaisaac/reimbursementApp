<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReimbursementRequest;
use App\Models\CashAdvance;
use App\Models\Project;
use App\Models\Reimbursement;
use App\Models\ReimbursementItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ReimbursementController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        $reimbursements = Reimbursement::with(['user', 'project'])
            ->when(! $user->isAdmin() && ! $user->isFinance() && ! $user->isPicProject(), fn ($q) => $q->where('user_id', $user->id))
            ->when($request->status, fn ($q, $s) => $q->where('status', $s))
            ->when($request->search, fn ($q, $s) => $q->where('reimbursement_number', 'like', "%{$s}%"))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('reimbursements.index', compact('reimbursements'));
    }

    public function create(Request $request): View
    {
        $projects = Project::where('status', 'active')->orderBy('project_no')->get();

        // Outstanding cash advances for settlement
        $outstandingCas = CashAdvance::where('user_id', $request->user()->id)
            ->whereIn('status', ['approved_by_finance', 'partial_settlement'])
            ->where('outstanding_amount', '>', 0)
            ->get();

        $categories = Reimbursement::CATEGORIES;

        return view('reimbursements.create', compact('projects', 'outstandingCas', 'categories'));
    }

    public function store(StoreReimbursementRequest $request): RedirectResponse
    {
        DB::transaction(function () use ($request) {
            $reimbursement = Reimbursement::create([
                'user_id' => $request->user()->id,
                'project_id' => $request->validated('project_id'),
                'type' => $request->validated('type'),
                'description' => $request->validated('description'),
                'status' => 'draft',
            ]);

            // Create line items
            $total = 0;
            foreach ($request->validated('items') as $index => $item) {
                $receiptPath = null;
                if ($request->hasFile("items.{$index}.receipt")) {
                    $receiptPath = $request->file("items.{$index}.receipt")->store('reimbursement-receipts', 'public');
                }

                ReimbursementItem::create([
                    'reimbursement_id' => $reimbursement->id,
                    'date' => $item['date'],
                    'category' => $item['category'],
                    'description' => $item['description'],
                    'amount' => $item['amount'],
                    'receipt' => $receiptPath,
                ]);

                $total += (float) $item['amount'];
            }

            $reimbursement->update(['total_amount' => $total]);

            // Link cash advances for settlement
            if ($request->validated('type') === 'ca_settlement' && $request->has('cash_advance_ids')) {
                $remainingAmount = $total;

                foreach ($request->input('cash_advance_ids', []) as $caId) {
                    if ($remainingAmount <= 0) {
                        break;
                    }

                    $ca = CashAdvance::find($caId);
                    if (! $ca) {
                        continue;
                    }

                    $settleAmount = min($remainingAmount, (float) $ca->outstanding_amount);

                    $reimbursement->cashAdvances()->attach($caId, [
                        'settled_amount' => $settleAmount,
                    ]);

                    $remainingAmount -= $settleAmount;

                    // Recalculate CA settlement
                    $ca->recalculateSettlement();
                }
            }
        });

        return redirect()->route('reimbursements.index')->with('success', 'Reimbursement created successfully.');
    }

    public function show(Reimbursement $reimbursement): View
    {
        $reimbursement->load(['user', 'project', 'items', 'cashAdvances', 'approvedByPic', 'approvedByFinance']);

        return view('reimbursements.show', compact('reimbursement'));
    }

    public function submit(Reimbursement $reimbursement): RedirectResponse
    {
        if ($reimbursement->status !== 'draft') {
            return back()->with('error', 'Only draft reimbursements can be submitted.');
        }

        $reimbursement->update(['status' => 'submitted']);

        return back()->with('success', 'Reimbursement submitted for approval.');
    }

    public function approve(Request $request, Reimbursement $reimbursement): RedirectResponse
    {
        $user = $request->user();

        if (($user->isPicProject() || $user->isAdmin()) && $reimbursement->status === 'submitted') {
            $reimbursement->update([
                'status' => 'approved_by_pic',
                'approved_by_pic_id' => $user->id,
                'approved_by_pic_at' => now(),
            ]);

            return back()->with('success', 'Reimbursement approved by PIC.');
        }

        if ($user->isFinance() && $reimbursement->status === 'approved_by_pic') {
            $reimbursement->update([
                'status' => 'approved_by_finance',
                'approved_by_finance_id' => $user->id,
                'approved_by_finance_at' => now(),
            ]);

            return back()->with('success', 'Reimbursement approved by Finance.');
        }

        return back()->with('error', 'You cannot approve this reimbursement at this stage.');
    }

    public function reject(Request $request, Reimbursement $reimbursement): RedirectResponse
    {
        $validated = $request->validate([
            'rejection_reason' => ['required', 'string', 'max:500'],
        ]);

        if (in_array($reimbursement->status, ['draft', 'approved_by_finance', 'rejected', 'paid'])) {
            return back()->with('error', 'This reimbursement cannot be rejected at this stage.');
        }

        $reimbursement->update([
            'status' => 'rejected',
            'rejection_reason' => $validated['rejection_reason'],
        ]);

        return back()->with('success', 'Reimbursement rejected.');
    }
}
