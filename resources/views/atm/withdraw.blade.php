@extends('layouts.app')

@section('title', 'Withdraw Money - SecureBank')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/atm.css') }}">
@endpush

@section('content')
<div class="atm-container">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-title">
            <a href="{{ url('/dashboard') }}" class="back-btn">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h1><i class="fas fa-money-bill-wave"></i> Withdraw Money</h1>
                <p>Select account and enter amount to withdraw</p>
            </div>
        </div>
        <div class="page-actions">
            <button class="btn-icon" title="Help">
                <i class="fas fa-question-circle"></i>
            </button>
        </div>
    </div>

    <div class="atm-grid">
        <!-- Withdrawal Form Card -->
        <div class="atm-card main-card">
            <div class="card-header">
                <h3><i class="fas fa-credit-card"></i> Withdrawal Details</h3>
                <span class="step-indicator">Step 1 of 2</span>
            </div>
            
            <form id="withdraw-form" action="{{ url('/atm/withdraw') }}" method="POST">
                @csrf
                <input type="hidden" name="latitude" id="withdraw-latitude">
                <input type="hidden" name="longitude" id="withdraw-longitude">
                
                <!-- Account Selection -->
                <div class="form-section">
                    <label class="form-label">
                        <i class="fas fa-wallet"></i>
                        Select Account
                    </label>
                    
                    <div class="account-selector">
                        @forelse($accounts as $account)
                            <div class="account-option" data-account="{{ $account->id }}" data-balance="{{ $account->balance }}">
                                <input type="radio" name="account_id" value="{{ $account->id }}" id="acc-{{ $account->id }}" required>
                                <label for="acc-{{ $account->id }}">
                                    <div class="account-option-header">
                                        <div class="account-option-icon {{ $account->account_type }}">
                                            @if($account->account_type === 'savings')
                                                <i class="fas fa-piggy-bank"></i>
                                            @elseif($account->account_type === 'current')
                                                <i class="fas fa-wallet"></i>
                                            @else
                                                <i class="fas fa-crown"></i>
                                            @endif
                                        </div>
                                        <div class="account-option-info">
                                            <h4>{{ ucfirst($account->account_type) }} Account</h4>
                                        </div>
                                        <div class="account-option-check">
                                            <i class="fas fa-check-circle"></i>
                                        </div>
                                    </div>
                                    <div class="account-option-balance">
                                        <span>Available Balance</span>
                                        <strong>${{ number_format($account->balance, 2) }}</strong>
                                    </div>
                                </label>
                            </div>
                        @empty
                            <p style="text-align: center; color: #999; padding: 20px;">No accounts available</p>
                        @endforelse
                    </div>
                </div>

                <!-- Amount Input -->
                <div class="form-section">
                    <label class="form-label" for="amount">
                        <i class="fas fa-dollar-sign"></i>
                        Withdrawal Amount
                    </label>
                    
                    <div class="amount-input-wrapper">
                        <span class="currency-symbol">$</span>
                        <input 
                            type="number" 
                            id="amount" 
                            name="amount" 
                            class="amount-input" 
                            placeholder="0.00" 
                            step="0.01"
                            min="10"
                            max="5000"
                            required>
                    </div>
                    
                    <div class="input-info">
                        <small><i class="fas fa-info-circle"></i> Minimum: $10 | Maximum: $5,000 per transaction</small>
                    </div>
                </div>

                <!-- Quick Amount Buttons -->
                <div class="form-section">
                    <label class="form-label">Quick Amount</label>
                    <div class="quick-amounts">
                        <button type="button" class="quick-amount-btn" data-amount="50">$50</button>
                        <button type="button" class="quick-amount-btn" data-amount="100">$100</button>
                        <button type="button" class="quick-amount-btn" data-amount="200">$200</button>
                        <button type="button" class="quick-amount-btn" data-amount="500">$500</button>
                        <button type="button" class="quick-amount-btn" data-amount="1000">$1,000</button>
                        <button type="button" class="quick-amount-btn" data-amount="2000">$2,000</button>
                    </div>
                </div>
                

                <!-- Form Actions -->
                <div class="form-actions">
                    <button type="button" class="btn btn-secondary" onclick="window.location.href='{{ url('/dashboard') }}'">
                        <i class="fas fa-times"></i>
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-primary" id="submit-btn">
                        <i class="fas fa-arrow-right"></i>
                        Continue
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Confirmation Modal -->
<div class="modal" id="confirmation-modal">
    <div class="modal-overlay"></div>
    <div class="modal-content">
        <div class="modal-header">
            <h3><i class="fas fa-check-circle"></i> Confirm Withdrawal</h3>
            <button class="modal-close" id="close-modal">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div class="modal-body">
            <div class="confirmation-details">
                <div class="confirmation-icon">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
                
                <div class="confirmation-info">
                    <div class="conf-row">
                        <span>Account:</span>
                        <strong id="conf-account">-</strong>
                    </div>
                    <div class="conf-row">
                        <span>Amount:</span>
                        <strong id="conf-amount" class="text-primary">$0.00</strong>
                    </div>
                    <div class="conf-row">
                        <span>Transaction Fee:</span>
                        <strong id="conf-fee">$0.00</strong>
                    </div>
                    <div class="conf-divider"></div>
                    <div class="conf-row total">
                        <span>Total Deduction:</span>
                        <strong id="conf-total" class="text-primary">$0.00</strong>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="modal-footer">
            <button class="btn btn-secondary" id="cancel-modal">
                <i class="fas fa-times"></i>
                Cancel
            </button>
            <button class="btn btn-primary" id="confirm-withdrawal">
                <i class="fas fa-check"></i>
                Confirm Withdrawal
            </button>
        </div>
    </div>
