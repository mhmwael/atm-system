@extends('layouts.app')

@section('title', 'Transaction History - SecureBank')

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
                <option value="deposit">Deposits</option>
                <option value="payment">Payments</option>
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
            <span class="transaction-count">Showing <strong>72</strong> transactions</span>
        </div>

        <div class="transactions-list">
            <!-- Transaction Item -->
            <div class="transaction-item" data-type="deposit" data-account="savings">
                <div class="transaction-icon deposit">
                    <i class="fas fa-arrow-down"></i>
                </div>
                <div class="transaction-details">
                    <h4 class="transaction-title">Salary Deposit</h4>
                    <p class="transaction-meta">
                        <span><i class="fas fa-calendar"></i> Dec 15, 2024 - 9:00 AM</span>
                        <span><i class="fas fa-wallet"></i> Savings Account</span>
                    </p>
                </div>
                <div class="transaction-amount positive">
                    +$5,000.00
                </div>
                <button class="transaction-more" onclick="showTransactionDetails(this)">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>

            <!-- Transaction Item -->
            <div class="transaction-item" data-type="transfer" data-account="current">
                <div class="transaction-icon transfer">
                    <i class="fas fa-exchange-alt"></i>
                </div>
                <div class="transaction-details">
                    <h4 class="transaction-title">Transfer to John Smith</h4>
                    <p class="transaction-meta">
                        <span><i class="fas fa-calendar"></i> Dec 14, 2024 - 2:30 PM</span>
                        <span><i class="fas fa-wallet"></i> Current Account</span>
                    </p>
                </div>
                <div class="transaction-amount negative">
                    -$250.00
                </div>
                <button class="transaction-more" onclick="showTransactionDetails(this)">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>

            <!-- Transaction Item -->
            <div class="transaction-item" data-type="withdrawal" data-account="savings">
                <div class="transaction-icon withdrawal">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
                <div class="transaction-details">
                    <h4 class="transaction-title">ATM Withdrawal</h4>
                    <p class="transaction-meta">
                        <span><i class="fas fa-calendar"></i> Dec 12, 2024 - 4:15 PM</span>
                        <span><i class="fas fa-wallet"></i> Savings Account</span>
                    </p>
                </div>
                <div class="transaction-amount negative">
                    -$500.00
                </div>
                <button class="transaction-more" onclick="showTransactionDetails(this)">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>

            <!-- Transaction Item -->
            <div class="transaction-item" data-type="payment" data-account="current">
                <div class="transaction-icon payment">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div class="transaction-details">
                    <h4 class="transaction-title">Amazon Purchase</h4>
                    <p class="transaction-meta">
                        <span><i class="fas fa-calendar"></i> Dec 11, 2024 - 6:45 PM</span>
                        <span><i class="fas fa-wallet"></i> Current Account</span>
                    </p>
                </div>
                <div class="transaction-amount negative">
                    -$89.99
                </div>
                <button class="transaction-more" onclick="showTransactionDetails(this)">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>

            <!-- Transaction Item -->
            <div class="transaction-item" data-type="deposit" data-account="savings">
                <div class="transaction-icon deposit">
                    <i class="fas fa-arrow-down"></i>
                </div>
                <div class="transaction-details">
                    <h4 class="transaction-title">Refund - Electronics Store</h4>
                    <p class="transaction-meta">
                        <span><i class="fas fa-calendar"></i> Dec 10, 2024 - 11:20 AM</span>
                        <span><i class="fas fa-wallet"></i> Savings Account</span>
                    </p>
                </div>
                <div class="transaction-amount positive">
                    +$299.00
                </div>
                <button class="transaction-more" onclick="showTransactionDetails(this)">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>

            <!-- Transaction Item -->
            <div class="transaction-item" data-type="payment" data-account="current">
                <div class="transaction-icon payment">
                    <i class="fas fa-credit-card"></i>
                </div>
                <div class="transaction-details">
                    <h4 class="transaction-title">Netflix Subscription</h4>
                    <p class="transaction-meta">
                        <span><i class="fas fa-calendar"></i> Dec 8, 2024 - 12:00 PM</span>
                        <span><i class="fas fa-wallet"></i> Current Account</span>
                    </p>
                </div>
                <div class="transaction-amount negative">
                    -$15.99
                </div>
                <button class="transaction-more" onclick="showTransactionDetails(this)">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>

            <!-- Transaction Item -->
            <div class="transaction-item" data-type="transfer" data-account="gold">
                <div class="transaction-icon transfer">
                    <i class="fas fa-exchange-alt"></i>
                </div>
                <div class="transaction-details">
                    <h4 class="transaction-title">Transfer to Sarah Johnson</h4>
                    <p class="transaction-meta">
                        <span><i class="fas fa-calendar"></i> Dec 7, 2024 - 3:15 PM</span>
                        <span><i class="fas fa-wallet"></i> Gold Account</span>
                    </p>
                </div>
                <div class="transaction-amount negative">
                    -$1,200.00
                </div>
                <button class="transaction-more" onclick="showTransactionDetails(this)">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>

            <!-- Transaction Item -->
            <div class="transaction-item" data-type="deposit" data-account="current">
                <div class="transaction-icon deposit">
                    <i class="fas fa-arrow-down"></i>
                </div>
                <div class="transaction-details">
                    <h4 class="transaction-title">Freelance Payment</h4>
                    <p class="transaction-meta">
                        <span><i class="fas fa-calendar"></i> Dec 5, 2024 - 10:30 AM</span>
                        <span><i class="fas fa-wallet"></i> Current Account</span>
                    </p>
                </div>
                <div class="transaction-amount positive">
                    +$2,500.00
                </div>
                <button class="transaction-more" onclick="showTransactionDetails(this)">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>

            <!-- Transaction Item -->
            <div class="transaction-item" data-type="withdrawal" data-account="current">
                <div class="transaction-icon withdrawal">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
                <div class="transaction-details">
                    <h4 class="transaction-title">ATM Withdrawal</h4>
                    <p class="transaction-meta">
                        <span><i class="fas fa-calendar"></i> Dec 4, 2024 - 7:00 PM</span>
                        <span><i class="fas fa-wallet"></i> Current Account</span>
                    </p>
                </div>
                <div class="transaction-amount negative">
                    -$300.00
                </div>
                <button class="transaction-more" onclick="showTransactionDetails(this)">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>

            <!-- Transaction Item -->
            <div class="transaction-item" data-type="payment" data-account="savings">
                <div class="transaction-icon payment">
                    <i class="fas fa-utensils"></i>
                </div>
                <div class="transaction-details">
                    <h4 class="transaction-title">Restaurant - Food Delivery</h4>
                    <p class="transaction-meta">
                        <span><i class="fas fa-calendar"></i> Dec 3, 2024 - 8:30 PM</span>
                        <span><i class="fas fa-wallet"></i> Savings Account</span>
                    </p>
                </div>
                <div class="transaction-amount negative">
                    -$45.50
                </div>
                <button class="transaction-more" onclick="showTransactionDetails(this)">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </div>

        <!-- Load More Button -->
        <div class="load-more-container">
            <button class="btn btn-outline" id="load-more-btn">
                <i class="fas fa-sync"></i>
                Load More Transactions
            </button>
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
                <div class="detail-row">
                    <span class="detail-label">Status</span>
                    <span class="detail-value">
                        <span class="status-badge success">Completed</span>
                    </span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Reference</span>
                    <span class="detail-value" id="detail-ref">REF-ABC-123456</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ url('js/history.js') }}"></script>
@endpush