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
        @forelse($accounts as $account)
            <div class="account-card {{ $account->account_type === 'savings' ? 'primary' : ($account->account_type === 'current' ? 'secondary' : 'tertiary') }}">
                <div class="account-header">
                    <div class="account-icon">
                        <i class="fas fa-wallet"></i>
                    </div>
                    <div class="account-badge">{{ ucfirst($account->account_type) }}</div>
                </div>
                <div class="account-body">
                    <p class="account-label">{{ ucfirst($account->account_type) }} Account</p>
                    <h2 class="account-balance">${{ number_format($account->balance, 2) }}</h2>
                    <p class="account-number">{{ $account->account_number }}</p>
                </div>
            </div>
        @empty
            <div class="account-card primary">
                <div class="account-header">
                    <div class="account-icon">
                        <i class="fas fa-wallet"></i>
                    </div>
                    <div class="account-badge">Primary</div>
                </div>
                <div class="account-body">
                    <p class="account-label">No accounts</p>
                    <h2 class="account-balance">$0.00</h2>
                </div>
            </div>
        @endforelse
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
                @php
                    $userAccountIds = auth()->user()->accounts->pluck('id')->toArray();
                @endphp
                @forelse($transactions as $transaction)
                    <div class="transaction-item">
                        <div class="transaction-icon {{ $transaction->transaction_type === 'withdrawal' ? 'withdrawal' : (in_array($transaction->from_account_id, $userAccountIds) ? 'sent' : 'received') }}">
                            @if($transaction->transaction_type === 'withdrawal')
                                <i class="fas fa-money-bill-wave"></i>
                            @elseif(in_array($transaction->from_account_id, $userAccountIds))
                                <i class="fas fa-arrow-up"></i>
                            @else
                                <i class="fas fa-arrow-down"></i>
                            @endif
                        </div>
                        <div class="transaction-details">
                            <p class="transaction-title">{{ ucfirst($transaction->transaction_type) }}</p>
                            <p class="transaction-date">{{ $transaction->transaction_date->format('M d, g:i A') }}</p>
                            <p style="font-size: 12px; color: #999; margin-top: 4px;">
                                @if($transaction->transaction_type === 'withdrawal')
                                    ({{ $transaction->fromAccount->account_type ?? 'account' }} account)
                                @elseif(in_array($transaction->from_account_id, $userAccountIds))
                                    ({{ $transaction->toAccount->account_type ?? 'account' }} account)
                                @else
                                    ({{ $transaction->fromAccount->account_type ?? 'account' }} account)
                                @endif
                            </p>
                        </div>
                        <div class="transaction-amount {{ in_array($transaction->from_account_id, $userAccountIds) ? 'negative' : 'positive' }}">
                            {{ in_array($transaction->from_account_id, $userAccountIds) ? '-' : '+' }}${{ number_format($transaction->amount, 2) }}
                        </div>
                    </div>
                @empty
                    <div class="transaction-item">
                        <p style="text-align: center; color: #999; padding: 20px;">No transactions yet</p>
                    </div>
                @endforelse
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