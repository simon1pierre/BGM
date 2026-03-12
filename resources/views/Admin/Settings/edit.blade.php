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
        @if ($errors->any())
            <div class="alert alert-danger mb-4">
                <div class="fw-semibold mb-2">Please fix the errors below:</div>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @php
            $translations = [
                'en' => $settings->translationFor('en'),
                'fr' => $settings->translationFor('fr'),
                'rw' => $settings->translationFor('rw'),
            ];
        @endphp

        <div class="card mb-4">
            <div class="card-body">
                <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data">
                    @csrf
                    <ul class="nav nav-tabs mb-4" role="tablist">
                        <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-identity" type="button">Identity</button></li>
                        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-translations" type="button">Translations</button></li>
                        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-contact" type="button">Contact & Social</button></li>
                        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-livechat" type="button">Live Chat</button></li>
                        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-home" type="button">Homepage</button></li>
                        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-pages" type="button">Pages</button></li>
                        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-content" type="button">Content</button></li>
                        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-moderation" type="button">Moderation</button></li>
                        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-analytics" type="button">Analytics</button></li>
                        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-maintenance" type="button">Maintenance</button></li>
                        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-security" type="button">Security</button></li>
                        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-email" type="button">Email</button></li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="tab-identity" role="tabpanel">
                            <div class="row g-3">
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
                            </div>
                        </div>

                        <div class="tab-pane fade" id="tab-translations" role="tabpanel">
                            <div class="row g-3">
                                @foreach (['en' => 'English', 'fr' => 'French', 'rw' => 'Kinyarwanda'] as $locale => $label)
                                    <div class="col-12">
                                        <h6 class="fw-semibold text-muted">{{ $label }}</h6>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Site Name ({{ strtoupper($locale) }})</label>
                                        <input type="text" name="site_name_{{ $locale }}" value="{{ old('site_name_'.$locale, $translations[$locale]?->site_name) }}" class="form-control">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Tagline ({{ strtoupper($locale) }})</label>
                                        <input type="text" name="site_tagline_{{ $locale }}" value="{{ old('site_tagline_'.$locale, $translations[$locale]?->site_tagline) }}" class="form-control">
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label fw-semibold">Site Description ({{ strtoupper($locale) }})</label>
                                        <textarea name="site_description_{{ $locale }}" class="form-control" rows="2">{{ old('site_description_'.$locale, $translations[$locale]?->site_description) }}</textarea>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label fw-semibold">Footer Text ({{ strtoupper($locale) }})</label>
                                        <input type="text" name="footer_text_{{ $locale }}" value="{{ old('footer_text_'.$locale, $translations[$locale]?->footer_text) }}" class="form-control">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Hero Title ({{ strtoupper($locale) }})</label>
                                        <input type="text" name="hero_title_{{ $locale }}" value="{{ old('hero_title_'.$locale, $translations[$locale]?->hero_title) }}" class="form-control">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Hero Subtitle ({{ strtoupper($locale) }})</label>
                                        <input type="text" name="hero_subtitle_{{ $locale }}" value="{{ old('hero_subtitle_'.$locale, $translations[$locale]?->hero_subtitle) }}" class="form-control">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Primary CTA Label ({{ strtoupper($locale) }})</label>
                                        <input type="text" name="hero_primary_label_{{ $locale }}" value="{{ old('hero_primary_label_'.$locale, $translations[$locale]?->hero_primary_label) }}" class="form-control">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Secondary CTA Label ({{ strtoupper($locale) }})</label>
                                        <input type="text" name="hero_secondary_label_{{ $locale }}" value="{{ old('hero_secondary_label_'.$locale, $translations[$locale]?->hero_secondary_label) }}" class="form-control">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">About Page Title ({{ strtoupper($locale) }})</label>
                                        <input type="text" name="about_title_{{ $locale }}" value="{{ old('about_title_'.$locale, $translations[$locale]?->about_title) }}" class="form-control">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">About Page Subtitle ({{ strtoupper($locale) }})</label>
                                        <input type="text" name="about_subtitle_{{ $locale }}" value="{{ old('about_subtitle_'.$locale, $translations[$locale]?->about_subtitle) }}" class="form-control">
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label fw-semibold">About Page Body ({{ strtoupper($locale) }})</label>
                                        <textarea name="about_body_{{ $locale }}" class="form-control" rows="3">{{ old('about_body_'.$locale, $translations[$locale]?->about_body) }}</textarea>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Mission Title ({{ strtoupper($locale) }})</label>
                                        <input type="text" name="about_mission_title_{{ $locale }}" value="{{ old('about_mission_title_'.$locale, $translations[$locale]?->about_mission_title) }}" class="form-control">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Vision Title ({{ strtoupper($locale) }})</label>
                                        <input type="text" name="about_vision_title_{{ $locale }}" value="{{ old('about_vision_title_'.$locale, $translations[$locale]?->about_vision_title) }}" class="form-control">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Mission Body ({{ strtoupper($locale) }})</label>
                                        <textarea name="about_mission_body_{{ $locale }}" class="form-control" rows="2">{{ old('about_mission_body_'.$locale, $translations[$locale]?->about_mission_body) }}</textarea>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Vision Body ({{ strtoupper($locale) }})</label>
                                        <textarea name="about_vision_body_{{ $locale }}" class="form-control" rows="2">{{ old('about_vision_body_'.$locale, $translations[$locale]?->about_vision_body) }}</textarea>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label fw-semibold">Core Values ({{ strtoupper($locale) }})</label>
                                        <textarea name="about_values_{{ $locale }}" class="form-control" rows="2">{{ old('about_values_'.$locale, $translations[$locale]?->about_values) }}</textarea>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Resources Page Title ({{ strtoupper($locale) }})</label>
                                        <input type="text" name="resources_title_{{ $locale }}" value="{{ old('resources_title_'.$locale, $translations[$locale]?->resources_title) }}" class="form-control">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Resources Page Subtitle ({{ strtoupper($locale) }})</label>
                                        <input type="text" name="resources_subtitle_{{ $locale }}" value="{{ old('resources_subtitle_'.$locale, $translations[$locale]?->resources_subtitle) }}" class="form-control">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Resources CTA Label ({{ strtoupper($locale) }})</label>
                                        <input type="text" name="resources_cta_label_{{ $locale }}" value="{{ old('resources_cta_label_'.$locale, $translations[$locale]?->resources_cta_label) }}" class="form-control">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Contact Page Title ({{ strtoupper($locale) }})</label>
                                        <input type="text" name="contact_title_{{ $locale }}" value="{{ old('contact_title_'.$locale, $translations[$locale]?->contact_title) }}" class="form-control">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Contact Page Subtitle ({{ strtoupper($locale) }})</label>
                                        <input type="text" name="contact_subtitle_{{ $locale }}" value="{{ old('contact_subtitle_'.$locale, $translations[$locale]?->contact_subtitle) }}" class="form-control">
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label fw-semibold">Contact Form Intro ({{ strtoupper($locale) }})</label>
                                        <textarea name="contact_form_intro_{{ $locale }}" class="form-control" rows="2">{{ old('contact_form_intro_'.$locale, $translations[$locale]?->contact_form_intro) }}</textarea>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Events Page Title ({{ strtoupper($locale) }})</label>
                                        <input type="text" name="events_title_{{ $locale }}" value="{{ old('events_title_'.$locale, $translations[$locale]?->events_title) }}" class="form-control">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Events Page Subtitle ({{ strtoupper($locale) }})</label>
                                        <input type="text" name="events_subtitle_{{ $locale }}" value="{{ old('events_subtitle_'.$locale, $translations[$locale]?->events_subtitle) }}" class="form-control">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Featured Event Title ({{ strtoupper($locale) }})</label>
                                        <input type="text" name="events_feature_title_{{ $locale }}" value="{{ old('events_feature_title_'.$locale, $translations[$locale]?->events_feature_title) }}" class="form-control">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Featured Event Date ({{ strtoupper($locale) }})</label>
                                        <input type="text" name="events_feature_date_{{ $locale }}" value="{{ old('events_feature_date_'.$locale, $translations[$locale]?->events_feature_date) }}" class="form-control">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Featured Event Location ({{ strtoupper($locale) }})</label>
                                        <input type="text" name="events_feature_location_{{ $locale }}" value="{{ old('events_feature_location_'.$locale, $translations[$locale]?->events_feature_location) }}" class="form-control">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Featured Event Body ({{ strtoupper($locale) }})</label>
                                        <textarea name="events_feature_body_{{ $locale }}" class="form-control" rows="2">{{ old('events_feature_body_'.$locale, $translations[$locale]?->events_feature_body) }}</textarea>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Give Page Title ({{ strtoupper($locale) }})</label>
                                        <input type="text" name="give_title_{{ $locale }}" value="{{ old('give_title_'.$locale, $translations[$locale]?->give_title) }}" class="form-control">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Give Page Subtitle ({{ strtoupper($locale) }})</label>
                                        <input type="text" name="give_subtitle_{{ $locale }}" value="{{ old('give_subtitle_'.$locale, $translations[$locale]?->give_subtitle) }}" class="form-control">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Give CTA Label ({{ strtoupper($locale) }})</label>
                                        <input type="text" name="give_cta_label_{{ $locale }}" value="{{ old('give_cta_label_'.$locale, $translations[$locale]?->give_cta_label) }}" class="form-control">
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="tab-pane fade" id="tab-contact" role="tabpanel">
                            <div class="row g-3">
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
                                    <input type="text" placeholder="Url here...." name="youtube_channel" value="{{ old('youtube_channel', $settings->youtube_channel) }}" class="form-control">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Facebook</label>
                                    <input type="text" name="facebook_url" placeholder="url..." value="{{ old('facebook_url', $settings->facebook_url) }}" class="form-control">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Instagram</label>
                                    <input type="text" placeholder="url..." name="instagram_url" value="{{ old('instagram_url', $settings->instagram_url) }}" class="form-control">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Twitter / X</label>
                                    <input type="text" placeholder="url..." name="twitter_url" value="{{ old('twitter_url', $settings->twitter_url) }}" class="form-control">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">TikTok</label>
                                    <input type="text" placeholder="url..." name="tiktok_url" value="{{ old('tiktok_url', $settings->tiktok_url) }}" class="form-control">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">WhatsApp</label>
                                    <input type="text" placeholder="url..." name="whatsapp_url" value="{{ old('whatsapp_url', $settings->whatsapp_url) }}" class="form-control">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Telegram</label>
                                    <input type="text" placeholder="url..." name="telegram_url" value="{{ old('telegram_url', $settings->telegram_url) }}" class="form-control">
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="tab-livechat" role="tabpanel">
                            <div class="row g-3">
                                <div class="col-md-4 d-flex align-items-end">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="live_chat_enabled" value="1" id="liveChatEnabled" {{ old('live_chat_enabled', $settings->live_chat_enabled) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-semibold" for="liveChatEnabled">
                                            Enable Tawk.to Chat
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Tawk Property ID</label>
                                    <input type="text" name="tawk_property_id" value="{{ old('tawk_property_id', $settings->tawk_property_id) }}" class="form-control" placeholder="e.g. 65b2xxxxxxx">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Tawk Widget ID</label>
                                    <input type="text" name="tawk_widget_id" value="{{ old('tawk_widget_id', $settings->tawk_widget_id) }}" class="form-control" placeholder="e.g. 1hmxxxxxxx">
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="tab-home" role="tabpanel">
                            <div class="row g-3">
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
                                        <label class="form-check-label fw-semibold" for="homeRecommendedEnabled">Show Recommended Section</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="tab-pages" role="tabpanel">
                            <div class="row g-3">
                                <div class="col-12">
                                    <h6 class="fw-semibold text-muted">About Page</h6>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">About Title</label>
                                    <input type="text" name="about_title" value="{{ old('about_title', $settings->about_title) }}" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">About Subtitle</label>
                                    <input type="text" name="about_subtitle" value="{{ old('about_subtitle', $settings->about_subtitle) }}" class="form-control">
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label fw-semibold">About Body</label>
                                    <textarea name="about_body" class="form-control" rows="3">{{ old('about_body', $settings->about_body) }}</textarea>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Mission Title</label>
                                    <input type="text" name="about_mission_title" value="{{ old('about_mission_title', $settings->about_mission_title) }}" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Vision Title</label>
                                    <input type="text" name="about_vision_title" value="{{ old('about_vision_title', $settings->about_vision_title) }}" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Mission Body</label>
                                    <textarea name="about_mission_body" class="form-control" rows="2">{{ old('about_mission_body', $settings->about_mission_body) }}</textarea>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Vision Body</label>
                                    <textarea name="about_vision_body" class="form-control" rows="2">{{ old('about_vision_body', $settings->about_vision_body) }}</textarea>
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label fw-semibold">Core Values</label>
                                    <textarea name="about_values" class="form-control" rows="2">{{ old('about_values', $settings->about_values) }}</textarea>
                                </div>

                                <div class="col-12">
                                    <h6 class="fw-semibold text-muted mt-2">Resources Page</h6>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Resources Title</label>
                                    <input type="text" name="resources_title" value="{{ old('resources_title', $settings->resources_title) }}" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Resources Subtitle</label>
                                    <input type="text" name="resources_subtitle" value="{{ old('resources_subtitle', $settings->resources_subtitle) }}" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Resources CTA Label</label>
                                    <input type="text" name="resources_cta_label" value="{{ old('resources_cta_label', $settings->resources_cta_label) }}" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Resources CTA URL</label>
                                    <input type="text" name="resources_cta_url" value="{{ old('resources_cta_url', $settings->resources_cta_url) }}" class="form-control">
                                </div>

                                <div class="col-12">
                                    <h6 class="fw-semibold text-muted mt-2">Contact Page</h6>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Contact Title</label>
                                    <input type="text" name="contact_title" value="{{ old('contact_title', $settings->contact_title) }}" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Contact Subtitle</label>
                                    <input type="text" name="contact_subtitle" value="{{ old('contact_subtitle', $settings->contact_subtitle) }}" class="form-control">
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label fw-semibold">Contact Form Intro</label>
                                    <textarea name="contact_form_intro" class="form-control" rows="2">{{ old('contact_form_intro', $settings->contact_form_intro) }}</textarea>
                                </div>

                                <div class="col-12">
                                    <h6 class="fw-semibold text-muted mt-2">Events Page</h6>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Events Title</label>
                                    <input type="text" name="events_title" value="{{ old('events_title', $settings->events_title) }}" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Events Subtitle</label>
                                    <input type="text" name="events_subtitle" value="{{ old('events_subtitle', $settings->events_subtitle) }}" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Featured Event Title</label>
                                    <input type="text" name="events_feature_title" value="{{ old('events_feature_title', $settings->events_feature_title) }}" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Featured Event Date</label>
                                    <input type="text" name="events_feature_date" value="{{ old('events_feature_date', $settings->events_feature_date) }}" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Featured Event Location</label>
                                    <input type="text" name="events_feature_location" value="{{ old('events_feature_location', $settings->events_feature_location) }}" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Featured Event Body</label>
                                    <textarea name="events_feature_body" class="form-control" rows="2">{{ old('events_feature_body', $settings->events_feature_body) }}</textarea>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Featured Event URL</label>
                                    <input type="text" name="events_feature_url" value="{{ old('events_feature_url', $settings->events_feature_url) }}" class="form-control">
                                </div>

                                <div class="col-12">
                                    <h6 class="fw-semibold text-muted mt-2">Give Page</h6>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Give Title</label>
                                    <input type="text" name="give_title" value="{{ old('give_title', $settings->give_title) }}" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Give Subtitle</label>
                                    <input type="text" name="give_subtitle" value="{{ old('give_subtitle', $settings->give_subtitle) }}" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Give CTA Label</label>
                                    <input type="text" name="give_cta_label" value="{{ old('give_cta_label', $settings->give_cta_label) }}" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Give CTA URL</label>
                                    <input type="text" name="give_cta_url" value="{{ old('give_cta_url', $settings->give_cta_url) }}" class="form-control">
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="tab-content" role="tabpanel">
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold">Default Per Page</label>
                                    <input type="number" name="per_page_default" value="{{ old('per_page_default', $settings->per_page_default) }}" class="form-control">
                                </div>
                                <div class="col-md-3 d-flex align-items-end">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="auto_publish" value="1" id="autoPublish" {{ old('auto_publish', $settings->auto_publish) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-semibold" for="autoPublish">Auto Publish New Content</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="tab-moderation" role="tabpanel">
                            <div class="row g-3">
                                <div class="col-md-3 d-flex align-items-end">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="comments_auto_approve" value="1" id="commentsAutoApprove" {{ old('comments_auto_approve', $settings->comments_auto_approve) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-semibold" for="commentsAutoApprove">Auto Approve Comments</label>
                                    </div>
                                </div>
                                <div class="col-md-3 d-flex align-items-end">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="comments_require_name" value="1" id="commentsRequireName" {{ old('comments_require_name', $settings->comments_require_name) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-semibold" for="commentsRequireName">Require Name</label>
                                    </div>
                                </div>
                                <div class="col-md-3 d-flex align-items-end">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="comments_require_email" value="1" id="commentsRequireEmail" {{ old('comments_require_email', $settings->comments_require_email) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-semibold" for="commentsRequireEmail">Require Email</label>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label fw-semibold">Blocked Words (comma separated)</label>
                                    <textarea name="moderation_block_words" class="form-control" rows="2">{{ old('moderation_block_words', $settings->moderation_block_words) }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="tab-analytics" role="tabpanel">
                            <div class="row g-3">
                                <div class="col-md-3 d-flex align-items-end">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="analytics_enabled" value="1" id="analyticsEnabled" {{ old('analytics_enabled', $settings->analytics_enabled) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-semibold" for="analyticsEnabled">Enable Tracking</label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold">Retention Days</label>
                                    <input type="number" name="analytics_retention_days" value="{{ old('analytics_retention_days', $settings->analytics_retention_days) }}" class="form-control">
                                </div>
                                <div class="col-md-3 d-flex align-items-end">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="analytics_anonymize_ip" value="1" id="analyticsAnonymizeIp" {{ old('analytics_anonymize_ip', $settings->analytics_anonymize_ip) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-semibold" for="analyticsAnonymizeIp">Anonymize IP</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="tab-maintenance" role="tabpanel">
                            <div class="row g-3">
                                <div class="col-md-3 d-flex align-items-end">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="maintenance_mode" value="1" id="maintenanceMode" {{ old('maintenance_mode', $settings->maintenance_mode) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-semibold" for="maintenanceMode">Enable Maintenance Mode</label>
                                    </div>
                                </div>
                                <div class="col-md-9">
                                    <label class="form-label fw-semibold">Maintenance Message</label>
                                    <input type="text" name="maintenance_message" value="{{ old('maintenance_message', $settings->maintenance_message) }}" class="form-control">
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="tab-security" role="tabpanel">
                            <div class="row g-3">
                                <div class="col-md-3 d-flex align-items-end">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="force_admin_2fa" value="1" id="forceAdmin2fa" {{ old('force_admin_2fa', $settings->force_admin_2fa) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-semibold" for="forceAdmin2fa">Force Admin 2FA</label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold">Session Timeout (minutes)</label>
                                    <input type="number" name="session_timeout_minutes" value="{{ old('session_timeout_minutes', $settings->session_timeout_minutes) }}" class="form-control">
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="tab-email" role="tabpanel">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Notifications Email</label>
                                    <input type="email" name="notifications_email" value="{{ old('notifications_email', $settings->notifications_email) }}" class="form-control">
                                </div>
                                <div class="col-md-6 d-flex align-items-end">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="notifications_enabled" value="1" id="notificationsEnabled" {{ old('notifications_enabled', $settings->notifications_enabled) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-semibold" for="notificationsEnabled">Enable Email Notifications</label>
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
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-4">
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


