<?php


namespace App\Http\Controllers;

use App\Models\Paper;
use Illuminate\Support\Str;  
use Illuminate\Support\Facades\Storage;

class PaperController extends Controller
{
    public function acceptedPapers()
    {
        $acceptedPapers = Paper::where('status', 'Under Review')
                              ->with(['author', 'reviews']) // Eager load reviews
                              ->withAvg('reviews', 'score') // Calculate average score
                              ->orderBy('created_at', 'desc')
                              ->paginate(10);
        
        return view('accepted-papers', compact('acceptedPapers'));
    }
    public function download(Paper $paper)
{
    // Check if file exists and user has permission to download
    if (!Storage::disk('public')->exists($paper->file_path)) {
        abort(404, 'File not found');
    }
    return Storage::disk('public')->download(
        $paper->file_path,
        Str::slug($paper->title) . '.' . pathinfo($paper->file_path, PATHINFO_EXTENSION)
    );
}
}