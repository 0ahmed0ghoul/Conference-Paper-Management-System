@extends('layouts.app')

@section('title', 'Register | Ai24')

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
    
    .registration-card {
        background-color: white;
        border-radius: 0.75rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        padding: 2rem;
    }
    
    .registration-header {
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
    
    input {
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
    
    .role-selection {
        margin: 2rem 0;
    }
    
    .role-option {
        border: 1px solid #e5e7eb;
        border-radius: 0.5rem;
        padding: 1.5rem;
        margin-bottom: 1rem;
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .role-option:hover {
        border-color: var(--primary);
    }
    
    .role-option.selected {
        border: 2px solid var(--primary);
        background-color: #f5f3ff;
    }
    
    .role-description {
        color: var(--text-light);
        margin-top: 0.5rem;
    }
    
    .error-message {
        color: #ef4444;
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }
    
    .form-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
    }
</style>
@endpush

@section('content')
<div class="container">
    <div class="registration-card">
        <div class="registration-header">
            <h1>Register for Ai24</h1>
            <p>Join us as a conference participant</p>
        </div>

        @if($errors->any())
            <div class="alert alert-danger" style="color: #ef4444; margin-bottom: 1.5rem;">
                <ul style="list-style: none; padding: 0;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('signup') }}">
            @csrf

            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required>
                @error('name')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required>
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
                <label for="password_confirmation">Confirm Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required>
            </div>

            <div class="role-selection">
                <h3>Select Your Role</h3>
                
                <div class="role-option {{ old('role', 'author') === 'author' ? 'selected' : '' }}" data-role="author">
                    <div class="ticket-header">
                        <h4>Author</h4>
                    </div>
                    <p class="role-description">Submit papers and participate in the conference</p>
                    <input type="radio" name="role" value="author" {{ old('role', 'author') === 'author' ? 'checked' : '' }} required hidden>
                </div>

                <div class="role-option {{ old('role') === 'reviewer' ? 'selected' : '' }}" data-role="reviewer">
                    <div class="ticket-header">
                        <h4>Reviewer</h4>
                    </div>
                    <p class="role-description">Review papers (requires chair approval)</p>
                    <input type="radio" name="role" value="reviewer" {{ old('role') === 'reviewer' ? 'checked' : '' }} required hidden>
                </div>

                <div class="role-option {{ old('role') === 'chair' ? 'selected' : '' }}" data-role="chair">
                    <div class="ticket-header">
                        <h4>Chair</h4>
                    </div>
                    <p class="role-description">Manage conference (requires admin approval)</p>
                    <input type="radio" name="role" value="chair" {{ old('role') === 'chair' ? 'checked' : '' }} required hidden>
                </div>
                @error('role')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit" class="btn-submit">Complete Registration</button>
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.querySelectorAll('.role-option').forEach(option => {
        option.addEventListener('click', function () {
            // Unselect all options first
            document.querySelectorAll('.role-option').forEach(opt => {
                opt.classList.remove('selected');
                opt.querySelector('input[type="radio"]').checked = false;
            });

            // Select the clicked one
            this.classList.add('selected');
            this.querySelector('input[type="radio"]').checked = true;
        });
    });
</script>

</script>
@endpush
@endsection