@extends('layouts.auth')

@section('title', 'SecureBank - Login')

@section('content')
<div class="login-form">
    <div class="form-tabs">
        <button class="tab-btn active" data-tab="fingerprint">
            <i class="fas fa-fingerprint"></i>
            Fingerprint
        </button>
        <button class="tab-btn" data-tab="password">
            <i class="fas fa-key"></i>
            Password
        </button>
    </div>

    <!-- Fingerprint Login Tab -->
    <div class="tab-content active" id="fingerprint-tab">
        <div class="fingerprint-scanner">
            <div class="scanner-circle">
                <div class="scanner-animation">
                    <i class="fas fa-fingerprint"></i>
                </div>
                <div class="pulse-ring"></div>
                <div class="pulse-ring delay-1"></div>
                <div class="pulse-ring delay-2"></div>
            </div>
            <h3>Touch Sensor to Login</h3>
            <p class="scanner-status">Waiting for fingerprint...</p>
        </div>

        <form action="{{ url('/login/fingerprint') }}" method="POST" id="fingerprint-form">
            @csrf
            <input type="hidden" name="fingerprint_data" id="fingerprint_data">
            
            <div class="form-group">
                <label for="user_id">
                    <i class="fas fa-user"></i>
                    User ID
                </label>
                <input type="text" 
                       id="user_id" 
                       name="user_id" 
                       placeholder="Enter your user ID" 
                       required 
                       autocomplete="username">
            </div>

            <button type="button" class="btn btn-primary" id="scan-fingerprint">
                <i class="fas fa-fingerprint"></i>
                Scan Fingerprint
            </button>
        </form>

        <div class="form-divider">
            <span>or use backup method</span>
        </div>

        <button class="btn btn-secondary btn-switch" data-switch="password">
            <i class="fas fa-key"></i>
            Login with Password
        </button>
    </div>

    <!-- Password Login Tab -->
    <div class="tab-content" id="password-tab">
        <form action="{{ url('/login') }}" method="POST" id="password-form">
            @csrf
            
            <div class="form-group">
                <label for="email">
                    <i class="fas fa-envelope"></i>
                    Email or User ID
                </label>
                <input type="text" 
                       id="email" 
                       name="email" 
                       placeholder="Enter your email or user ID" 
                       required 
                       autocomplete="username">
            </div>

            <div class="form-group">
                <label for="password">
                    <i class="fas fa-lock"></i>
                    Password
                </label>
                <div class="password-input">
                    <input type="password" 
                           id="password" 
                           name="password" 
                           placeholder="Enter your password" 
                           required 
                           autocomplete="current-password">
                    <button type="button" class="toggle-password">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
            </div>

            <div class="form-options">
                <label class="checkbox-label">
                    <input type="checkbox" name="remember">
                    <span>Remember me</span>
                </label>
                <a href="{{ url('/forgot-password') }}" class="forgot-link">
                    Forgot Password?
                </a>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-sign-in-alt"></i>
                Login
            </button>
        </form>

        <div class="form-divider">
            <span>or</span>
        </div>

        <button class="btn btn-secondary btn-switch" data-switch="fingerprint">
            <i class="fas fa-fingerprint"></i>
            Use Fingerprint Instead
        </button>
    </div>

    <div class="register-link">
        Don't have an account? 
        <a href="{{ url('/register') }}">Register Now</a>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/fingerprint.js') }}"></script>
<script>
    // Tab switching
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const tab = this.dataset.tab;
            
            // Update active tab button
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            
            // Update active tab content
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.remove('active');
            });
            document.getElementById(tab + '-tab').classList.add('active');
        });
    });

    // Switch buttons
    document.querySelectorAll('.btn-switch').forEach(btn => {
        btn.addEventListener('click', function() {
            const targetTab = this.dataset.switch;
            document.querySelector(`.tab-btn[data-tab="${targetTab}"]`).click();
        });
    });

    // Password toggle
    document.querySelector('.toggle-password')?.addEventListener('click', function() {
        const passwordInput = document.getElementById('password');
        const icon = this.querySelector('i');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    });
</script>
@endpush