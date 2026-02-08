# Analytics Documentation

## Event Tables

### Video Events (`video_events`)
- event_type: `impression`, `play`, `watch`, `share`
- view_count on `videos` is unique per device

### Content Events (`content_events`)
- audio events: `play`, `watch`, `share`, `download`
- book events: `read`, `share`, `download`

## Key Rules
- Video views count once per device.
- Plays, downloads, shares, reads always count (no dedupe).

## Admin Views
- Dashboard shows total metrics and trends.
- Analytics module provides:
  - Event logs
  - Audience profiles
  - Content insights

