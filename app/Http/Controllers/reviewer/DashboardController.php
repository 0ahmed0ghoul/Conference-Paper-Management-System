<?php

namespace App\Http\Controllers\Reviewer;

use App\Http\Controllers\Controller;
use App\Models\PaperAssignment;
use App\Models\Review;
use App\Models\Paper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
    
        $assignments = PaperAssignment::with(['paper.author', 'review'])
            ->where('reviewer_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();
    
        return view('reviewer.dashboard', [
            'assignments' => $assignments,
        ]);
    }
    public function showReviewForm($assignmentId)
    {
        $assignment = PaperAssignment::with('paper')
            ->where('id', $assignmentId)
            ->where('reviewer_id', Auth::id())
            ->where('status', 'accepted') // Only show form for accepted assignments
            ->firstOrFail();

        return view('reviews.form', compact('assignment'));
    }

    public function accept($assignmentId)
    {
        $assignment = PaperAssignment::where('id', $assignmentId)
            ->where('reviewer_id', Auth::id())
            ->where('status', 'pending') // Only accept pending assignments
            ->firstOrFail();
    
        $assignment->status = 'accepted';
        $assignment->save();
    
        return redirect()->route('reviewer.dashboard')->with('success', 'Assignment accepted.');
    }
    
    public function reject($assignmentId)
    {
        $assignment = PaperAssignment::where('id', $assignmentId)
            ->where('reviewer_id', Auth::id())
            ->where('status', 'pending') // Only reject pending assignments
            ->firstOrFail();
    
        $assignment->status = 'rejected';
        $assignment->save();
    
        return redirect()->route('reviewer.dashboard')->with('warning', 'Assignment rejected.');
    }
    
    public function submitReview(Request $request, $assignmentId)
    {
        $request->validate([
            'score' => 'required|integer|min:1|max:5',
            'comments' => 'required|string|min:20',
        ]);
    
        $assignment = PaperAssignment::where([
            'id' => $assignmentId,
            'reviewer_id' => auth()->id(),
            'status' => 'accepted'
        ])->firstOrFail();
    
        // Create review
        Review::create([
            'paper_id' => $assignment->paper_id,
            'reviewer_id' => auth()->id(),
            'assignment_id' => $assignment->id,
            'score' => $request->score,
            'comments' => $request->comments,
            'submitted_at' => now()
        ]);
    
        $assignment->update(['status' => 'completed']);
    
        return back()->with('success', 'Review submitted successfully');
    }

    public function showPreviousReviews()
    {
        $reviews = Review::with(['paper', 'assignment'])
            ->where('reviewer_id', Auth::id())
            ->orderBy('submitted_at', 'desc')
            ->get();

        return view('reviewer.reviews', compact('reviews'));
    }
}