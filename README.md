# Beacons of God Ministries

**_Shining God's Light in a Darkened World_**

A modern, full-featured Laravel web application for an online Christian ministry dedicated to sharing biblical truth, spiritual resources, and evangelical outreach.

---

## Overview

Beacons of God Ministries is a comprehensive digital platform designed to deliver spiritual guidance and biblical teaching through multiple content channels. The website serves as a hub for believers seeking deeper understanding of Scripture, spiritual encouragement, and accessible resources for personal growth in faith.

**Mission:** To illuminate the path of righteousness through the unwavering truth of Scripture, offering peace and clarity to all believers seeking spiritual guidance.

---

## Current System Status (February 8, 2026)

### Public Audience Experience
- **Home Page** includes dynamic featured content and recommended resources pulled from the DB.
- **Public Libraries**
  - `/videos` — Video library with category filters, featured filter, search, pagination, likes, comments, share, modal player, and engagement tracking.
  - `/books` — Book library with category filters, search, pagination, likes, comments, share, online reader, and downloads.
  - `/audios` — Audio library with category filters, search, pagination, likes, comments, share, inline player on list + full player on detail page.
- **Engagement**
  - Likes + comments are supported for videos, books, and audios.
  - **Video view counts** are **unique per device**. All other actions (plays, downloads, shares, reads) count every time.

### Admin Experience
- **Content Management** (videos, audios, documents)
- **Categories** per content type
- **Playlists** for video/audio content
- **Featured vs Recommended** are separate flags
- **Content Notifications** to subscribers when new content is uploaded
- **Email Campaigns** with audience selection, preview, and drafts

### Analytics & Tracking
- **Video analytics:** impressions, plays, watch time, shares, and unique views.
- **Audio/Book analytics:** plays, reads, downloads, shares (tracked in content events).
- **Download logs** for documents and audios.

---

## Key Features

### Content Delivery (Public)
- **Video Sermons** — YouTube integration with modal playback
- **Downloadable E-Books & Study Guides** — Public book library with online reader + download
- **Audio Teachings** — Public audio library with in-page playback + full player
- **Public Resource Libraries** — `/videos`, `/books`, `/audios` with search, category filters, pagination
- **Engagement** — Likes + comments on videos, books, audios

### Community Engagement
- **Email Newsletter Subscription**
- **Subscriber Management**
- **Email Campaigns** — Targeted campaigns with audience selection + preview
- **Content Notifications** — Notify subscribers on new content uploads

### Administration
- **Content Management System** — Manage videos, audio files, and downloadable resources
- **Categories** — Categories for videos, audios, documents
- **Playlists** — Playlists for videos/audios with ordering
- **Featured vs Recommended** — Separate flags for homepage placement
- **Settings & Configuration** — Customize ministry branding and preferences

### Analytics & Tracking
- **Video Analytics** — View counts (unique per device), impressions, shares, watch time
- **Audio/Book Analytics** — Plays, reads, shares, downloads (event tracking)
- **Downloads Log** — Tracks downloads for resources

---

## Tech Stack

### Backend
- **Framework:** Laravel 12+ (PHP modern framework)
- **Database:** MySQL (Eloquent ORM)
- **API:** RESTful architecture

### Frontend
- **CSS Framework:** Tailwind CSS (utility-first, customizable)
- **Bundler:** Vite
- **Icons:** Lucide
- **Template Engine:** Blade

---

## Requirements

- **PHP:** 8.2 or higher
- **Composer:** Latest version
- **Node.js:** 18+ (for npm dependencies)
- **MySQL/PostgreSQL:** Database server

---

## Quick Start

### 1. Install Dependencies
```bash
composer install
npm install
```

### 2. Environment Setup
```bash
cp .env.example .env
php artisan key:generate
```

### 3. Configure Database
Edit `.env` with your database credentials:
```env
DB_DATABASE=bgm
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Run Migrations
```bash
php artisan migrate
```

### 5. Build Assets
```bash
npm run dev
```

### 6. Start Development Server
```bash
php artisan serve
```

Visit `http://localhost:8000`

---

## Project Structure

