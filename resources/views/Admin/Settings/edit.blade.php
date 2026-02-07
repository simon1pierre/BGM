@extends('layouts.admin.app')
@section('contents')
<div class="nxl-content">
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title">
                <h5 class="m-b-10">System Settings</h5>
            </div>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item">Settings</li>
            </ul>
        </div>
    </div>

    <div class="main-content">
        @if (session('status'))
            <div class="alert alert-success mb-4">{{ session('status') }}</div>
        @endif

        <div class="card mb-4">
            <div class="card-body">
                <form method="POST" action="{{ route('admin.settings.update') }}" class="row g-3">
                    @csrf

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Site Name</label>
                        <input type="text" name="site_name" value="{{ old('site_name', $settings->site_name) }}" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Contact Email</label>
                        <input type="email" name="contact_email" value="{{ old('contact_email', $settings->contact_email) }}" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Notifications Email</label>
                        <input type="email" name="notifications_email" value="{{ old('notifications_email', $settings->notifications_email) }}" class="form-control">
                    </div>
                    <div class="col-md-6 d-flex align-items-end">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="notifications_enabled" value="1" id="notificationsEnabled" {{ old('notifications_enabled', $settings->notifications_enabled) ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="notificationsEnabled">
                                Enable Email Notifications
                            </label>
                        </div>
                    </div>

                    <div class="col-12">
                        <hr>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Mail Mailer</label>
                        <input type="text" readonly name="mail_mailer" value="{{ old('mail_mailer', $settings->mail_mailer) }}" class="form-control" placeholder="smtp">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Mail Host</label>
                        <input type="text" readonly name="mail_host" value="{{ old('mail_host', $settings->mail_host) }}" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Mail Port</label>
                        <input type="number" readonly name="mail_port" value="{{ old('mail_port', $settings->mail_port) }}" class="form-control">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Mail Username</label>
                        <input type="text" readonly name="mail_username" value="{{ old('mail_username', $settings->mail_username) }}" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Mail Password</label>
                        <input type="password" readonly name="mail_password" value="" class="form-control" placeholder="Leave blank to keep current">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Mail Scheme</label>
                        <input type="text" readonly name="mail_scheme" value="{{ old('mail_scheme', $settings->mail_scheme) }}" class="form-control" placeholder="smtp or smtps">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Mail From Address</label>
                        <input type="email" name="mail_from_address" value="{{ old('mail_from_address', $settings->mail_from_address) }}" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Mail From Name</label>
                        <input type="text" name="mail_from_name" value="{{ old('mail_from_name', $settings->mail_from_name) }}" class="form-control">
                    </div>

                    <div class="col-12 d-flex gap-2">
                        <button class="btn btn-primary">Save Settings</button>
                    </div>
                </form>
                <form method="POST" action="{{ route('admin.settings.test-email') }}" class="mt-2">
                    @csrf
                    <button class="btn btn-outline-primary">Send Test Email</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
