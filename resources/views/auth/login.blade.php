@extends('layouts.auth')

@section('title', 'SecureBank - Login')

@section('content')
<div class="login-form">
    <div class="form-tabs">
        <button class="tab-btn active" data-tab="fingerprint">
            <i class="fas fa-fingerprint"></i>
            Fingerprint
        </button>
        <button class="tab-btn" data-tab="card">
            <i class="fas fa-credit-card"></i>
            Card & PIN
        </button>
    </div>

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
            <input type="hidden" name="latitude" id="fingerprint-latitude">
            <input type="hidden" name="longitude" id="fingerprint-longitude">
            

            <button type="button" class="btn btn-primary" id="scan-fingerprint">
                <i class="fas fa-fingerprint"></i>
                Scan Fingerprint
            </button>
        </form>

        <div class="form-divider">
            <span>or use backup method</span>
        </div>

        <button class="btn btn-secondary btn-switch" data-switch="card">
            <i class="fas fa-key"></i>
            Login with Card & PIN
        </button>
    </div>

    <div class="tab-content" id="card-tab">
        <form action="{{ url('/login') }}" method="POST" id="card-form">
            @csrf
            <input type="hidden" name="latitude" id="card-latitude">
            <input type="hidden" name="longitude" id="card-longitude">
            
            <div class="form-group">
                <label for="card_number">
                    <i class="fas fa-credit-card"></i>
                    Card Number
                </label>
                <input type="text" 
                       id="card_number" 
                       name="card_number" 
                       placeholder="0000 0000 0000 0000" 
                       required 
                       pattern="[0-9]*"
                       inputmode="numeric"
                       autocomplete="off">
            </div>

            <div class="form-group">
                <label for="pin">
                    <i class="fas fa-th"></i>
                    PIN Code
                </label>
                <div class="password-input">
                    <input type="password" 
                           id="pin" 
                           name="pin" 
                           placeholder="Enter 4-digit PIN" 
                           required 
                           maxlength="4"
                           pattern="[0-9]*"
                           inputmode="numeric"
                           autocomplete="off">
                    <button type="button" class="toggle-password">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn btn-primary" style="margin-top: 20px;">
                <i class="fas fa-sign-in-alt"></i>
                Enter
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
    
    </div>
@endsection

@push('scripts')
<script src="{{ asset('js/geolocation.js') }}"></script>
<script src="{{ asset('js/fingerprint.js') }}"></script>
<script>
    // Retry getting location with delay
    function captureLocation() {
        console.log('[Login] Attempting to capture location...');
        console.log('[Login] geoHandler available:', typeof geoHandler !== 'undefined');
        
        if (typeof geoHandler !== 'undefined' && geoHandler.isSupported) {
            geoHandler.getCurrentLocation()
                .then(location => {
                    console.log('[Login] ✓ Location captured:', location);
                    document.getElementById('fingerprint-latitude').value = location.latitude;
                    document.getElementById('fingerprint-longitude').value = location.longitude;
                    document.getElementById('card-latitude').value = location.latitude;
                    document.getElementById('card-longitude').value = location.longitude;
                })
                .catch(error => {
                    console.warn('[Login] Location permission denied or error:', error.message);
                });
        } else {
            console.warn('[Login] Geolocation not supported or handler not ready');
        }
    }

    // Try immediately
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', captureLocation);
    } else {
        captureLocation();
    }
    
    // Also try on window load as backup
    window.addEventListener('load', captureLocation);

    // Add form submit handler to verify and log
    document.getElementById('card-form')?.addEventListener('submit', function(e) {
        const lat = document.getElementById('card-latitude').value;
        const lon = document.getElementById('card-longitude').value;
        console.log('[Login] Form submitted with location:', { lat, lon });
        if (!lat || !lon) {
            console.warn('[Login] ⚠ Location not captured! Lat:', lat, 'Lon:', lon);
        }
    });

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

    // PIN toggle (Updated selector)
    document.querySelector('.toggle-password')?.addEventListener('click', function() {
        const pinInput = document.getElementById('pin');
        const icon = this.querySelector('i');
        
        if (pinInput.type === 'password') {
            pinInput.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            pinInput.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    });
</script>
@endpush