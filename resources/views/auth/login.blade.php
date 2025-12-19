@extends('layouts.app')

@section('content')
<div style="display: flex; justify-content: center; align-items: center; min-height: 80vh;">
    <div class="glass-card" style="width: 100%; max-width: 400px;">
        <h2 style="text-align: center; margin-bottom: 2rem;">Welcome Back</h2>
        
        <form method="POST" action="{{ route('login') }}">
            @csrf
            
            <div class="form-group">
                <label for="email">Email Address</label>
                <input id="email" type="email" class="form-control" name="email" required autofocus>
                @error('email')
                    <span style="color: red; font-size: 0.875rem;">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input id="password" type="password" class="form-control" name="password" required>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%;">
                Login
            </button>
            
            <p style="text-align: center; margin-top: 1rem;">
                Don't have an account? <a href="{{ route('register') }}" style="color: var(--primary-color);">Register</a>
            </p>
        </form>
    </div>
</div>
@endsection