</div>
@if(session('verify_pin'))
<!-- PIN Verification Modal -->
<div class="modal active" id="pin-verification-modal">
    <div class="modal-overlay"></div>
    <div class="modal-content">
        <div class="modal-header">
            <h3><i class="fas fa-shield-alt"></i> Security Check</h3>
            <button class="modal-close" id="close-pin-modal" onclick="document.getElementById('pin-verification-modal').classList.remove('active'); document.body.style.overflow='';">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div class="modal-body">
            <div class="confirmation-details">
                <div class="confirmation-icon">
                    <i class="fas fa-lock"></i>
                </div>
                
                <div class="confirmation-info">
                    <div class="conf-row">
                        <span>Account:</span>
                        <strong>
                            @php
                                $selected = $accounts->firstWhere('id', old('account_id'));
                            @endphp
                            {{ $selected ? ucfirst($selected->account_type).' Account' : '-' }}
                        </strong>
                    </div>
                    <div class="conf-row">
                        <span>Amount:</span>
                        <strong class="text-primary">${{ number_format((float)old('amount', 0), 2) }}</strong>
                    </div>
                    <div class="conf-row">
                        <span>Transaction Fee:</span>
                        <strong>$0.00</strong>
                    </div>
                    <div class="conf-divider"></div>
                    <div class="conf-row total">
                        <span>Total Deduction:</span>
                        <strong class="text-primary">${{ number_format((float)old('amount', 0), 2) }}</strong>
                    </div>
                </div>
            </div>
            
            <form method="POST" action="{{ url('/atm/withdraw') }}">
                @csrf
                <input type="hidden" name="account_id" value="{{ old('account_id') }}">
                <input type="hidden" name="amount" value="{{ old('amount') }}">
                <input type="hidden" name="latitude" value="{{ old('latitude') }}">
                <input type="hidden" name="longitude" value="{{ old('longitude') }}">
                
                <div class="form-section">
                    <label class="form-label" for="pin">
                        <i class="fas fa-key"></i>
                        Enter 4-Digit PIN
                    </label>
                    <input 
                        type="password" 
                        name="pin" 
                        id="pin" 
                        maxlength="4" 
                        placeholder="••••"
                        style="width: 100%; padding: 0.9rem 1rem; border: 2px solid var(--border-color); border-radius: 10px; font-size: 1.25rem; letter-spacing: 0.5rem; text-align: center;"
                        required 
                        autofocus 
                        autocomplete="off">
                </div>
                
                <div class="modal-footer">
                    <a href="{{ url('/atm/withdraw') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i>
                        Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-check"></i>
                        Verify & Proceed
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection

@push('scripts')
<script src="{{ asset('js/geolocation.js') }}"></script>
<script src="{{ url('js/atm.js') }}"></script>
<script>
    // Capture location when page loads
    function captureWithdrawLocation() {
        if (typeof geoHandler !== 'undefined' && geoHandler.isSupported) {
            geoHandler.getCurrentLocation()
                .then(location => {
                    document.getElementById('withdraw-latitude').value = location.latitude;
                    document.getElementById('withdraw-longitude').value = location.longitude;
                })
                .catch(error => {
                });
        } else {
        }
    }
    
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', captureWithdrawLocation);
    } else {
        captureWithdrawLocation();
    }
    window.addEventListener('load', captureWithdrawLocation);
</script>
@endpush
