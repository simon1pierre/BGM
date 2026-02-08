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
                <form method="POST" action="{{ route('admin.settings.update') }}" class="row g-3" enctype="multipart/form-data">
                    @csrf

                    <div class="col-12">
                        <h6 class="fw-semibold">A. Site Identity</h6>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Site Name</label>
                        <input type="text" name="site_name" value="{{ old('site_name', $settings->site_name) }}" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Tagline</label>
                        <input type="text" name="site_tagline" value="{{ old('site_tagline', $settings->site_tagline) }}" class="form-control">
                    </div>
                    <div class="col-md-12">
                        <label class="form-label fw-semibold">Site Description</label>
                        <textarea name="site_description" class="form-control" rows="2">{{ old('site_description', $settings->site_description) }}</textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Logo</label>
                        <input type="file" name="logo" class="form-control" accept="image/*">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Favicon</label>
                        <input type="file" name="favicon" class="form-control" accept="image/*">
                    </div>

                    <div class="col-12">
                        <hr>
                        <h6 class="fw-semibold">B. Contact & Social</h6>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Contact Email</label>
                        <input type="email" name="contact_email" value="{{ old('contact_email', $settings->contact_email) }}" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Phone</label>
                        <input type="text" name="contact_phone" value="{{ old('contact_phone', $settings->contact_phone) }}" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Address</label>
                        <input type="text" name="contact_address" value="{{ old('contact_address', $settings->contact_address) }}" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">YouTube</label>
                        <input type="text" name="youtube_channel" value="{{ old('youtube_channel', $settings->youtube_channel) }}" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Facebook</label>
                        <input type="text" name="facebook_url" value="{{ old('facebook_url', $settings->facebook_url) }}" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Instagram</label>
                        <input type="text" name="instagram_url" value="{{ old('instagram_url', $settings->instagram_url) }}" class="form-control">
                    </div>

                    <div class="col-12">
                        <hr>
                        <h6 class="fw-semibold">C. Homepage Controls</h6>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Hero Title</label>
                        <input type="text" name="hero_title" value="{{ old('hero_title', $settings->hero_title) }}" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Hero Subtitle</label>
                        <input type="text" name="hero_subtitle" value="{{ old('hero_subtitle', $settings->hero_subtitle) }}" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Primary CTA Label</label>
                        <input type="text" name="hero_primary_label" value="{{ old('hero_primary_label', $settings->hero_primary_label) }}" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Primary CTA URL</label>
                        <input type="text" name="hero_primary_url" value="{{ old('hero_primary_url', $settings->hero_primary_url) }}" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Secondary CTA Label</label>
                        <input type="text" name="hero_secondary_label" value="{{ old('hero_secondary_label', $settings->hero_secondary_label) }}" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Secondary CTA URL</label>
                        <input type="text" name="hero_secondary_url" value="{{ old('hero_secondary_url', $settings->hero_secondary_url) }}" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Featured Videos Limit</label>
                        <input type="number" name="home_featured_video_limit" value="{{ old('home_featured_video_limit', $settings->home_featured_video_limit) }}" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Recommended Books Limit</label>
                        <input type="number" name="home_recommended_books_limit" value="{{ old('home_recommended_books_limit', $settings->home_recommended_books_limit) }}" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Recommended Audios Limit</label>
                        <input type="number" name="home_recommended_audios_limit" value="{{ old('home_recommended_audios_limit', $settings->home_recommended_audios_limit) }}" class="form-control">
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="home_recommended_enabled" value="1" id="homeRecommendedEnabled" {{ old('home_recommended_enabled', $settings->home_recommended_enabled) ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="homeRecommendedEnabled">
                                Show Recommended Section
                            </label>
                        </div>
                    </div>

                    <div class="col-12">
                        <hr>
                        <h6 class="fw-semibold">D. Content Defaults</h6>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Default Per Page</label>
                        <input type="number" name="per_page_default" value="{{ old('per_page_default', $settings->per_page_default) }}" class="form-control">
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="auto_publish" value="1" id="autoPublish" {{ old('auto_publish', $settings->auto_publish) ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="autoPublish">
                                Auto Publish New Content
                            </label>
                        </div>
                    </div>

                    <div class="col-12">
                        <hr>
                        <h6 class="fw-semibold">E. Moderation</h6>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="comments_auto_approve" value="1" id="commentsAutoApprove" {{ old('comments_auto_approve', $settings->comments_auto_approve) ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="commentsAutoApprove">
                                Auto Approve Comments
                            </label>
                        </div>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="comments_require_name" value="1" id="commentsRequireName" {{ old('comments_require_name', $settings->comments_require_name) ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="commentsRequireName">
                                Require Name
                            </label>
                        </div>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="comments_require_email" value="1" id="commentsRequireEmail" {{ old('comments_require_email', $settings->comments_require_email) ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="commentsRequireEmail">
                                Require Email
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label fw-semibold">Blocked Words (comma separated)</label>
                        <textarea name="moderation_block_words" class="form-control" rows="2">{{ old('moderation_block_words', $settings->moderation_block_words) }}</textarea>
                    </div>

                    <div class="col-12">
                        <hr>
                        <h6 class="fw-semibold">F. Analytics</h6>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="analytics_enabled" value="1" id="analyticsEnabled" {{ old('analytics_enabled', $settings->analytics_enabled) ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="analyticsEnabled">
                                Enable Tracking
                            </label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Retention Days</label>
                        <input type="number" name="analytics_retention_days" value="{{ old('analytics_retention_days', $settings->analytics_retention_days) }}" class="form-control">
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="analytics_anonymize_ip" value="1" id="analyticsAnonymizeIp" {{ old('analytics_anonymize_ip', $settings->analytics_anonymize_ip) ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="analyticsAnonymizeIp">
                                Anonymize IP
                            </label>
                        </div>
                    </div>

                    <div class="col-12">
                        <hr>
                        <h6 class="fw-semibold">G. Maintenance</h6>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="maintenance_mode" value="1" id="maintenanceMode" {{ old('maintenance_mode', $settings->maintenance_mode) ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="maintenanceMode">
                                Enable Maintenance Mode
                            </label>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <label class="form-label fw-semibold">Maintenance Message</label>
                        <input type="text" name="maintenance_message" value="{{ old('maintenance_message', $settings->maintenance_message) }}" class="form-control">
                    </div>

                    <div class="col-12">
                        <hr>
                        <h6 class="fw-semibold">H. Security</h6>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="force_admin_2fa" value="1" id="forceAdmin2fa" {{ old('force_admin_2fa', $settings->force_admin_2fa) ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="forceAdmin2fa">
                                Force Admin 2FA
                            </label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Session Timeout (minutes)</label>
                        <input type="number" name="session_timeout_minutes" value="{{ old('session_timeout_minutes', $settings->session_timeout_minutes) }}" class="form-control">
                    </div>

                    <div class="col-12">
                        <hr>
                        <h6 class="fw-semibold">I. Email & Notifications</h6>
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
