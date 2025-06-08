<?php

namespace App\Http\Controllers\Chair;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PaperAssignment;
use App\Models\Paper;
use App\Models\User;
use App\Models\Review;

class DashboardController extends Controller
{
    protected function isSuperChair()
    {
        $user = auth()->user();
        if (!$user) {
            return false;
        }

        $superChairEmails = explode(',', env('SUPER_CHAIR_EMAIL', ''));
        
        return in_array($user->email, $superChairEmails);
    }

    public function index(Request $request)
    {
        $isSuperChair = $this->isSuperChair();

        // Handle form submissions
        if ($request->isMethod('post') && $request->has('action')) {
            if ($request->action === 'assign') {
                return $this->handleAssignment($request);
            }
        }
        
        $reviews = Review::with(['paper', 'reviewer', 'assignment'])
            ->orderBy('created_at', 'desc')
            ->get();

        $papersQuery = Paper::with(['author', 'reviewers', 'reviews']);
        $papersQuery->orderByRaw("CASE WHEN NOT EXISTS (SELECT * FROM paper_assignments WHERE paper_assignments.paper_id = papers.id) THEN 0 ELSE 1 END");

        $papers = $papersQuery->paginate(10);
    
        $reviewers = User::where('role', 'reviewer')
            ->withCount(['assignedPapers as pending_assignments' => function ($q) {
                $q->where('review_assignments.status', 'assigned');
            }])
            ->orderBy('pending_assignments')
            ->get();
    
        if ($isSuperChair) {
            $pendingUsers = User::whereIn('role', ['reviewer', 'chair'])
                ->where('status', 'pending')
                ->get();
    
            return view('chair.dashboard', compact('papers', 'reviewers', 'reviews', 'pendingUsers', 'isSuperChair'));
        }
        
        return view('chair.dashboard', compact('papers', 'reviewers', 'isSuperChair'));
    }

    protected function handleAssignment(Request $request)
    {
        $request->validate([
            'reviewer_id' => 'required|exists:users,id',
            'paper_id' => 'required|exists:papers,id',
        ]);
    
        $reviewer = User::find($request->reviewer_id);
        if ($reviewer->role !== 'reviewer') {
            return back()->with('error', 'User is not a reviewer');
        }
    
        $exists = PaperAssignment::where([
            'paper_id' => $request->paper_id,
            'reviewer_id' => $request->reviewer_id,
        ])->exists();
    
        if ($exists) {
            return back()->with('error', 'This reviewer is already assigned to the paper');
        }
    
        PaperAssignment::create([
            'reviewer_id' => $request->reviewer_id,
            'paper_id' => $request->paper_id,
            'assigned_by' => auth()->id(),
            'status' => 'pending',
            'assigned_at' => now(),
        ]);
    
        // Only update paper status if it's currently pending
        Paper::where('id', $request->paper_id)
            ->where('status', 'submitted')
            ->update(['status' => 'Under Review']);
    
        return back()->with('success', 'Reviewer assigned successfully');
    }
    
    public function approve($userId)
    {
        if (!$this->isSuperChair()) {
            abort(403, 'Unauthorized: Only super chair can approve users.');
        }

        $user = User::findOrFail($userId);

        if (!in_array($user->role, ['reviewer', 'chair'])) {
            return redirect()->back()->with('error', 'Invalid user role.');
        }

        $user->status = 'approved';
        $user->save();

        return redirect()->back()->with('success', 'User approved.');
    }
}