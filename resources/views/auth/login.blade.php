@extends('layouts.app')

@section('title', 'Login')

@push('styles')
<style>
    :root {
        --primary: #4f46e5;
        --primary-dark: #4338ca;
        --text: #1f2937;
        --text-light: #6b7280;
        --bg: #f9fafb;
    }

    .container {
        max-width: 800px;
        margin: 3rem auto;
        padding: 0 2rem;
    }

    .login-card {
        background-color: white;
        border-radius: 0.75rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        padding: 2rem;
    }

    .login-header {
        text-align: center;
        margin-bottom: 2rem;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 600;
    }

    input, select {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid #e5e7eb;
        border-radius: 0.5rem;
        font-family: inherit;
    }

    .btn-submit {
        background-color: var(--primary);
        color: white;
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 0.5rem;
        font-weight: 600;
        cursor: pointer;
        transition: background-color 0.2s;
        width: 100%;
        margin-top: 1rem;
    }

    .error-message {
        color: #ef4444;
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }

    .form-footer {
        margin-top: 1.5rem;
        text-align: center;
        font-size: 0.9rem;
    }

    .form-footer a {
        color: var(--primary);
        text-decoration: none;
    }

    /* Role card buttons */
    .role-options {
        display: flex;
        justify-content: space-between;
        gap: 1rem;
        margin-top: 0.5rem;
    }

    .role-card {
        flex: 1;
        padding: 1rem;
        border: 2px solid #e5e7eb;
        border-radius: 0.5rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s;
        user-select: none;
    }

    .role-card:hover {
        border-color: var(--primary);
        background-color: #eef2ff;
    }

    .role-card.selected {
        border-color: var(--primary-dark);
        background-color: #e0e7ff;
        font-weight: 600;
    }

    .role-card input[type="radio"] {
        display: none;
    }

    @media (max-width: 768px) {
        .role-options {
            flex-direction: column;
        }
    }
</style>
@endpush

@section('content')
<div class="container">
    <div class="login-card">
        <div class="login-header">
            <h1>Welcome Back</h1>
            <p>Login to your Ai24 account</p>
        </div>

        @if(session('error'))
            <div class="alert alert-danger" style="color: #ef4444; margin-bottom: 1.5rem;">
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger" style="color: #ef4444; margin-bottom: 1.5rem;">
                <ul style="list-style: none; padding: 0;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus>
                @error('email')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
                @error('password')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label>Select Role</label>
                <div class="role-options">
                    @php $selectedRole = old('role'); @endphp

                    <label class="role-card {{ $selectedRole === 'author' ? 'selected' : '' }}">
                        <input type="radio" name="role" value="author" {{ $selectedRole === 'author' ? 'checked' : '' }}>
                        Author
                    </label>

                    <label class="role-card {{ $selectedRole === 'chair' ? 'selected' : '' }}">
                        <input type="radio" name="role" value="chair" {{ $selectedRole === 'chair' ? 'checked' : '' }}>
                        Chair
                    </label>

                    <label class="role-card {{ $selectedRole === 'reviewer' ? 'selected' : '' }}">
                        <input type="radio" name="role" value="reviewer" {{ $selectedRole === 'reviewer' ? 'checked' : '' }}>
                        Reviewer
                    </label>
                </div>
                @error('role')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group" style="display: flex; justify-content: space-between; align-items: center;">
                <label>
                    <input type="checkbox" name="remember"> Remember me
                </label>
            </div>

            <button type="submit" class="btn-submit">Login</button>
        </form>

        <div class="form-footer">
            Don’t have an account? <a href="{{ route('signup') }}">Register here</a>
        </div>
    </div>
</div>

<script>
    // JS to toggle selected class on role cards
    document.querySelectorAll('.role-card').forEach(card => {
        card.addEventListener('click', () => {
            document.querySelectorAll('.role-card').forEach(c => c.classList.remove('selected'));
            card.classList.add('selected');
            card.querySelector('input[type="radio"]').checked = true;
        });
    });
</script>
@endsection
