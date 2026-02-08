# Architecture Overview

This document explains how the platform is organized, where the main flows live, and how to extend it safely.

## 1. Core Concepts

- **Content Types**: video, audio, book (document)
- **Categories**: shared taxonomy for all content types
- **Playlists**: used by videos and audios
- **Engagement**: likes, comments, shares, downloads, views, watch time

## 2. Core Models

- `video`, `audio`, `book`: primary content
- `ContentCategory`: taxonomy
- `Playlist`, `PlaylistItem`: playlist structure
- `ContentLike`, `ContentComment`: engagement on all content types
- `VideoEvent`: video analytics (impressions, plays, shares, watch)
- `ContentEvent`: audio/book analytics (play/read/share/download)
- `Subscriber`, `EmailCampaign`, `ContentNotification`: outreach

## 3. Controllers

### Public
- `Home/HomeController`: home page + public libraries
- `Content/ContentDownloadController`: public downloads, tracks download events
- `Content/ContentEngagementController`: likes/comments for all content
- `Content/PublicContentEngagementController`: analytics events for audio/book

### Admin
- `Admin/AdminController`: dashboard stats
- `Admin/Content/*`: CRUD for videos, audios, documents
- `Admin/Analytics/AnalyticsController`: insights and audience data
- `Admin/Settings/SettingsController`: system settings center

## 4. Analytics Pipeline

- Video events are stored in `video_events`
- Audio/Book events are stored in `content_events`
- Metrics are summarized in Admin dashboard and Analytics pages

## 5. Settings

The settings table controls site identity, homepage layout, moderation rules, analytics toggles, and more. See `docs/SETTINGS.md`.

## 6. Extending the System

- Add new content types by creating a model, migrations, and controller under `Admin/Content` and public views.
- Add new analytics by extending `VideoEvent` or `ContentEvent` and surfacing data in analytics controllers.

