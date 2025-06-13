@extends('layouts.app')

@section('content')
<div class="reviewer-dashboard">
    <div class="dashboard-header">
        <h2>Your Paper Assignments</h2>
        <div class="active-badge">
            <i class="bi bi-inbox-fill"></i>
            {{ $assignments->whereIn('status', ['pending', 'accepted'])->count() }} Active
        </div>
    </div>

    <!-- Flash Messages -->
    @if (session('success'))
        <div class="alert-message success">
            <i class="bi bi-check-circle-fill"></i>
            {{ session('success') }}
            <button class="close-btn">&times;</button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert-message error">
            <i class="bi bi-exclamation-triangle-fill"></i>
            {{ session('error') }}
            <button class="close-btn">&times;</button>
        </div>
    @endif

    @if($assignments->isEmpty())
        <div class="empty-state">
            <i class="bi bi-inbox"></i>
            <h5>No paper assignments at the moment</h5>
            <p>Check back later for new review requests</p>
        </div>
    @else
        <!-- Active Assignments -->
        <div class="assignments-grid">
            @foreach ($assignments->whereIn('status', ['pending', 'accepted']) as $assignment)
                <div class="assignment-card" id="assignment-{{ $assignment->id }}">
                    <div class="card-header">
                        <div class="paper-info">
                            <h3>{{ $assignment->paper->title }}</h3>
                            <div class="meta-tags">
                                <span class="author-tag">
                                    <i class="bi bi-person-fill"></i>
                                    {{ $assignment->paper->author->name }}
                                </span>
                                <span class="date-tag">
                                    <i class="bi bi-calendar"></i>
                                    {{ $assignment->created_at->format('M d, Y') }}
                                </span>
                            </div>
                        </div>
                        <div class="status-badge">
                            @if($assignment->status === 'pending')
                                <span class="pending">
                                    <i class="bi bi-hourglass-split"></i> Pending
                                </span>
                            @elseif($assignment->status === 'accepted')
                                <span class="accepted">
                                    <i class="bi bi-check-circle"></i> Accepted
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="card-content">
                        <h4>ABSTRACT</h4>
                        <div class="abstract-container">
                            <p>{{ $assignment->paper->abstract }}</p>
                        </div>
                        @if($assignment->status === 'pending')
                        <div class="download-section">
                            <a href="{{ route('reviewer.downloadPaper', $assignment->paper) }}" class="download-btn">
                                <i class="bi bi-download"></i> Download Paper
                            </a>
                        </div>
                            <div class="action-buttons">
                                <form action="{{ route('reviewer.accept', $assignment->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="accept-btn">
                                        <i class="bi bi-check-circle"></i> Accept Paper
                                    </button>
                                </form>
                                <form action="{{ route('reviewer.reject', $assignment->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="reject-btn">
                                        <i class="bi bi-x-circle"></i> Decline
                                    </button>
                                </form>
                            </div>
                        @elseif($assignment->status === 'accepted')
                            <form method="POST" action="{{ route('reviewer.submitReview', $assignment->id) }}" class="review-form">
                                @csrf
                                <input type="hidden" name="paper_id" value="{{ $assignment->paper_id }}">

                                <div class="form-group">
                                    <label for="score">Rating</label>
                                    <select name="score" required>
                                        <option value="">Select rating...</option>
                                        <option value="1">★ Poor</option>
                                        <option value="2">★★ Fair</option>
                                        <option value="3" selected>★★★ Good</option>
                                        <option value="4">★★★★ Very Good</option>
                                        <option value="5">★★★★★ Excellent</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="comments">Review Comments</label>
                                    <textarea name="comments" rows="5" required
                                              minlength="20" maxlength="2000"
                                              placeholder="Please provide detailed feedback (20-2000 characters)"></textarea>
                                </div>

                                <button type="submit" class="submit-btn">
                                    <i class="bi bi-send-fill"></i> Submit Review
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
    // Close alert buttons
    document.querySelectorAll('.close-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            this.parentElement.style.opacity = '0';
            setTimeout(() => this.parentElement.remove(), 300);
        });
    });

    // Assignment removal animation
    const assignmentId = {{ session('assignment_id') ?? 'null' }};
    if (assignmentId) {
        const card = document.getElementById('assignment-' + assignmentId);
        if (card) {
            card.style.transition = 'all 0.4s ease';
            card.style.transform = 'translateX(-100%)';
            card.style.opacity = '0';
            card.style.marginBottom = '0';
            card.style.height = card.offsetHeight + 'px';
            
            setTimeout(() => {
                card.style.height = '0';
                card.style.overflow = 'hidden';
                card.style.marginBottom = '0';
                card.style.paddingTop = '0';
                card.style.paddingBottom = '0';
                card.style.border = '0';
                
                setTimeout(() => {
                    card.remove();
                    if (document.querySelectorAll('.assignment-card').length === 0) {
                        const container = document.querySelector('.assignments-grid');
                        container.innerHTML = `
                            <div class="empty-state completed">
                                <i class="bi bi-check-circle"></i>
                                <h5>All caught up!</h5>
                                <p>You've completed all active assignments</p>
                            </div>
                        `;
                    }
                }, 400);
            }, 10);
        }
    }

    // Form validation
    document.querySelectorAll('.review-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!this.checkValidity()) {
                e.preventDefault();
                e.stopPropagation();
                this.classList.add('was-validated');
            }
        });
    });
});
</script>

