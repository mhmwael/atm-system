@extends('layouts.app')

@section('content')
<div class="glass-card">
    <h1>Your Profile</h1>
    <div class="form-group">
        <label>Name</label>
        <div class="form-control">{{ $user->name }}</div>
    </div>
    <div class="form-group">
        <label>Email</label>
        <div class="form-control">{{ $user->email }}</div>
    </div>
    <div class="form-group">
        <label>Role</label>
        <div class="form-control">{{ $user->role }}</div>
    </div>

    <hr style="margin: 2rem 0; border: none; border-top: 1px solid #eee;">

    <h3>Biometric Security (ERD Feature)</h3>
    <div style="display: flex; justify-content: space-between; align-items: center; background: rgba(37, 99, 235, 0.05); padding: 15px; border-radius: 12px; border-left: 4px solid var(--primary-color);">
        <div>
            <strong>Fingerprint Status:</strong> 
            @if($user->fingerprints->count() > 0)
                <span style="color: green;">● Active</span>
                <p style="font-size: 0.8rem; color: #666; margin-top: 5px;">Last used: {{ $user->fingerprints->first()->last_used->diffForHumans() }}</p>
            @else
                <span style="color: #999;">Not Registered</span>
            @endif
        </div>
        @if($user->fingerprints->count() == 0)
            <form action="{{ route('fingerprint.register') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-primary" style="font-size: 0.8rem;">Register Fingerprint</button>
            </form>
        @endif
    </div>

    <div style="margin-top: 2rem;">
        <a href="{{ route('dashboard') }}" class="btn" style="background: #ddd; color: #333;">Back to Dashboard</a>
    </div>
</div>
@endsection
