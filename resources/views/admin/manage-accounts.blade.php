@extends('layouts.app')

@section('title', 'Manage Accounts - Admin Panel')

@section('content')
<div class="dashboard-container">
    <div class="welcome-section">
        <div class="welcome-text">
            <h1>Manage Accounts for {{ $user->name }}</h1>
            <p>Email: {{ $user->email }} | Phone: {{ $user->phone }}</p>
        </div>
    </div>

    <div class="transactions-card card" style="margin-bottom: 20px;">
        <div class="card-header section-header">
            <h2><i class="fas fa-plus"></i> Create New Account</h2>
            <p>Add a new account for this user</p>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.accounts.store', $user->id) }}" style="display: flex; gap: 10px; align-items: flex-end;">
                @csrf
                <div style="flex: 1;">
                    <label for="account_type">Account Type</label>
                    <select id="account_type" name="account_type" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                        <option value="">Select Account Type</option>
                        <option value="current">Current</option>
                        <option value="savings">Savings</option>
                        <option value="gold">Gold</option>
                    </select>
                </div>
                <div style="flex: 1;">
                    <label for="initial_balance">Initial Balance (optional)</label>
                    <input type="number" id="initial_balance" name="initial_balance" placeholder="0.00" step="0.01" min="0" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                </div>
                <button type="submit" class="btn btn-primary" style="margin-bottom: 0;">
                    <i class="fas fa-plus"></i> Create Account
                </button>
            </form>
        </div>
    </div>

    <div class="transactions-card card">
        <div class="card-header section-header">
            <h2><i class="fas fa-list"></i> User Accounts</h2>
            <p>View and manage accounts</p>
        </div>
        <div class="card-body">
            <div class="transactions-list">
                @forelse($user->accounts as $account)
                <div class="transaction-item">
                    <div class="transaction-icon received">
                        <i class="fas fa-{{ $account->account_type === 'savings' ? 'piggy-bank' : ($account->account_type === 'gold' ? 'crown' : 'credit-card') }}"></i>
                    </div>
                    <div class="transaction-details">
                        <div class="transaction-title">
                            {{ ucfirst($account->account_type) }} Account
                            <span style="display: inline-block; margin-left: 10px; padding: 4px 8px; border-radius: 4px; font-size: 0.85em; font-weight: bold;
                                {{ $account->isFrozen() ? 'background-color: #f8d7da; color: #721c24;' : 'background-color: #d4edda; color: #155724;' }}">
                                {{ $account->isFrozen() ? '❄️ Frozen' : '✓ Active' }}
                            </span>
                        </div>
                        <div class="transaction-date">Account Number: {{ substr($account->account_number, 0, 4) }}****{{ substr($account->account_number, -4) }}</div>
                        <div class="transaction-date">Balance: <strong>${{ number_format($account->balance, 2) }}</strong></div>
                        <div class="transaction-date">Opened: {{ $account->opened_date->format('M d, Y') }}</div>
                    </div>
                    <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                        <!-- Deposit Modal Button -->
                        <button class="btn btn-success" onclick="openDepositModal({{ $account->id }}, '{{ ucfirst($account->account_type) }}')">
                            <i class="fas fa-money-bill"></i> Deposit
                        </button>

                        <!-- Freeze/Unfreeze Button -->
                        @if($account->isFrozen())
                            <form action="{{ route('admin.accounts.unfreeze', [$user->id, $account->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn btn-warning">
                                    <i class="fas fa-unlock"></i> Unfreeze
                                </button>
                            </form>
                        @else
                            <form action="{{ route('admin.accounts.freeze', [$user->id, $account->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-lock"></i> Freeze
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
                @empty
                <div class="transaction-item">
                    <div class="transaction-details">
                        <div class="transaction-title">No accounts found. Create one to get started.</div>
                    </div>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <a href="{{ route('admin.index') }}" class="btn btn-secondary" style="margin-top: 20px;">
        <i class="fas fa-arrow-left"></i> Back to Admin Panel
    </a>
</div>

<!-- Deposit Modal -->
<div id="depositModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
    <div style="background-color: white; padding: 30px; border-radius: 8px; width: 90%; max-width: 400px;">
        <h2 id="modalTitle">Deposit to Account</h2>
        <form id="depositForm" method="POST">
            @csrf
            <div style="margin-bottom: 20px;">
                <label for="deposit_amount">Amount ($)</label>
                <input type="number" id="deposit_amount" name="amount" placeholder="0.00" step="0.01" min="0.01" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
            </div>
            <div style="display: flex; gap: 10px;">
                <button type="submit" class="btn btn-primary" style="flex: 1;">Deposit</button>
                <button type="button" class="btn btn-secondary" style="flex: 1;" onclick="closeDepositModal()">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openDepositModal(accountId, accountType) {
        const modal = document.getElementById('depositModal');
        const form = document.getElementById('depositForm');
        const title = document.getElementById('modalTitle');
        
        title.textContent = `Deposit to ${accountType} Account`;
        form.action = `/admin/users/{{ $user->id }}/accounts/${accountId}/deposit`;
        form.style.display = 'block';
        modal.style.display = 'flex';
    }

    function closeDepositModal() {
        const modal = document.getElementById('depositModal');
        modal.style.display = 'none';
    }

    // Close modal when clicking outside
    document.getElementById('depositModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeDepositModal();
        }
    });
</script>

<style>
    .btn {
        padding: 8px 12px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 0.9em;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        text-decoration: none;
    }

    .btn-primary {
        background-color: #007bff;
        color: white;
    }

    .btn-success {
        background-color: #28a745;
        color: white;
    }

    .btn-danger {
        background-color: #dc3545;
        color: white;
    }

    .btn-warning {
        background-color: #ffc107;
        color: black;
    }

    .btn-secondary {
        background-color: #6c757d;
        color: white;
    }

    .btn:hover {
        opacity: 0.9;
    }
</style>
@endsection
