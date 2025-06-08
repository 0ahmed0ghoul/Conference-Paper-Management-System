@extends('layouts.app')

@section('title', 'Waiting for Approval | Ai24')

@push('styles')
<style>
    .approval-container {
        max-width: 600px;
        margin: 4rem auto;
        background-color: #ffffff;
        border-radius: 0.75rem;
        padding: 2rem;
        text-align: center;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        border: 1px solid #e5e7eb;
        animation: fadeInUp 0.6s ease-out forwards;
        opacity: 0;
        transform: translateY(20px);
    }

    @keyframes fadeInUp {
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .approval-container h2 {
        color: #4f46e5;
        margin-bottom: 1rem;
    }

    .approval-container p {
        font-size: 1rem;
        margin-bottom: 0.75rem;
        color: #374151;
    }

    .approval-container strong {
        color: #111827;
    }

    .spinner {
        margin-top: 1.5rem;
        width: 40px;
        height: 40px;
        border: 4px solid #e5e7eb;
        border-top: 4px solid #4f46e5;
        border-radius: 50%;
        animation: spin 1s linear infinite;
        display: inline-block;
    }

    @keyframes spin {
        to {
            transform: rotate(360deg);
        }
    }
</style>
@endpush

@section('content')
<div class="approval-container">
    <h2>Thank you for registering, {{ $user->name }}!</h2>
    <p>Your role: <strong>{{ ucfirst($user->role) }}</strong></p>
    <p>Status: <strong>{{ ucfirst($user->status) }}</strong></p>
    <p>You will receive an email once your account is approved.</p>
    <div class="spinner" title="Waiting for approval..."></div>
</div>
@endsection