<style>
/* Base Styles */
.reviewer-dashboard {
    max-width: 1200px;
    margin: 0 auto;
    padding: 2rem;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    color: #333;
}

.dashboard-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
}

.dashboard-header h2 {
    font-size: 1.8rem;
    font-weight: 600;
    color: #2c3e50;
    margin: 0;
}

/* Badges & Tags */
.active-badge {
    background-color: #f8f9fa;
    color: #212529;
    padding: 0.5rem 1rem;
    border-radius: 2rem;
    font-size: 0.9rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.meta-tags {
    display: flex;
    gap: 0.8rem;
    margin-top: 0.5rem;
}

.author-tag, .date-tag {
    background-color: #f0f2f5;
    padding: 0.3rem 0.8rem;
    border-radius: 2rem;
    font-size: 0.85rem;
    display: flex;
    align-items: center;
    gap: 0.3rem;
}

/* Alert Messages */
.alert-message {
    padding: 1rem;
    border-radius: 0.5rem;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.8rem;
    position: relative;
    transition: opacity 0.3s ease;
}

.alert-message.success {
    background-color: #e6f7ee;
    color: #0d6832;
}

.alert-message.error {
    background-color: #fde8e8;
    color: #c81e1e;
}

.close-btn {
    background: none;
    border: none;
    font-size: 1.2rem;
    cursor: pointer;
    margin-left: auto;
    color: inherit;
}

/* Assignment Card */
.assignment-card {
    background: white;
    border-radius: 0.8rem;
    box-shadow: 0 2px 15px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
    overflow: hidden;
    width: 100%;
}

.assignment-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.12);
}

.card-header {
    padding: 1.2rem 1.5rem;
    border-bottom: 1px solid #e9ecef;
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: white;
}

.paper-info h3 {
    font-size: 1.2rem;
    font-weight: 600;
    margin: 0;
    color: #2c3e50;
}

.status-badge span {
    padding: 0.4rem 1rem;
    border-radius: 2rem;
    font-size: 0.85rem;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 0.4rem;
}

.status-badge .pending {
    background-color: rgba(255, 193, 7, 0.15);
    color: #856404;
}

.status-badge .accepted {
    background-color: rgba(40, 167, 69, 0.15);
    color: #155724;
}

.card-content {
    padding: 1.5rem;
}

.card-content h4 {
    font-size: 1rem;
    font-weight: 600;
    color: #6c757d;
    margin-bottom: 0.8rem;
}

/* Abstract Container */
.abstract-container {
    background-color: #f8f9fa;
    padding: 1rem;
    border-radius: 0.5rem;
    max-height: 150px;
    overflow-y: auto;
    margin-bottom: 1.5rem;
    line-height: 1.6;
}

.abstract-container p {
    margin: 0;
    color: #495057;
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

/* Buttons */
.download-btn, .accept-btn, .reject-btn, .submit-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    padding: 0.7rem 1.5rem;
    border-radius: 2rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s ease;
    border: none;
    font-size: 0.95rem;
}

.download-section {
    margin-bottom: 1.5rem;
    width: 100%;
}

.download-btn {
    background-color: transparent;
    border: 1px solid #3490dc;
    color: #3490dc;
    width: 100%;

}

.download-btn:hover {
    background-color: #f0f7ff;
}

.action-buttons {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
}

.accept-btn {
    background-color: #28a745;
    color: white;
    width: 100%;

}

.accept-btn:hover {
    background-color: #218838;
}

.reject-btn {
    background-color: transparent;
    border: 1px solid #dc3545;
    color: #dc3545;
    width: 100%;

}

.reject-btn:hover {
    background-color: #fff0f0;
}

.submit-btn {
    background-color: #007bff;
    color: white;
    width: 100%;
    padding: 0.8rem;
}

.submit-btn:hover {
    background-color: #0069d9;
}

/* Form Elements */
.review-form {
    margin-top: 1rem;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-group label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 600;
    color: #6c757d;
    font-size: 0.95rem;
}

.form-group select, .form-group textarea {
    width: 100%;
    padding: 0.8rem 1rem;
    border: 1px solid #ced4da;
    border-radius: 0.5rem;
    font-size: 1rem;
    transition: border-color 0.15s ease, box-shadow 0.15s ease;
}

.form-group select:focus, .form-group textarea:focus {
    border-color: #80bdff;
    outline: 0;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.form-group textarea {
    min-height: 150px;
    resize: vertical;
}

/* Empty States */
.empty-state {
    text-align: center;
    padding: 3rem 2rem;
    background: white;
    border-radius: 0.8rem;
    box-shadow: 0 2px 15px rgba(0, 0, 0, 0.08);
}

.empty-state i {
    font-size: 3rem;
    color: #adb5bd;
    margin-bottom: 1rem;
}

.empty-state.completed i {
    color: #28a745;
}

.empty-state h5 {
    font-size: 1.3rem;
    color: #495057;
    margin-bottom: 0.5rem;
}

.empty-state p {
    color: #6c757d;
    margin: 0;
}

@media (max-width: 768px) {
    .dashboard-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }
    
    .action-buttons {
        grid-template-columns: 1fr;
    }
    
    .card-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }
    
    .status-badge {
        align-self: flex-end;
    }
    
    .assignments-container {
        padding: 0 1rem;
    }
}
</style>
@endsection