@extends('layouts.app')

@section('title', 'Dashboard - SecureBank')

@section('content')
<div class="dashboard-container">
    <!-- Welcome Section -->
    <div class="welcome-section">
        <div class="welcome-text">
            <h1>Welcome back, {{ auth()->user()->name ?? 'John' }}! 👋</h1>
            <p>Here's what's happening with your accounts today</p>
        </div>
        <div class="welcome-actions">
            <button class="btn btn-primary" onclick="window.location.href='{{ url('/atm/withdraw') }}'">
                <i class="fas fa-money-bill-wave"></i>
                Quick Withdraw
            </button>
            <button class="btn btn-secondary" onclick="window.location.href='{{ url('/atm/transfer') }}'">
                <i class="fas fa-exchange-alt"></i>
                Transfer Money
            </button>
        </div>
    </div>

    <!-- Account Cards -->
    <div class="accounts-grid">
        <div class="account-card primary">
            <div class="account-header">
                <div class="account-icon">
                    <i class="fas fa-wallet"></i>
                </div>
                <div class="account-badge">Primary</div>
            </div>
            <div class="account-body">
                <p class="account-label">Savings Account</p>
                <h2 class="account-balance">$45,280.50</h2>
            </div>
        </div>

        <div class="account-card secondary">
            <div class="account-header">
                <div class="account-icon">
                    <i class="fas fa-credit-card"></i>
                </div>
                <div class="account-badge">Current</div>
            </div>
            <div class="account-body">
                <p class="account-label">Current Account</p>
                <h2 class="account-balance">$12,450.00</h2>
            </div>
        </div>

        <div class="account-card tertiary">
            <div class="account-header">
                <div class="account-icon">
                    <i class="fas fa-piggy-bank"></i>
                </div>
                <div class="account-badge">Gold</div>
            </div>
            <div class="account-body">
                <p class="account-label">Gold Account</p>
                <h2 class="account-balance">$78,900.25</h2>
            </div>
        </div>
    </div>

    <!-- Quick Actions Grid -->
    <div class="section-header">
        <h2>Quick Actions</h2>
        <p>Manage your finances with ease</p>
    </div>

    <div class="quick-actions-grid">
        <a href="{{ url('/atm/withdraw') }}" class="action-card">
            <div class="action-icon blue">
                <i class="fas fa-money-bill-wave"></i>
            </div>
            <h3>Withdraw Cash</h3>
            <p>Quick ATM withdrawal</p>
        </a>

        <a href="{{ url('/atm/transfer') }}" class="action-card">
            <div class="action-icon green">
                <i class="fas fa-exchange-alt"></i>
            </div>
            <h3>Transfer Money</h3>
            <p>Send to any account</p>
        </a>
    </div>

    <!-- Recent Transactions & Security -->
    <div class="dashboard-row">
        <!-- Recent Transactions -->
        <div class="card transactions-card">
            <div class="card-header">
                <h3><i class="fas fa-history"></i> Recent Transactions</h3>
                <a href="{{ url('/atm/history') }}" class="view-all">View All <i class="fas fa-arrow-right"></i></a>
            </div>
            <div class="card-body">
                <div class="transaction-item">
                    <div class="transaction-icon sent">
                        <i class="fas fa-arrow-up"></i>
                    </div>
                    <div class="transaction-details">
                        <p class="transaction-title">Transfer to John Smith</p>
                        <p class="transaction-date">Today, 2:30 PM</p>
                    </div>
                    <div class="transaction-amount negative">
                        -$250.00
                    </div>
                </div>

                <div class="transaction-item">
                    <div class="transaction-icon received">
                        <i class="fas fa-arrow-down"></i>
                    </div>
                    <div class="transaction-details">
                        <p class="transaction-title">Salary Deposit</p>
                        <p class="transaction-date">Yesterday, 9:00 AM</p>
                    </div>
                    <div class="transaction-amount positive">
                        +$5,000.00
                    </div>
                </div>

                <div class="transaction-item">
                    <div class="transaction-icon withdrawal">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                    <div class="transaction-details">
                        <p class="transaction-title">ATM Withdrawal</p>
                        <p class="transaction-date">Dec 10, 4:15 PM</p>
                    </div>
                    <div class="transaction-amount negative">
                        -$500.00
                    </div>
                </div>

                <div class="transaction-item">
                    <div class="transaction-icon received">
                        <i class="fas fa-arrow-down"></i>
                    </div>
                    <div class="transaction-details">
                        <p class="transaction-title">Refund from Amazon</p>
                        <p class="transaction-date">Dec 9, 11:20 AM</p>
                    </div>
                    <div class="transaction-amount positive">
                        +$89.99
                    </div>
                </div>

                <div class="transaction-item">
                    <div class="transaction-icon sent">
                        <i class="fas fa-arrow-up"></i>
                    </div>
                    <div class="transaction-details">
                        <p class="transaction-title">Netflix Subscription</p>
                        <p class="transaction-date">Dec 8, 12:00 PM</p>
                    </div>
                    <div class="transaction-amount negative">
                        -$15.99
                    </div>
                </div>
            </div>
        </div>

        
    </div>

    <!-- Statistics -->
    
</div>
@endsection

@push('scripts')
<script>
    // Animate numbers on scroll
    const observerOptions = {
        threshold: 0.5
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.animation = 'fadeInUp 0.6s ease-out';
            }
        });
    }, observerOptions);

    document.querySelectorAll('.account-card, .action-card, .stat-card').forEach(el => {
        observer.observe(el);
    });
</script>
@endpush