```
BGM/
+-- app/Models/
¦   +-- User.php
¦   +-- video.php               # Sermon videos
¦   +-- audio.php               # Audio teachings
¦   +-- book.php                # E-books and PDFs
¦   +-- ContentCategory.php     # Categories
¦   +-- Playlist.php            # Playlists
¦   +-- ContentNotification.php # Content notification logs
¦   +-- EmailCampaign.php       # Email campaigns
¦   +-- VideoEvent.php          # Video analytics
¦   +-- ContentEvent.php        # Audio/Book analytics
¦   +-- ContentLike.php         # Likes
¦   +-- ContentComment.php      # Comments
+-- app/Http/Controllers/
¦   +-- Home/HomeController.php         # Home + public libraries
¦   +-- Content/ContentDownloadController.php
¦   +-- Content/ContentEngagementController.php
¦   +-- Content/PublicContentEngagementController.php
+-- database/migrations/
+-- resources/views/
¦   +-- home.blade.php
¦   +-- videos/
¦   +-- books/
¦   +-- audios/
+-- routes/web.php
```

---

## Public Routes (Important)

- `/` — Home
- `/videos` — Video library
- `/books` — Book library
- `/books/{book}` — Book reader
- `/audios` — Audio library
- `/audios/{audio}` — Audio player

---

## Admin Routes (Important)

All admin routes are prefixed with `/beacons/admin` and protected by `auth`.

- `/beacons/admin/videos`
- `/beacons/admin/audios`
- `/beacons/admin/documents`
- `/beacons/admin/categories`
- `/beacons/admin/playlists`
- `/beacons/admin/campaigns`
- `/beacons/admin/content-notifications`

---

## Homepage Sections

1. **Hero Section** — CTA buttons
2. **Mission Statement**
3. **Three Core Pillars** — Videos, Books, Audios (dynamic)
4. **Latest Messages** — Latest featured videos
5. **Ministry Resources** — 6 recommended books + 6 recommended audios
6. **Newsletter Signup**
7. **Footer**

---

## Features by Content Type

### Videos (Sermons)
- YouTube integration with modal playback
- Category tagging + playlist support
- Featured content + recommended content
- Engagement tracking (views, impressions, shares, watch time)
- Likes and comments

### Audio Teachings
- MP3 file storage
- Streaming capability (list + detail players)
- Series organization
- Recommended flag
- Likes and comments
- Download + share tracking

### Books & Study Guides
- PDF storage and delivery
- Category classification + series
- Online reader + downloads
- Recommended flag
- Likes and comments
- Usage analytics

---

## Where to Start (For New Contributors)

### 1. Home Page Logic
- `app/Http/Controllers/Home/HomeController.php`
- `resources/views/home.blade.php`

### 2. Public Libraries
- `resources/views/videos/index.blade.php`
- `resources/views/books/index.blade.php`
- `resources/views/audios/index.blade.php`

### 3. Engagement & Analytics
- `app/Http/Controllers/Content/ContentDownloadController.php`
- `app/Http/Controllers/Content/ContentEngagementController.php`
- `app/Http/Controllers/Content/PublicContentEngagementController.php`
- Models: `VideoEvent`, `ContentEvent`, `ContentLike`, `ContentComment`

### 4. Admin Content Management
- Controllers:
  - `app/Http/Controllers/Admin/Content/VideoController.php`
  - `app/Http/Controllers/Admin/Content/AudioController.php`
  - `app/Http/Controllers/Admin/Content/DocumentController.php`
- Views:
  - `resources/views/Admin/Content/*`

---

## Design System

**Brand Colors:**
- **Primary Blue:** `#0f2b5e`
- **Accent Gold:** `#d4af37`
- **Light Background:** `#f8fafc`

**Typography:**
- **Serif Font:** Playfair Display (headings)
- **Sans Font:** Lato (body text)

---

## Responsive Breakpoints

- **Mobile:** Below 768px
- **Tablet:** 768px - 1023px
- **Desktop:** 1024px+

---

## Security Features

- CSRF token protection
- SQL injection prevention (Eloquent ORM)
- XSS protection via Blade escaping
- Input validation and sanitization
- Environment variable handling

---

## Contact & Support

- **Email:** contact@beaconsofgod.org
- **Social Media:** YouTube, Facebook, Instagram
- **Location:** Global Online Ministry

---

## License

Proprietary software. Unauthorized copying or distribution is prohibited.

---

## Credits

Designed and developed with care for advancing God's Kingdom through digital ministry.

*Last Updated: February 8, 2026*
