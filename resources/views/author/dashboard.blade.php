@extends('layouts.app')

@section('title', 'Author Dashboard')

@section('content')
<div class="container mt-5">
    {{-- Paper Submission Form --}}
    <div class="card mb-5">
        <div class="card-header bg-primary text-white">
            <h2 class="mb-0">Submit New Paper</h2>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('submitPaper') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="title" class="form-label">Title *</label>
                    <input type="text" name="title" id="title" class="form-control" value="{{ old('title') }}" required>
                </div>

                <div class="mb-3">
                    <label for="abstract" class="form-label">Abstract *</label>
                    <textarea name="abstract" id="abstract" class="form-control" rows="4" required>{{ old('abstract') }}</textarea>
                </div>

                <div class="mb-3">
                    <label for="paper_file" class="form-label">Upload Paper (PDF or DOCX) *</label>
                    <input type="file" name="paper_file" id="paper_file" class="form-control" accept=".pdf,.docx" required>
                </div>

                <button type="submit" class="btn btn-primary">Submit Paper</button>
            </form>
        </div>
    </div>

    {{-- Submitted Papers Section --}}
    <h2 class="mb-4">Your Submitted Papers</h2>
    
    @if($papers->isEmpty())
        <div class="alert alert-info">No papers submitted yet.</div>
    @else
        <div class="row">
            @foreach($papers as $paper)
                @php
                    // Get rejected assignments
                    $rejectedAssignments = $paper->assignments()->where('status', 'rejected')->get();
                    $hasRejection = $rejectedAssignments->isNotEmpty();
                    
                    // Get all reviews including those from rejected assignments
                    $allReviews = $paper->reviews->merge(
                        $paper->assignments->flatMap->review
                    )->filter();
                @endphp
                
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100 @if($hasRejection) border-danger border-2 @endif">
                        {{-- Paper Preview --}}
                        <div class="card-img-top bg-light text-center p-3" style="height: 200px; overflow: hidden;">
                            @if($paper->thumbnail_path && Storage::disk('public')->exists($paper->thumbnail_path))
                                <img src="{{ Storage::url($paper->thumbnail_path) }}" 
                                    alt="{{ $paper->title }} thumbnail" 
                                    style="width: 100%; height: 100%; object-fit: contain;">
                            @else
                                <div class="d-flex align-items-center justify-content-center h-100">
                                    <i class="fas fa-file-alt fa-4x text-secondary"></i>
                                </div>
                            @endif
                        </div>
                        
                        <div class="card-body">
                            <h5 class="card-title">{{ $paper->title }}</h5>
                            <p class="card-text text-muted small">{{ Str::limit($paper->abstract, 150) }}</p>
                            
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="badge bg-{{ 
                                    $paper->status === 'pending' ? 'warning' : 
                                    ($paper->status === 'approved' ? 'success' : 'secondary') 
                                }}">
                                    {{ ucfirst($paper->status) }}
                                    @if($hasRejection)
                                        <span class="badge bg-danger ms-1">Rejected</span>
                                    @endif
                                </span>
                                <small class="text-muted">{{ $paper->created_at->format('M d, Y') }}</small>
                            </div>

                            {{-- Rejection Info --}}
                            @if($hasRejection)
                                <div class="alert alert-danger p-2 mt-2 mb-0 small">
                                    <strong><i class="fas fa-exclamation-circle me-1"></i> Rejection:</strong>
                                    @foreach($rejectedAssignments as $assignment)
                                        @if($assignment->reviewer)
                                            <p class="mb-1">Reviewer: {{ $assignment->reviewer->name }}</p>
                                        @endif
                                        @if($assignment->review && $assignment->review->comments)
                                            <p class="mb-0">Reason: {{ $assignment->review->comments }}</p>
                                        @endif
                                    @endforeach
                                </div>
                            @endif
                        </div>
                        
                        <div class="card-footer bg-transparent">
                            @if($allReviews->isEmpty())
                                <small class="text-muted">No reviews yet</small>
                            @else
                                <div class="reviews-section">
                                    <h6 class="small fw-bold mb-2">Reviews:</h6>
                                    @foreach($allReviews as $review)
                                        <div class="mb-2 @if(!$review->score) border-start border-danger border-3 ps-2 @endif">
                                            <div class="d-flex align-items-center">
                                                @if($review->score)
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <i class="fas fa-star{{ $i <= $review->score ? '' : '-empty' }} text-warning"></i>
                                                    @endfor
                                                    <span class="ms-2 small">{{ $review->score }}/5</span>
                                                @else
                                                    <span class="badge bg-danger me-2">Rejected</span>
                                                @endif
                                            </div>
                                            @if($review->comments)
                                                <p class="small text-muted mb-0 mt-1">{{ Str::limit($review->comments, 80) }}</p>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                            
                            <div class="mt-3 d-flex justify-content-between">
                                <form action="{{ route('removePaper', $paper->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this paper?')">
                                        <i class="fas fa-trash"></i> Remove
                                    </button>
                                </form>
                                <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#paperModal{{ $paper->id }}">
                                    <i class="fas fa-expand"></i> Preview
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                {{-- Modal for full preview --}}
                <div class="modal fade" id="paperModal{{ $paper->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">{{ $paper->title }}
                                    @if($hasRejection)
                                        <span class="badge bg-danger ms-2">Rejected</span>
                                    @endif
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                @if($hasRejection)
                                    <div class="alert alert-danger">
                                        <h6><i class="fas fa-exclamation-triangle me-1"></i> Rejection Details</h6>
                                        @foreach($rejectedAssignments as $assignment)
                                            @if($assignment->reviewer)
                                                <p class="mb-1">Reviewer: {{ $assignment->reviewer->name }}</p>
                                            @endif
                                            @if($assignment->review && $assignment->review->comments)
                                                <p class="mb-0">Reason: {{ $assignment->review->comments }}</p>
                                            @endif
                                        @endforeach
                                    </div>
                                @endif
                                
                                @if($paper->file_path && Storage::disk('public')->exists($paper->file_path))
                                    @if(Str::endsWith($paper->file_path, '.pdf'))
                                        <embed src="{{ Storage::url($paper->file_path) }}" type="application/pdf" width="100%" height="500px" style="border: none;">
                                    @else
                                        <div class="text-center py-5">
                                            <i class="fas fa-file-word fa-5x text-primary mb-3"></i>
                                            <p>Full preview not available for this file type.</p>
                                            <a href="{{ Storage::url($paper->file_path) }}" class="btn btn-primary" download>Download File</a>
                                        </div>
                                    @endif
                                @else
                                    <div class="alert alert-warning">File not available</div>
                                @endif
                                
                                @if($allReviews->isNotEmpty())
                                    <div class="mt-4">
                                        <h5>Review Details</h5>
                                        @foreach($allReviews as $review)
                                            <div class="card mb-2 @if(!$review->score) border-danger @endif">
                                                <div class="card-body">
                                                    <div class="d-flex justify-content-between">
                                                        <div>
                                                            <strong>Reviewer:</strong> 
                                                            {{ $review->reviewer->name ?? 'Anonymous' }}
                                                        </div>
                                                        <div>
                                                            @if(!$review->score)
                                                                <span class="badge bg-danger">Rejected</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    @if($review->score)
                                                        <div class="mt-2">
                                                            <strong>Score:</strong> 
                                                            @for($i = 1; $i <= 5; $i++)
                                                                <i class="fas fa-star{{ $i <= $review->score ? '' : '-empty' }} text-warning"></i>
                                                            @endfor
                                                            ({{ $review->score }}/5)
                                                        </div>
                                                    @endif
                                                    @if($review->comments)
                                                        <div class="mt-2">
                                                            <strong>Comments:</strong>
                                                            <p class="mb-0">{{ $review->comments }}</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

{{-- Keep your existing styles --}}
<style>
    .card {
        transition: transform 0.2s;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
    }
    .card-img-top {
        border-top-left-radius: 10px;
        border-top-right-radius: 10px;
    }
    .badge {
        font-size: 0.8rem;
        padding: 0.35em 0.65em;
    }
    .alert-danger {
        background-color: #f8d7da;
        border-color: #f5c6cb;
    }
    .review-rejected {
        background-color: #fff3f3;
        border-left: 3px solid #dc3545;
    }
</style>
@endsection