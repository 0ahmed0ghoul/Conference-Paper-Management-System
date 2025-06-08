@extends('layouts.app')

@section('title', 'Authors Papers')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h2 class="fw-bold text-success mb-0">Author's Papers</h2>
                                <a href="{{ route('welcome') }}" class="btn btn-outline-primary">
                                    <i class="fas fa-arrow-left me-1"></i> Back to Home Page
                                </a>
                    </div>
                </div>

                <div class="card-body">
                    @if($acceptedPapers->isEmpty())
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i> No  papers available yet.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th width="40%">Title</th>
                                        <th width="25%">Author</th>
                                        <th width="20%">Submitted Date</th>
                                        <th width="25%">Rate</th>
                                        <th width="15%">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($acceptedPapers as $paper)
                                    <tr>
                                        <td>
                                            <h6 class="fw-bold mb-1">{{ $paper->title }}</h6>
                                            <p class="small text-muted mb-0">{{ Str::limit($paper->abstract, 100) }}</p>
                                        </td>
                                        <td>{{ $paper->author->name }}</td>
                                        <td>{{ $paper->created_at->format('M d, Y') }}</td>
                                        <td>
                                                @if($paper->reviews_avg_score)
                                                    <div class="star-rating">
                                                        @for($i = 1; $i <= 5; $i++)
                                                            @if($i <= round($paper->reviews_avg_score))
                                                                <i class="fas fa-star text-warning"></i>
                                                            @else
                                                                <i class="far fa-star text-warning"></i>
                                                            @endif
                                                        @endfor
                                                    </div>
                                                @else
                                                    <span class="text-muted">No reviews yet</span>
                                                @endif
                                            </td>
                                        <td>
                                            @if($paper->file_path && Storage::disk('public')->exists($paper->file_path))
                                                <a href="{{ route('papers.download', $paper) }}" 
                                                class="btn btn-sm btn-info">
                                                    <i class="fas fa-download me-1"></i> Download
                                                </a>
                                            @else
                                                <span class="badge bg-secondary">File not available</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-center mt-4">
                            {{ $acceptedPapers->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .card {
        border-radius: 10px;
        overflow: hidden;
    }
    .table th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 0.5px;
    }
    .table td {
        vertical-align: middle;
    }
    .btn-success {
        background-color: #28a745;
        border-color: #28a745;
    }
    .btn-success:hover {
        background-color: #218838;
        border-color: #1e7e34;
    }
</style>
@endsection