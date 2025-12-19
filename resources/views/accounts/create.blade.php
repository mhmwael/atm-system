@extends('layouts.app')

@section('content')
<div class="glass-card" style="max-width: 600px; margin: 0 auto;">
    <h2>Create New Account</h2>
    
    <form action="{{ route('accounts.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label>User</label>
            <select name="user_id" class="form-control" required>
                @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>Account Type</label>
            <select name="type" class="form-control" required>
                <option value="checking">Checking</option>
                <option value="savings">Savings</option>
            </select>
        </div>

        <div class="form-group">
            <label>Initial Balance</label>
            <input type="number" name="amount" step="0.01" class="form-control" value="0.00">
        </div>

        <button type="submit" class="btn btn-primary">Create Account</button>
        <a href="{{ route('accounts.index') }}" class="btn" style="background: #ddd;">Cancel</a>
    </form>
</div>
@endsection
