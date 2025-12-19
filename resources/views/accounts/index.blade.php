@extends('layouts.app')

@section('content')
<div class="glass-card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2>Accounts Management</h2>
        <a href="{{ route('accounts.create') }}" class="btn btn-primary">Create New Account</a>
    </div>

    @if(session('success'))
        <div style="background: #d1fae5; color: #065f46; padding: 10px; border-radius: 8px; margin-bottom: 20px;">
            {{ session('success') }}
        </div>
    @endif

    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="text-align: left; border-bottom: 2px solid #eee;">
                <th style="padding: 10px;">ID</th>
                <th style="padding: 10px;">User</th>
                <th style="padding: 10px;">Account Number</th>
                <th style="padding: 10px;">Type</th>
                <th style="padding: 10px;">Balance</th>
                <th style="padding: 10px;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($accounts as $account)
            <tr style="border-bottom: 1px solid #eee;">
                <td style="padding: 10px;">{{ $account->id }}</td>
                <td style="padding: 10px;">{{ $account->user->name }}</td>
                <td style="padding: 10px;">{{ $account->account_number }}</td>
                <td style="padding: 10px;">{{ ucfirst($account->type) }}</td>
                <td style="padding: 10px;">${{ number_format($account->balance, 2) }}</td>
                <td style="padding: 10px; display: flex; gap: 10px;">
                    <a href="{{ route('accounts.edit', $account) }}" class="btn" style="background:#f59e0b; color:white; font-size: 0.8rem; padding: 5px 10px;">Edit</a>
                    <form action="{{ route('accounts.destroy', $account) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn" style="background:#ef4444; color:white; font-size: 0.8rem; padding: 5px 10px;">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
