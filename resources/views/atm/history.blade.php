@extends('layouts.app')

@section('title', 'Transaction History - SecureBank')

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
                <h1><i class="fas fa-history"></i> Transaction History</h1>
                <p>View all your recent transactions and activities</p>
            </div>
        </div>
    </div>

    <!-- Filters Card -->
    <div class="filters-card">
        <div class="filter-group">
            <label><i class="fas fa-calendar"></i> Period</label>
            <select id="period-filter" class="filter-select">
                <option value="7">Last 7 Days</option>
                <option value="30" selected>Last 30 Days</option>
                <option value="90">Last 3 Months</option>
                <option value="180">Last 6 Months</option>
                <option value="365">Last Year</option>
                <option value="all">All Time</option>
            </select>
        </div>

        <div class="filter-group">
            <label><i class="fas fa-filter"></i> Type</label>
            <select id="type-filter" class="filter-select">
                <option value="all">All Types</option>
                <option value="withdrawal">Withdrawals</option>
                <option value="transfer">Transfers</option>
            </select>
        </div>

        <div class="filter-group">
            <label><i class="fas fa-wallet"></i> Account</label>
            <select id="account-filter" class="filter-select">
                <option value="all">All Accounts</option>
                <option value="savings">Savings Account</option>
                <option value="current">Current Account</option>
                <option value="gold">Gold Account</option>
            </select>
        </div>

        <div class="filter-group">
            <button class="btn btn-secondary btn-sm" id="reset-filters">
                <i class="fas fa-redo"></i>
                Reset
            </button>
        </div>
    </div>

    <!-- Transactions List -->
    <div class="transactions-container">
        <div class="transactions-header">
            <h2><i class="fas fa-list"></i> Recent Transactions</h2>
            <span class="transaction-count">Showing <strong>{{ $transactions->count() }}</strong> transactions</span>
        </div>

        <div class="transactions-list">
            @forelse($transactions as $transaction)
                <div class="transaction-item" data-type="{{ $transaction->transaction_type }}" data-account="{{ optional($transaction->fromAccount)->account_type }}" data-transaction-id="{{ $transaction->transaction_id }}" data-to-account-id="{{ $transaction->to_account_id ?? '' }}" data-recipient-name="{{ $transaction->transaction_type === 'transfer' && in_array($transaction->from_account_id, $accountIds) && $transaction->toAccount ? $transaction->toAccount->user->name : '' }}" data-sender-name="{{ $transaction->transaction_type === 'transfer' && !in_array($transaction->from_account_id, $accountIds) && $transaction->fromAccount ? $transaction->fromAccount->user->name : '' }}" data-status="{{ ucfirst($transaction->status) }}">
                    <div class="transaction-icon {{ $transaction->transaction_type === 'withdrawal' ? 'withdrawal' : (in_array($transaction->from_account_id, $accountIds) ? 'transfer' : 'deposit') }}">
                        @if($transaction->transaction_type === 'withdrawal')
                            <i class="fas fa-money-bill-wave"></i>
                        @elseif(in_array($transaction->from_account_id, $accountIds))
                            <i class="fas fa-arrow-up"></i>
                        @else
                            <i class="fas fa-arrow-down"></i>
                        @endif
                    </div>
                    <div class="transaction-details">
                        <h4 class="transaction-title">
                            @if($transaction->transaction_type === 'withdrawal')
                                ATM Withdrawal
                            @elseif(in_array($transaction->from_account_id, $accountIds))
                                Transfer
                            @else
                                Transfer Received
                            @endif
                        </h4>
                        <p class="transaction-meta">
                            <span><i class="fas fa-calendar"></i> {{ $transaction->transaction_date->format('M d, Y - g:i A') }}</span>
                            <span><i class="fas fa-wallet"></i> 
                                @if($transaction->transaction_type === 'withdrawal')
                                    {{ optional($transaction->fromAccount)->account_type ?? 'Account' }} Account
                                @elseif(in_array($transaction->from_account_id, $accountIds))
                                    {{ optional($transaction->toAccount)->account_type ?? 'Account' }} Account
                                @else
                                    {{ optional($transaction->fromAccount)->account_type ?? 'Account' }} Account
                                @endif
                            </span>
                        </p>
                    </div>
                    <div class="transaction-amount {{ in_array($transaction->from_account_id, $accountIds) ? 'negative' : 'positive' }}">
                        {{ in_array($transaction->from_account_id, $accountIds) ? '-' : '+' }}${{ number_format($transaction->amount, 2) }}
                    </div>
                    <button class="transaction-more" onclick="showTransactionDetails(this)">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            @empty
                <div style="text-align: center; padding: 40px; color: #999;">
                    <i class="fas fa-inbox" style="font-size: 48px; margin-bottom: 20px;"></i>
                    <p style="font-size: 16px;">No transactions yet</p>
                </div>
            @endforelse
        </div>
    </div>

</div>

<!-- Transaction Details Modal -->
<div class="modal" id="details-modal">
    <div class="modal-overlay"></div>
    <div class="modal-content">
        <div class="modal-header">
            <h3><i class="fas fa-receipt"></i> Transaction Details</h3>
            <button class="modal-close" id="close-modal">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div class="modal-body">
            <div class="details-container">
                <div class="detail-row">
                    <span class="detail-label">Transaction ID</span>
                    <span class="detail-value" id="detail-id">TXN-2024-001234</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Type</span>
                    <span class="detail-value" id="detail-type">Withdrawal</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Date & Time</span>
                    <span class="detail-value" id="detail-date">Dec 15, 2024 - 9:00 AM</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Account</span>
                    <span class="detail-value" id="detail-account">Savings Account</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Amount</span>
                    <span class="detail-value amount" id="detail-amount">$500.00</span>
                </div>
                <div class="detail-row" id="detail-recipient-row" style="display: none;">
                    <span class="detail-label" id="detail-transfer-label">Transferred To</span>
                    <span class="detail-value" id="detail-recipient"></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Status</span>
                    <span class="detail-value">
                        <span class="status-badge success" id="detail-status">Completed</span>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ url('js/history.js') }}"></script>
@endpush