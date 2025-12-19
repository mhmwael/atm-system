@extends('layouts.app')

@section('content')
<div class="glass-card" style="max-width: 600px; margin: 0 auto;">
    <h2>Edit Account: {{ $account->account_number }}</h2>
    
    <form action="{{ route('accounts.update', $account) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label>User</label>
            <input type="text" class="form-control" value="{{ $account->user->name }}" disabled>
        </div>

        <div class="form-group">
            <label>Account Type</label>
            <select name="type" class="form-control" required>
                <option value="checking" {{ $account->type == 'checking' ? 'selected' : '' }}>Checking</option>
                <option value="savings" {{ $account->type == 'savings' ? 'selected' : '' }}>Savings</option>
            </select>
        </div>

        <div class="form-group">
            <label>Balance</label>
            <input type="number" name="balance" step="0.01" class="form-control" value="{{ $account->balance }}">
        </div>

        <button type="submit" class="btn btn-primary">Update Account</button>
        <a href="{{ route('accounts.index') }}" class="btn" style="background: #ddd;">Cancel</a>
    </form>
</div>
@endsection
