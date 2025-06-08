@extends('layouts.app')
@section('content')
<div class="container">
    <!-- Alert Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Main Dashboard Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-primary">Chair Dashboard</h2>
    </div>

    <!-- Rejected Papers Section -->
    <div class="card shadow-sm mb-4 border-0">
        <div class="card-header bg-white border-bottom-0">
            <h4 class="fw-bold text-danger">Rejected Papers</h4>
        </div>
        <div class="card-body">
            @if($papers->filter(fn($paper) => $paper->status == 'rejected')->isEmpty())
                <p class="text-muted">No rejected papers.</p>
            @else
                <div class="row g-4">
                    @foreach ($papers->filter(fn($paper) => $paper->status == 'rejected') as $paper)
                        @php
                            $rejectedAssignments = $paper->assignments()->where('status', 'rejected')->get();
                            $hasRejection = $rejectedAssignments->isNotEmpty();
                        @endphp
                        
                        <div class="col-md-6 col-lg-4">
                            <div class="card h-100 shadow-sm border-danger border-2">
                                <div class="card-header bg-white border-bottom-0 pt-3">
                                    <h5 class="card-title fw-bold text-truncate">{{ $paper->title }}</h5>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="badge bg-danger">
                                            {{ str_replace('_', ' ', $paper->status) }}
                                        </span>
                                        <small class="text-muted">Author: {{ $paper->author->name }}</small>
                                    </div>
                                </div>

                                <div class="card-body pt-0">
                                    @if($hasRejection)
                                        <div class="alert alert-danger p-2 mt-2 mb-3 small">
                                            <strong><i class="fas fa-exclamation-circle me-1"></i> Rejection Details:</strong>
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
                                    
                                    <!-- Reviewer Information -->
                                    @if($paper->reviewers->count() > 0)
                                        <div class="mb-3">
                                            <h6 class="fw-bold">Reviewer Assignment</h6>
                                            <p class="mb-2">Reviewer: <strong>{{ $paper->reviewers->first()->name }}</strong></p>
                                        </div>
                                    @endif
                                    
                                    <!-- Reviews Section -->
                                    <div class="mb-3">
                                        <h6 class="fw-bold">Review Details</h6>
                                        @if($paper->reviews->count() > 0)
                                            @foreach($paper->reviews as $review)
                                                <div class="bg-light p-3 rounded mb-2">
                                                    <p class="mb-1 fw-semibold">{{ $review->reviewer->name }}</p>
                                                    @if($review->score)
                                                        <div class="star-rating mb-2">
                                                            @for($i = 1; $i <= 5; $i++)
                                                                @if($i <= $review->score)
                                                                    <i class="fas fa-star text-warning"></i>
                                                                @else
                                                                    <i class="far fa-star text-warning"></i>
                                                                @endif
                                                            @endfor
                                                            <span class="ms-2 small text-muted">({{ $review->score }}/5)</span>
                                                        </div>
                                                    @endif
                                                    <p class="mb-0">{{ $review->content }}</p>
                                                </div>
                                            @endforeach
                                        @else
                                            <p class="text-muted">No reviews available.</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <!-- Papers With Reviews Section -->
    <div class="card shadow-sm mb-4 border-0">
        <div class="card-header bg-white border-bottom-0">
            <h4 class="fw-bold text-success">Reviewed Papers</h4>
        </div>
        <div class="card-body">
            <div class="row g-4">
                @foreach ($papers->filter(fn($paper) => $paper->reviews->count() > 0 && $paper->status != 'rejected') as $paper)
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-header bg-white border-bottom-0 pt-3">
                            <h5 class="card-title fw-bold text-truncate">{{ $paper->title }}</h5>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="badge 
                                    @if($paper->status == 'submitted') bg-primary 
                                    @elseif($paper->status == 'under_review') bg-warning text-dark 
                                    @elseif($paper->status == 'accepted') bg-success 
                                    @elseif($paper->status == 'rejected') bg-danger 
                                    @endif">
                                    {{ str_replace('_', ' ', $paper->status) }}
                                </span>
                                <small class="text-muted">Author: {{ $paper->author->name }}</small>
                            </div>
                        </div>

                        <div class="card-body pt-0">
                            <!-- Reviewer Assignment Section -->
                            <div class="mb-3">
                                <h6 class="fw-bold">Reviewer Assignment</h6>
                                @if($paper->reviewers->count() > 0)
                                    <p class="mb-2">Current Reviewer: <strong>{{ $paper->reviewers->first()->name }}</strong></p>
                                @endif
                                
                                @if($paper->reviews->count() == 0)
                                    <!-- Only show assignment form if no reviews exist -->
                                    <form method="POST" action="{{ route('chair.index') }}" class="row g-2">
                                        @csrf
                                        <input type="hidden" name="action" value="assign">
                                        <input type="hidden" name="paper_id" value="{{ $paper->id }}">
                                        <div class="col-8">
                                            <select class="form-select form-select-sm" name="reviewer_id" required>
                                                <option value="">Select Reviewer</option>
                                                @foreach ($reviewers as $reviewer)
                                                    <option value="{{ $reviewer->id }}">{{ $reviewer->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-4">
                                            <button type="submit" class="btn btn-sm btn-primary w-100" style="color: black;">Assign</button>
                                        </div>
                                    </form>
                                @else
                                    <div class="alert alert-info p-2 mb-0">
                                        <i class="fas fa-info-circle me-1"></i> Reviewer already assigned and review submitted
                                    </div>
                                @endif
                            </div>

                            <!-- Reviews Section with Star Rating -->
                            <div class="mb-3">
                                <h6 class="fw-bold">Review Details</h6>
                                <div class="bg-light p-3 rounded">
                                    @php
                                        $review = $paper->reviews->first();
                                        $score = $review->score ?? 0;
                                    @endphp

                                    @if($review)
                                        <p class="mb-1 fw-semibold">{{ $review->reviewer->name }}</p>

                                        <!-- Star Rating Display -->
                                        @if($score)
                                            <div class="star-rating mb-2">
                                                @for($i = 1; $i <= 5; $i++)
                                                    @if($i <= $score)
                                                        <i class="fas fa-star text-warning"></i>
                                                    @else
                                                        <i class="far fa-star text-warning"></i>
                                                    @endif
                                                @endfor
                                                <span class="ms-2 small text-muted">({{ $score }}/5)</span>
                                            </div>
                                        @endif

                                        <p class="mb-0">{{ $review->content }}</p>
                                    @else
                                        <p class="text-muted">No reviews available.</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Papers Without Reviews Section -->
    <div class="card shadow-sm mb-4 border-0">
        <div class="card-header bg-white border-bottom-0">
            <h4 class="fw-bold text-warning">Papers Pending Review</h4>
        </div>
        <div class="card-body">
            <div class="row g-4">
                @foreach ($papers->filter(fn($paper) => $paper->reviews->count() === 0 && $paper->status != 'rejected') as $paper)
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-header bg-white border-bottom-0 pt-3">
                            <h5 class="card-title fw-bold text-truncate">{{ $paper->title }}</h5>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="badge 
                                    @if($paper->status == 'submitted') bg-primary 
                                    @elseif($paper->status == 'under_review') bg-warning text-dark 
                                    @elseif($paper->status == 'accepted') bg-success 
                                    @elseif($paper->status == 'rejected') bg-danger 
                                    @endif">
                                    {{ str_replace('_', ' ', $paper->status) }}
                                </span>
                                <small class="text-muted">Author: {{ $paper->author->name }}</small>
                            </div>
                        </div>

                        <div class="card-body pt-0">
                            <!-- Reviewer Assignment Section -->
                            <div class="mb-3">
                                <h6 class="fw-bold">Reviewer Assignment</h6>
                                @if($paper->reviewers->count() == 0)
                                <form method="POST" action="{{ route('chair.index') }}" class="row g-2">
                                    @csrf
                                    <input type="hidden" name="action" value="assign">
                                    <input type="hidden" name="paper_id" value="{{ $paper->id }}">
                                    <div class="col-8">
                                        <select class="form-select form-select-sm" name="reviewer_id" required>
                                            <option value="">Select Reviewer</option>
                                            @foreach ($reviewers as $reviewer)
                                                <option value="{{ $reviewer->id }}">{{ $reviewer->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-4">
                                        <button type="submit" class="btn btn-sm btn-primary w-100" style="color: black;">Assign</button>
                                    </div>
                                </form>
                                 @endif
                                @if($paper->reviewers->count() > 0)
                                    <p class="mb-2">Current Reviewer: <strong>{{ $paper->reviewers->first()->name }}</strong></p>
                                @endif
                                
                            </div>

                            <!-- Reviews Section -->
                            <div class="mb-3">
                                <h6 class="fw-bold">Reviews</h6>
                                <p class="text-muted">No reviews submitted yet</p>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-4">
        {{ $papers->links() }}
    </div>


    <!-- Pending Approvals Section (for Super Chair) -->
    @if ($isSuperChair)
    <div class="card shadow-sm mt-4 border-0">
        <div class="card-header bg-white border-bottom-0">
            <h4 class="fw-bold text-warning">Pending Approvals</h4>
        </div>
        <div class="card-body">
            @if($pendingUsers->isEmpty())
                <p class="text-muted">No pending approvals.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pendingUsers as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->role }}</td>
                                <td>
                                    <form method="POST" action="{{ route('chair.approve', $user->id) }}">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success">Approve</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
    @endif
</div>
@endsection