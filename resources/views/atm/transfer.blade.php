@extends('layouts.app')

@section('title', 'Transfer Money - SecureBank')

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
                <h1><i class="fas fa-exchange-alt"></i> Transfer Money</h1>
                <p>Send money to another account quickly and securely</p>
            </div>
        </div>
        <div class="page-actions">
            <button class="btn-icon" title="Help">
                <i class="fas fa-question-circle"></i>
            </button>
        </div>
    </div>

    <div class="atm-grid">
        <!-- Transfer Form Card -->
        <div class="atm-card main-card">
            <div class="card-header">
                <h3><i class="fas fa-paper-plane"></i> Transfer Details</h3>
                <span class="step-indicator">Step 1 of 2</span>
            </div>
            
            <form id="transfer-form" action="{{ url('/atm/transfer') }}" method="POST">
                @csrf
                
                <!-- From Account Selection -->
                <div class="form-section">
                    <label class="form-label">
                        <i class="fas fa-wallet"></i>
                        From Account
                    </label>
                    
                    <div class="account-selector">
                        @forelse($accounts as $account)
                            <div class="account-option" data-account="{{ $account->id }}" data-balance="{{ $account->balance }}">
                                <input type="radio" name="from_account_id" value="{{ $account->id }}" id="from-{{ $account->id }}" required>
                                <label for="from-{{ $account->id }}">
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

                <!-- To Account Input -->
                <div class="form-section">
                    <label class="form-label" for="to_account">
                        <i class="fas fa-user"></i>
                        To Account Number
                    </label>
                    
                    <input 
                        type="text" 
                        id="to_account" 
                        name="to_account_number" 
                        class="form-input" 
                        placeholder="Enter recipient's account number"
                        maxlength="20"
                        required>
                    
                    <div class="input-info">
                        <small><i class="fas fa-info-circle"></i> Enter the 20-digit account number</small>
                    </div>
                </div>

                <!-- Recipient Name (Optional Display) -->
                <div class="form-section" id="recipient-info" style="display: none;">
                    <div class="recipient-display">
                        <div class="recipient-avatar">
                            <i class="fas fa-user-circle"></i>
                        </div>
                        <div class="recipient-details">
                            <span class="recipient-label">Transfer to:</span>
                            <h4 class="recipient-name" id="recipient-name">-</h4>
                            <p class="recipient-bank" id="recipient-bank">-</p>
                        </div>
                    </div>
                </div>

                <!-- Amount Input -->
                <div class="form-section">
                    <label class="form-label" for="amount">
                        <i class="fas fa-dollar-sign"></i>
                        Transfer Amount
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
                            min="1"
                            max="10000"
                            required>
                    </div>
                    
                    <div class="input-info">
                        <small><i class="fas fa-info-circle"></i> Minimum: $1 | Maximum: $10,000 per transfer</small>
                    </div>
                </div>

                <!-- Quick Amount Buttons -->
                <div class="form-section">
                    <label class="form-label">Quick Amount</label>
                    <div class="quick-amounts">
                        <button type="button" class="quick-amount-btn" data-amount="100">$100</button>
                        <button type="button" class="quick-amount-btn" data-amount="250">$250</button>
                        <button type="button" class="quick-amount-btn" data-amount="500">$500</button>
                        <button type="button" class="quick-amount-btn" data-amount="1000">$1,000</button>
                        <button type="button" class="quick-amount-btn" data-amount="2500">$2,500</button>
                        <button type="button" class="quick-amount-btn" data-amount="5000">$5,000</button>
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
            <h3><i class="fas fa-check-circle"></i> Confirm Transfer</h3>
            <button class="modal-close" id="close-modal">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div class="modal-body">
            <div class="confirmation-details">
                <div class="confirmation-icon">
                    <i class="fas fa-exchange-alt"></i>
                </div>
                
                <div class="confirmation-info">
                    <div class="conf-row">
                        <span>From:</span>
                        <strong id="conf-from-account">-</strong>
                    </div>
                    <div class="conf-row">
                        <span>To:</span>
                        <strong id="conf-to-account">-</strong>
                    </div>
                    <div class="conf-divider"></div>
                    <div class="conf-row">
                        <span>Amount:</span>
                        <strong id="conf-amount" class="text-primary">$0.00</strong>
                    </div>
                    <div class="conf-row">
                        <span>Transfer Fee:</span>
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
            <button class="btn btn-primary" id="confirm-transfer">
                <i class="fas fa-check"></i>
                Confirm Transfer
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ url('js/transfer.js') }}"></script>
@endpush