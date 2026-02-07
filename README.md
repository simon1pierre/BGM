# Beacons of God Ministries

**_Shining God's Light in a Darkened World_**

A modern, full-featured Laravel web application for an online Christian ministry dedicated to sharing biblical truth, spiritual resources, and evangelical outreach.

---

## 🌟 Overview

Beacons of God Ministries is a comprehensive digital platform designed to deliver spiritual guidance and biblical teaching through multiple content channels. The website serves as a hub for believers seeking deeper understanding of Scripture, spiritual encouragement, and accessible resources for personal growth in faith.

**Mission:** To illuminate the path of righteousness through the unwavering truth of Scripture, offering peace and clarity to all believers seeking spiritual guidance.

---

## ✨ Key Features

### Content Delivery
- **Video Sermons** — Watch powerful, scripture-based messages that bring the Bible to life
- **Downloadable E-Books & Study Guides** — Access a curated library of PDF resources for biblical study
- **Audio Teachings** — Listen to sermons and teachings on-the-go for your commute or quiet time

### Community Engagement
- **Email Newsletter Subscription** — Receive weekly sermons, prayer points, and ministry updates
- **Subscriber Management** — Maintain an active community of engaged believers
- **Email Campaigns** — Organize and distribute targeted spiritual messages

### Administration
- **Content Management System** — Manage videos, audio files, and downloadable resources
- **Download Tracking** — Monitor resource engagement and usage patterns
- **Settings & Configuration** — Customize ministry branding and preferences

### Technical Excellence
- **Modern Responsive Design** — Optimized for desktop, tablet, and mobile devices
- **Progressive Web App (PWA) Ready** — Install as a home screen app
- **SEO Optimized** — Proper metadata, Open Graph, Twitter Card tags
- **Fast Performance** — Built with Vite for optimized asset bundling

---

## 🛠️ Tech Stack

### Backend
- **Framework:** Laravel 11+ (PHP modern framework)
- **Database:** MySQL (Eloquent ORM)
- **API:** RESTful architecture

### Frontend
- **CSS Framework:** Tailwind CSS (utility-first, customizable)
- **Bundler:** Vite (lightning-fast build tool)
- **Icons:** Lucide (beautiful SVG icon library)
- **Template Engine:** Blade (Laravel's powerful templating)

---

## 📋 Requirements

- **PHP:** 8.1 or higher
- **Composer:** Latest version
- **Node.js:** 18+ (for npm dependencies)
- **MySQL/PostgreSQL:** Database server

---

## 🚀 Quick Start

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
DB_DATABASE=beacons_of_god
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Run Migrations
```bash
php artisan migrate
php artisan migrate:seed
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

## 📁 Project Structure

```
BGM/
├── app/Models/
│   ├── User.php
│   ├── Video.php          # Sermon videos
│   ├── Audio.php          # Audio teachings
│   ├── Book.php           # E-books and PDFs
│   ├── Subscriber.php     # Newsletter subscribers
│   ├── EmailCampaign.php  # Email campaigns
│   └── DownloadsLog.php   # Analytics
├── database/
│   └── migrations/        # Database schema setup
├── resources/
│   ├── views/
│   │   └── home.blade.php # Main homepage
│   ├── css/               # Stylesheets
│   └── js/                # JavaScript
├── public/
│   ├── favicon.ico        # Browser tab icon
│   └── logo/              # Brand assets
└── storage/
    └── app/
        ├── private/       # Private downloads
        └── public/        # Public assets
```

---

## 🎨 Homepage Sections

1. **Hero Section** — Inspiring tagline with call-to-action buttons
2. **Mission Statement** — Ministry vision and values
3. **Three Core Pillars** — Videos, Books, Audio offerings
4. **Latest Messages** — Featured sermon videos
5. **Ministry Resources** — Downloadable guides
6. **Newsletter Signup** — Email subscription form
7. **Footer** — Links, contact info, social media

---

## 🎯 Features by Content Type

### Videos (Sermons)
- YouTube integration
- Duration and publish date
- Category tagging
- Engagement tracking

### Audio Teachings
- MP3 file storage
- Streaming capability
- Series organization
- Download tracking

### Books & Study Guides
- PDF storage and delivery
- Category classification
- Free download access
- Usage analytics

### Subscribers & Campaigns
- Double opt-in verification
- Segmentation support
- Email template management
- Campaign analytics

---

## 🎨 Design System

**Brand Colors:**
- **Primary Blue:** `#0f2b5e` (Deep Royal Blue)
- **Accent Gold:** `#d4af37` (Muted Gold)
- **Light Background:** `#f8fafc` (Soft Off-white)

**Typography:**
- **Serif Font:** Playfair Display (headings)
- **Sans Font:** Lato (body text)

---

## 📱 Responsive Breakpoints

- **Mobile:** Below 768px
- **Tablet:** 768px - 1023px
- **Desktop:** 1024px+

---

## 🔒 Security Features

- CSRF token protection
- SQL injection prevention (Eloquent ORM)
- XSS protection via Blade escaping
- Input validation and sanitization
- Environment variable handling

---

## 📞 Contact & Support

- **Email:** contact@beaconsofgod.org
- **Social Media:** YouTube, Facebook, Instagram
- **Location:** Global Online Ministry

---

## 📝 License

Proprietary software. Unauthorized copying or distribution is prohibited.

---

## 🙏 Credits

Designed and developed with care for advancing God's Kingdom through digital ministry.

**Made with ❤️ for Beacons of God Ministries**

---

*Last Updated: February 6, 2026*

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
