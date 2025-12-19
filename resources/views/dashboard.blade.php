@extends('layouts.app')

@section('content')
<div class="glass-card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <h1>Dashboard</h1>
        <div>
            @if($user->profile_photo_path)
                <img src="{{ Storage::url($user->profile_photo_path) }}" alt="Profile" style="width: 50px; height: 50px; border-radius: 50%;">
            @else
                <div style="width: 50px; height: 50px; border-radius: 50%; background: #ddd; display: flex; align-items: center; justify-content: center;">
                    {{ substr($user->name, 0, 1) }}
                </div>
            @endif
        </div>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
        <!-- Balance Card -->
        <div class="glass-card" style="background: linear-gradient(135deg, rgba(37, 99, 235, 0.2), rgba(37, 99, 235, 0.05)); border-left: 5px solid var(--primary-color);">
            <h3 style="margin-top: 0; color: var(--primary-color);">Current Balance</h3>
            <!-- Ajax Target -->
            <h2 id="balance-display" style="font-size: 2rem; margin: 10px 0;">Loading...</h2>
            <button onclick="refreshBalance()" class="btn btn-primary" style="font-size: 0.8rem; padding: 5px 15px;">Refresh (Ajax)</button>
        </div>

        <!-- Stats: Deposits -->
        <div class="glass-card" style="background: rgba(34, 197, 94, 0.1); border-left: 5px solid #22c55e;">
            <h3 style="margin-top: 0; color: #16a34a;">Total Deposits</h3>
            <h2 style="font-size: 1.8rem; margin: 10px 0;">+${{ number_format($stats['deposits'], 2) }}</h2>
            <p style="font-size: 0.8rem; color: #666;">All time activity</p>
        </div>

        <!-- Stats: Withdrawals -->
        <div class="glass-card" style="background: rgba(239, 68, 68, 0.1); border-left: 5px solid #ef4444;">
            <h3 style="margin-top: 0; color: #dc2626;">Total Withdrawals</h3>
            <h2 style="font-size: 1.8rem; margin: 10px 0;">-${{ number_format($stats['withdrawals'], 2) }}</h2>
            <p style="font-size: 0.8rem; color: #666;">All time activity</p>
        </div>
    </div>

    <h3 style="margin-top: 2rem;">Recent Transactions</h3>
    <table style="width: 100%; border-collapse: collapse; margin-top: 1rem;">
        <thead>
            <tr style="border-bottom: 2px solid #eee;">
                <th style="padding: 10px; text-align: left;">Date</th>
                <th style="padding: 10px; text-align: left;">Type</th>
                <th style="padding: 10px; text-align: left;">Description</th>
                <th style="padding: 10px; text-align: right;">Amount</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transactions as $transaction)
                <tr style="border-bottom: 1px solid #eee;">
                    <td style="padding: 10px;">{{ $transaction->created_at->format('M d, Y') }}</td>
                    <td style="padding: 10px;">{{ ucfirst($transaction->type) }}</td>
                    <td style="padding: 10px;">{{ $transaction->description }}</td>
                    <td style="padding: 10px; text-align: right; color: {{ $transaction->type == 'deposit' ? 'green' : 'red' }};">
                        {{ $transaction->type == 'deposit' ? '+' : '-' }}${{ number_format($transaction->amount, 2) }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="padding: 20px; text-align: center;">No transactions found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    
    <div style="margin-top: 2rem; padding-top: 1rem; border-top: 1px solid #eee;">
        <h3>Upload Profile Photo</h3>
        <form action="{{ route('upload.photo') }}" method="POST" enctype="multipart/form-data" style="display: flex; gap: 10px; align-items: center;">
            @csrf
            <input type="file" name="photo" class="form-control" style="width: auto;">
            <button type="submit" class="btn btn-primary">Upload</button>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function refreshBalance() {
        const display = document.getElementById('balance-display');
        display.style.opacity = '0.5';
        
        fetch('/api/balance', {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            display.innerText = '$' + parseFloat(data.balance).toFixed(2);
            display.style.opacity = '1';
        })
        .catch(error => {
            console.error('Error:', error);
            display.innerText = 'Error';
        });
    }

    // Load on page load
    document.addEventListener('DOMContentLoaded', refreshBalance);
</script>
@endsection
