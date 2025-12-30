@extends('layouts.app')

@section('title', 'Create User - Admin Panel')

@section('content')
<div class="dashboard-container">
    <div class="welcome-section">
        <div class="welcome-text">
            <h1>Create New User</h1>
            <p>Add a new user to the banking system</p>
        </div>
    </div>

    <div class="auth-card">
        <form method="POST" action="{{ route('admin.users.store') }}" class="form">
            @csrf

            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" id="name" name="name" placeholder="Enter full name" value="{{ old('name') }}" required>
                @error('name')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" placeholder="Enter email address" value="{{ old('email') }}" required>
                @error('email')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="phone">Phone Number</label>
                <input type="tel" id="phone" name="phone" placeholder="Enter phone number" value="{{ old('phone') }}" required>
                @error('phone')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="card_pin">Card PIN (4 digits)</label>
                <input type="text" id="card_pin" name="card_pin" placeholder="Enter 4-digit PIN" maxlength="4" value="{{ old('card_pin') }}" required>
                @error('card_pin')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary btn-block">
                <i class="fas fa-user-plus"></i> Create User
            </button>

            <a href="{{ route('admin.index') }}" class="btn btn-secondary btn-block" style="margin-top: 10px;">
                <i class="fas fa-arrow-left"></i> Back to Admin Panel
            </a>
        </form>

        @if ($errors->any())
            <div class="error-box" style="margin-top: 20px;">
                <h4>Validation Errors:</h4>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
</div>

<style>
    .error-box {
        background-color: #f8d7da;
        border: 1px solid #f5c6cb;
        color: #721c24;
        padding: 15px;
        border-radius: 4px;
    }

    .error-box ul {
        margin: 10px 0 0 20px;
    }

    .error-message {
        color: #dc3545;
        font-size: 0.875em;
        display: block;
        margin-top: 5px;
    }

    .btn-block {
        width: 100%;
        display: block;
    }
</style>
@endsection
