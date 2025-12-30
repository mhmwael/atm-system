@extends('layouts.app')

@section('title', 'Admin - SecureBank')

@section('content')
<div class="dashboard-container">
    <div class="welcome-section">
        <div class="welcome-text">
            <h1>Admin Panel</h1>
            <p>Manage users simply</p>
        </div>
    </div>

    <div class="transactions-card card" style="margin-bottom: 20px;">
        <div class="card-header section-header">
            <h2><i class="fas fa-user-plus"></i> Add New User</h2>
            <p>Create a new user account</p>
        </div>
        <div class="card-body">
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                <i class="fas fa-user-plus"></i> Create New User
            </a>
        </div>
    </div>

    <div class="transactions-card card" style="margin-bottom: 20px;">
        <div class="card-header section-header">
            <h2><i class="fas fa-search"></i> Search Users</h2>
            <p>Find users by name or email</p>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.index') }}" style="display: flex; gap: 10px;">
                <input type="text" name="search" placeholder="Search by name or email..." value="{{ request('search') }}" style="flex: 1; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search"></i> Search
                </button>
                @if(request('search'))
                    <a href="{{ route('admin.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Clear
                    </a>
                @endif
            </form>
        </div>
    </div>

    <div class="transactions-card card">
        <div class="card-header section-header">
            <h2><i class="fas fa-users"></i> Users</h2>
            <p>Delete users if necessary</p>
        </div>
        <div class="card-body">
            <div class="transactions-list">
                @forelse($users as $user)
                <div class="transaction-item">
                    <div class="transaction-icon received">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="transaction-details">
                        <div class="transaction-title">{{ $user->name }}</div>
                        <div class="transaction-date">Email: {{ $user->email }}</div>
                        <div class="transaction-date">Phone: {{ $user->phone ?? 'N/A' }}</div>
                        <div class="transaction-date">Card: {{ substr($user->card_number, 0, 4) }}****{{ substr($user->card_number, -4) }}</div>
                        <div class="transaction-date">Accounts: {{ $user->accounts_count }}</div>
                    </div>
                    <div>
                        <a href="{{ route('admin.accounts.manage', $user->id) }}" class="btn btn-info" style="background-color: #17a2b8; color: white; text-decoration: none;">
                            <i class="fas fa-cog"></i> Manage Accounts
                        </a>
                        <form action="{{ route('admin.users.delete', $user->id) }}" method="POST" onsubmit="return confirm('Delete this user?');" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </form>
                    </div>
                </div>
                @empty
                <div class="transaction-item">
                    <div class="transaction-details">
                        <div class="transaction-title">No users found</div>
                    </div>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
