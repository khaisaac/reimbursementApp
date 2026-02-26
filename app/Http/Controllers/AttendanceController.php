<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AttendanceController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        $attendances = Attendance::with(['user', 'project'])
            ->when(! $user->isAdmin() && ! $user->isPicProject(), fn ($q) => $q->where('user_id', $user->id))
            ->when($request->date, fn ($q, $d) => $q->whereDate('date', $d))
            ->when($request->project_id, fn ($q, $p) => $q->where('project_id', $p))
            ->latest('date')
            ->paginate(15)
            ->withQueryString();

        $projects = Project::where('status', 'active')->orderBy('project_no')->get();

        return view('attendances.index', compact('attendances', 'projects'));
    }

    public function create(): View
    {
        $projects = Project::where('status', 'active')->orderBy('project_no')->get();

        return view('attendances.create', compact('projects'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'date' => ['required', 'date'],
            'project_id' => ['required', 'exists:projects,id'],
            'location_link' => ['nullable', 'url', 'max:500'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        $validated['user_id'] = $request->user()->id;

        // Check for duplicate
        $exists = Attendance::where('user_id', $validated['user_id'])
            ->where('project_id', $validated['project_id'])
            ->whereDate('date', $validated['date'])
            ->exists();

        if ($exists) {
            return back()->withErrors(['date' => 'Attendance already recorded for this date and project.'])->withInput();
        }

        Attendance::create($validated);

        return redirect()->route('attendances.index')->with('success', 'Attendance recorded successfully.');
    }

    public function destroy(Request $request, Attendance $attendance): RedirectResponse
    {
        $user = $request->user();

        // Only the owner or admin can delete
        if ($attendance->user_id !== $user->id && ! $user->isAdmin()) {
            abort(403, 'Unauthorized.');
        }

        $attendance->delete();

        return redirect()->route('attendances.index')->with('success', 'Attendance deleted.');
    }
}
