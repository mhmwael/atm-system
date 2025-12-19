@extends('layouts.app')

@section('title', 'Withdraw Money - SecureBank')

@push('styles')
<link rel="stylesheet" href="{{ url('css/atm.css') }}">
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
                
                <!-- Account Selection -->
                <div class="form-section">
                    <label class="form-label">
                        <i class="fas fa-wallet"></i>
                        Select Account
                    </label>
                    
                    <div class="account-selector">
                        <div class="account-option" data-account="savings" data-balance="45280.50">
                            <input type="radio" name="account" value="savings" id="acc-savings" required>
                            <label for="acc-savings">
                                <div class="account-option-header">
                                    <div class="account-option-icon savings">
                                        <i class="fas fa-piggy-bank"></i>
                                    </div>
                                    <div class="account-option-info">
                                        <h4>Savings Account</h4>
                                    </div>
                                    <div class="account-option-check">
                                        <i class="fas fa-check-circle"></i>
                                    </div>
                                </div>
                                <div class="account-option-balance">
                                    <span>Available Balance</span>
                                    <strong>$45,280.50</strong>
                                </div>
                            </label>
                        </div>

                        <div class="account-option" data-account="current" data-balance="12450.00">
                            <input type="radio" name="account" value="current" id="acc-current">
                            <label for="acc-current">
                                <div class="account-option-header">
                                    <div class="account-option-icon current">
                                        <i class="fas fa-wallet"></i>
                                    </div>
                                    <div class="account-option-info">
                                        <h4>Current Account</h4>
                                    </div>
                                    <div class="account-option-check">
                                        <i class="fas fa-check-circle"></i>
                                    </div>
                                </div>
                                <div class="account-option-balance">
                                    <span>Available Balance</span>
                                    <strong>$12,450.00</strong>
                                </div>
                            </label>
                        </div>

                        <div class="account-option" data-account="gold" data-balance="78900.25">
                            <input type="radio" name="account" value="gold" id="acc-gold">
                            <label for="acc-gold">
                                <div class="account-option-header">
                                    <div class="account-option-icon gold">
                                        <i class="fas fa-crown"></i>
                                    </div>
                                    <div class="account-option-info">
                                        <h4>Gold Account</h4>
                                    </div>
                                    <div class="account-option-check">
                                        <i class="fas fa-check-circle"></i>
                                    </div>
                                </div>
                                <div class="account-option-balance">
                                    <span>Available Balance</span>
                                    <strong>$78,900.25</strong>
                                </div>
                            </label>
                        </div>
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

                <!-- Transaction Note (Optional) -->
                <div class="form-section">
                    <label class="form-label" for="note">
                        <i class="fas fa-sticky-note"></i>
                        Transaction Note (Optional)
                    </label>
                    <textarea 
                        id="note" 
                        name="note" 
                        class="form-textarea" 
                        rows="3" 
                        placeholder="Add a note about this withdrawal..."></textarea>
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

                <div class="fingerprint-verification">
                    <div class="fingerprint-icon">
                        <i class="fas fa-fingerprint"></i>
                    </div>
                    <p>Place your finger on the sensor to confirm</p>
                    <button type="button" class="btn btn-outline" id="verify-fingerprint">
                        <i class="fas fa-fingerprint"></i>
                        Verify with Fingerprint
                    </button>
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
@endsection

@push('scripts')
<script src="{{ url('js/atm.js') }}"></script>
@endpush