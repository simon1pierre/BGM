@extends('layouts.admin.app')
@section('contents')
<div class="nxl-content">
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title">
                <h5 class="m-b-10">Notification Details</h5>
            </div>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item">Notifications</li>
            </ul>
        </div>
    </div>

    <div class="main-content">
        <div class="card">
            <div class="card-body">
                @php
                    $labels = [
                        'user_created' => 'New user account created',
                        'user_updated' => 'User account updated',
                        'user_status_toggled' => 'User status changed',
                        'user_deleted' => 'User account deleted',
                        'user_restored' => 'User account restored',
                        'password_reset' => 'Password reset by admin',
                        'subscriber_created' => 'New newsletter subscriber',
                    ];
                    $title = $labels[$notification->action] ?? 'System update';
                @endphp

                <h5 class="mb-3">{{ $title }}</h5>
                <div class="text-muted mb-3">
                    {{ $notification->created_at?->toDayDateTimeString() }}
                </div>

                <div class="mb-3">
                    <strong>Actor:</strong>
                    <span>{{ $notification->actorUser?->email ?? 'Guest' }}</span>
                </div>

                <div class="mb-3">
                    <strong>Action:</strong>
                    <span>{{ $notification->action }}</span>
                </div>

                @if (!empty($notification->meta))
                    <div class="mb-3">
                        <strong>Details:</strong>
                        <ul class="mt-2">
                            @foreach ($notification->meta as $key => $value)
                                <li><strong>{{ ucfirst(str_replace('_', ' ', $key)) }}:</strong> {{ is_scalar($value) ? $value : json_encode($value) }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <a href="{{ route('admin.dashboard') }}" class="btn btn-light">Back</a>
            </div>
        </div>
    </div>
</div>
@endsection
