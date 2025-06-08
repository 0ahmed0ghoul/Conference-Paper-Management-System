@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-primary">Your Paper Assignments</h2>
        <div class="badge bg-light text-dark rounded-pill p-2">
            <i class="bi bi-inbox-fill me-2"></i>
            {{ $assignments->whereIn('status', ['pending', 'accepted'])->count() }} Active
        </div>
    </div>

    <!-- Flash Messages -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm">
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($assignments->isEmpty())
        <div class="card shadow-sm border-0">
            <div class="card-body text-center py-5">
                <i class="bi bi-inbox text-muted" style="font-size: 3rem;"></i>
                <h5 class="mt-3 text-muted">No paper assignments at the moment</h5>
                <p class="text-muted">Check back later for new review requests</p>
            </div>
        </div>
    @else
        <!-- Active Assignments -->
        <div class="assignment-container">
            @foreach ($assignments->whereIn('status', ['pending', 'accepted']) as $assignment)
                <div class="card mb-4 shadow-sm border-0 assignment-card" id="assignment-{{ $assignment->id }}">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center border-bottom">
                        <div>
                            <h5 class="mb-1 fw-bold text-dark">{{ $assignment->paper->title }}</h5>
                            <div class="d-flex align-items-center">
                                <span class="badge bg-light text-dark rounded-pill me-2">
                                    <i class="bi bi-person-fill me-1"></i>
                                    {{ $assignment->paper->author->name }}
                                </span>
                                <span class="badge bg-light text-dark rounded-pill">
                                    <i class="bi bi-calendar me-1"></i>
                                    {{ $assignment->created_at->format('M d, Y') }}
                                </span>
                            </div>
                        </div>
                        <div>
                            @if($assignment->status === 'pending')
                                <span class="badge bg-warning bg-opacity-15 text-warning rounded-pill px-3 py-2"  style="color: black;">
                                    <i class="bi bi-hourglass-split me-1" ></i> Pending
                                </span>
                            @elseif($assignment->status === 'accepted')
                                <span class="badge bg-success bg-opacity-15 text-success rounded-pill px-3 py-2">
                                    <i class="bi bi-check-circle me-1"></i> Accepted
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="card-body">
                        <h6 class="fw-bold text-secondary mb-3">ABSTRACT</h6>
                        <div class="abstract-container bg-light bg-opacity-10 p-3 rounded-2 mb-4" style="max-height: 150px; overflow-y: auto;">
                            <p class="mb-0 text-dark">{{ $assignment->paper->abstract }}</p>
                        </div>

                        @if($assignment->status === 'pending')
                            <div class="d-flex gap-3">
                                <form action="{{ route('reviewer.accept', $assignment->id) }}" method="POST" class="flex-grow-1">
                                    @csrf
                                    <button type="submit" class="btn btn-success rounded-pill w-100 py-2 fw-bold">
                                        <i class="bi bi-check-circle me-2"></i> Accept Paper
                                    </button>
                                </form>
                                <form action="{{ route('reviewer.reject', $assignment->id) }}" method="POST" class="flex-grow-1">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-danger rounded-pill w-100 py-2 fw-bold">
                                        <i class="bi bi-x-circle me-2"></i> Decline
                                    </button>
                                </form>
                            </div>
                        @elseif($assignment->status === 'accepted')
                            <form method="POST" action="{{ route('reviewer.submitReview', $assignment->id) }}" class="needs-validation" novalidate>
                                @csrf
                                <input type="hidden" name="paper_id" value="{{ $assignment->paper_id }}">

                                <div class="row g-3 mb-4">
                                    <div class="col-md-4">
                                        <label for="score" class="form-label fw-bold text-secondary">Rating</label>
                                        <select name="score" class="form-select rounded-pill py-2" required>
                                            <option value="">Select rating...</option>
                                            <option value="1">★ Poor</option>
                                            <option value="2">★★ Fair</option>
                                            <option value="3" selected>★★★ Good</option>
                                            <option value="4">★★★★ Very Good</option>
                                            <option value="5">★★★★★ Excellent</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label for="comments" class="form-label fw-bold text-secondary">Review Comments</label>
                                    <textarea name="comments" class="form-control rounded-3 p-3" rows="5" required
                                              minlength="20" maxlength="2000"
                                              placeholder="Please provide detailed feedback (20-2000 characters)"
                                              style="resize: none;"></textarea>
                                </div>

                                <button type="submit" class="btn btn-primary rounded-pill w-100 py-2 fw-bold">
                                    <i class="bi bi-send-fill me-2"></i> Submit Review
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Enhanced assignment removal with animation
    const assignmentId = {{ session('assignment_id') ?? 'null' }};
    if (assignmentId) {
        const card = document.getElementById('assignment-' + assignmentId);
        if (card) {
            // Animate removal
            card.style.transition = 'all 0.4s ease';
            card.style.transform = 'translateX(-100%)';
            card.style.opacity = '0';
            card.style.marginBottom = '0';
            card.style.height = card.offsetHeight + 'px';
            
            // Trigger reflow
            void card.offsetHeight;
            
            // Animate collapse
            card.style.height = '0';
            card.style.overflow = 'hidden';
            card.style.marginBottom = '0';
            card.style.paddingTop = '0';
            card.style.paddingBottom = '0';
            card.style.border = '0';
            
            // Remove after animation
            setTimeout(() => {
                card.remove();
                
                // Show empty state if needed
                if (document.querySelectorAll('.assignment-card').length === 0) {
                    const container = document.querySelector('.assignment-container');
                    const emptyState = `
                        <div class="card shadow-sm border-0">
                            <div class="card-body text-center py-5">
                                <i class="bi bi-check-circle text-success" style="font-size: 3rem;"></i>
                                <h5 class="mt-3 text-success">All caught up!</h5>
                                <p class="text-muted">You've completed all active assignments</p>
                            </div>
                        </div>
                    `;
                    container.innerHTML = emptyState;
                }
            }, 400);
        }
    }

    // Bootstrap form validation
    (() => {
        'use strict'
        const forms = document.querySelectorAll('.needs-validation')
        
        Array.from(forms).forEach(form => {
            form.addEventListener('submit', event => {
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }
                
                form.classList.add('was-validated')
            }, false)
        })
    })()
});
</script>

<style>
    .assignment-card {
        transition: all 0.3s ease;
    }
    .assignment-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 0.5rem 1.25rem rgba(0, 0, 0, 0.1) !important;
    }
    .abstract-container::-webkit-scrollbar {
        width: 6px;
    }
    .abstract-container::-webkit-scrollbar-track {
        background: rgba(0,0,0,0.05);
        border-radius: 10px;
    }
    .abstract-container::-webkit-scrollbar-thumb {
        background: rgba(0,0,0,0.1);
        border-radius: 10px;
    }
    .form-control:focus, .form-select:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
    }
</style>
@endsection