<?php

namespace App\Http\Controllers\Author;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Paper;
use App\Models\Review;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\PdfToImage\Pdf; 
use Intervention\Image\Facades\Image; 
class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Get all papers by the logged-in author with eager loaded reviews and reviewer info
        $papers = Paper::with(['reviews.reviewer', 'assignments.reviewer','assignments.review'])
            ->where('author_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($paper) {
                // Add file type information to each paper
                $paper->file_type = pathinfo($paper->paper_file, PATHINFO_EXTENSION);
                return $paper;
            });

        return view('author.dashboard', compact('papers'));
    }

    
    public function submitPaper(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'abstract' => 'required|string',
            'paper_file' => 'required|file|mimes:pdf,docx|max:10240',
        ]);
    
        try {
            $file = $request->file('paper_file');
            $fileName = Str::slug($request->title) . '_' . time() . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('papers', $fileName, 'public');
            
            Paper::create([
                'author_id' => auth()->id(),
                'title' => $request->title,
                'abstract' => $request->abstract,
                'file_path' => $filePath,
                'status' => 'submitted',
            ]);
    
            return redirect()->back()->with('success', 'Paper submitted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Failed to submit paper. Please try again.']);
        }
    }



    public function removePaper(Request $request, $paperId)
    {
        $paper = Paper::where('author_id', auth()->id())
            ->findOrFail($paperId);

        try {
            // Delete the file from storage
            Storage::disk('public')->delete($paper->file_path);
            
            // Delete the paper record
            $paper->delete();

            return redirect()->back()->with('success', 'Paper removed successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Failed to remove paper. Please try again.']);
        }
    }
}