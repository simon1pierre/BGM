<?php

namespace Database\Seeders;

use App\Models\ContentCategory;
use App\Models\ContentTranslation;
use App\Models\MinistryLeader;
use App\\Models\\Video;
use App\Models\VideoSeries;
use Illuminate\Database\Seeder;

class VideoSeeder extends Seeder
{
    public function run(): void
    {
        $defaultCategory = ContentCategory::query()
            ->where('slug', 'inyigisho-z-ibyanditswe')
            ->orWhere('type', 'video')
            ->orWhere('type', 'all')
            ->orderByRaw("CASE WHEN slug = 'inyigisho-z-ibyanditswe' THEN 0 ELSE 1 END")
            ->first();

        $videos = [
            [
                'title_rw' => 'UKOMERETSA NIWE UKIZA',
                'title_en' => 'The One Who Wounds Is the One Who Heals',
                'title_fr' => 'Celui Qui Blesse Est Celui Qui Guerit',
                'description_rw' => "Inyigisho ivuga ku Mana ikosora kandi ikagarura ubuzima ku bayizera. Umubabaro wo gukosorwa urangira mu gukira.",
                'description_en' => 'A message about God who corrects and restores. Discipline may hurt for a moment, but it brings healing.',
                'description_fr' => "Un message sur Dieu qui corrige et restaure. La correction peut faire mal un moment, mais elle apporte la guerison.",
                'speaker' => 'Elie Niyonzima',
                'youtube_url' => 'https://youtu.be/FV_Z4cF1PcU',
                'series' => "Ubutumwa bw'Ububyutse n'Ivugurura",
            ],
            [
                'title_rw' => "Imbabazi n'Umurava Bye Kukuvaho",
                'title_en' => 'May Mercy and Diligence Not Depart from You',
                'title_fr' => 'Que la Misericorde et le Zele Ne Te Quittent Pas',
                'description_rw' => "Ubutumwa buhamagarira kwizirika ku mbabazi z'Imana no kubaho ubuzima bw'umurava mu rugendo rwo kwizera.",
                'description_en' => "An exhortation to hold fast to God's mercy and to live with diligence in the walk of faith.",
                'description_fr' => "Un appel a demeurer dans la misericorde de Dieu et a vivre avec zele dans la foi.",
                'speaker' => 'Elie Niyonzima',
                'youtube_url' => 'https://youtu.be/BkrK9VZb-3U',
                'series' => "Ubutumwa bw'Ububyutse n'Ivugurura",
            ],
            [
                'title_rw' => 'URUFATIRO NYAKURI',
                'title_en' => 'The True Foundation',
                'title_fr' => 'Le Vrai Fondement',
                'description_rw' => "Inyigisho ishimangira ko urufatiro rw'ukuri rw'ubuzima bwa gikristo ari Kristo n'ijambo rye ridahinduka.",
                'description_en' => 'A message emphasizing Christ and His unchanging word as the only sure foundation.',
                'description_fr' => "Un message qui affirme que le Christ et sa parole immuable sont le seul fondement sur.",
                'speaker' => 'Heritier Niyomutunzi',
                'youtube_url' => 'https://youtu.be/XBzjfewCW8s',
                'series' => "Kwitegura Kugaruka kwa Yesu",
            ],
            [
                'title_rw' => "Ubuntu bw'Imana bwabonetse",
                'title_en' => 'The Grace of God Has Appeared',
                'title_fr' => 'La Grace de Dieu Est Apparue',
                'description_rw' => "Ubutumwa bwerekana ubuntu bw'Imana butwigisha kubaho twera no gutegereza ibyiringiro byiza.",
                'description_en' => "A sermon on God's grace that trains us to live holy lives while awaiting blessed hope.",
                'description_fr' => "Une predication sur la grace de Dieu qui nous enseigne a vivre saintement en attendant la bienheureuse esperance.",
                'speaker' => 'Batamuriza Florence',
                'youtube_url' => 'https://youtu.be/dj0NwF9LFOk',
                'series' => "Ubutumwa bw'Ububyutse n'Ivugurura",
            ],
            [
                'title_rw' => "Nigute umuntu yakwandikwa mu gitabo cy'ubugingo?",
                'title_en' => 'How Can a Person Be Written in the Book of Life?',
                'title_fr' => 'Comment Etre Inscrit Dans le Livre de Vie ?',
                'description_rw' => "Inyigisho isobanura inzira yo kwihana, kwizera no kuguma muri Kristo kugira ngo umuntu abe mu gitabo cy'ubugingo.",
                'description_en' => 'A message on repentance, faith, and abiding in Christ to be found in the Book of Life.',
                'description_fr' => "Un message sur la repentance, la foi et la communion avec le Christ pour etre trouve dans le Livre de Vie.",
                'speaker' => 'Ngabo Gasore',
                'youtube_url' => 'https://youtu.be/uoCF8kTr35U',
                'series' => "Daniyeli n'Ibyahishuwe",
            ],
            [
                'title_rw' => 'Umwuka ni we utanga ubugingo, umubiri nta cyo umaze',
                'title_en' => 'The Spirit Gives Life; The Flesh Profits Nothing',
                'title_fr' => "L'Esprit Donne la Vie; la Chair Ne Sert de Rien",
                'description_rw' => "Ubutumwa bugaragaza ko ubuzima nyakuri buturuka ku Mwuka Wera, kandi ko umuntu adakwiriye kwiringira imbaraga ze bwite.",
                'description_en' => 'A teaching that true life comes through the Holy Spirit, not human strength.',
                'description_fr' => "Un enseignement montrant que la vraie vie vient du Saint-Esprit et non de la force humaine.",
                'speaker' => 'Elie Niyonzima',
                'youtube_url' => 'https://youtu.be/68M7y31FAcI',
                'series' => "Yesu Umutambyi Mukuru",
            ],
            [
                'title_rw' => "AKAGA MU GIHE CY'UBUSAZA",
                'title_en' => 'Danger in Old Age',
                'title_fr' => 'Le Danger au Temps de la Vieillesse',
                'description_rw' => "Inyigisho ishingiye kuri Zaburi 71:9, ihamagarira kwizirika ku Mana no mu busaza igihe intege zigabanutse.",
                'description_en' => "A Psalm 71:9 based sermon calling believers to cling to God in old age when strength declines.",
                'description_fr' => "Une predication basee sur le Psaume 71:9, appelant a s'attacher a Dieu meme dans la vieillesse.",
                'speaker' => 'Tumukunde Yves',
                'youtube_url' => 'https://youtu.be/zEq6ufmmASA',
                'series' => "Kwitegura Kugaruka kwa Yesu",
            ],
            [
                'title_rw' => 'UBUBATA',
                'title_en' => 'Bondage',
                'title_fr' => 'La Servitude',
                'description_rw' => "Ubutumwa busobanura ububata bw'ibyaha n'uburyo Kristo atanga umudendezo nyakuri ku bamwizera.",
                'description_en' => 'A message exposing bondage to sin and the true freedom found in Christ.',
                'description_fr' => "Un message sur la servitude du peche et la vraie liberte en Jesus-Christ.",
                'speaker' => 'Tumukunde Yves',
                'youtube_url' => 'https://youtu.be/4eG2gBjoSDY',
                'series' => "Ubutumwa bw'Ububyutse n'Ivugurura",
            ],
        ];

        foreach ($videos as $index => $entry) {
            $youtubeId = $this->extractYoutubeId($entry['youtube_url']);
            if (!$youtubeId) {
                continue;
            }

            $speaker = trim($entry['speaker']);

            MinistryLeader::updateOrCreate(
                ['name' => $speaker, 'role_type' => 'preacher'],
                [
                    'position' => 'Umubwiriza',
                    'is_active' => true,
                    'sort_order' => $index + 1,
                ]
            );

            $series = VideoSeries::query()->where('title', $entry['series'])->first();

            $video = Video::updateOrCreate(
                ['youtube_id' => $youtubeId],
                [
                    'title' => $entry['title_rw'],
                    'description' => $entry['description_rw'],
                    'youtube_url' => $entry['youtube_url'],
                    'youtube_id' => $youtubeId,
                    'thumbnail' => null,
                    'category_id' => $defaultCategory?->id,
                    'speaker' => $speaker,
                    'series' => $series?->title,
                    'video_series_id' => $series?->id,
                    'published_at' => now()->subDays(30 - $index),
                    'featured' => $index < 3,
                    'view_count' => 0,
                    'is_published' => true,
                ]
            );

            ContentTranslation::updateOrCreate(
                [
                    'content_type' => $video->getMorphClass(),
                    'content_id' => $video->id,
                    'locale' => 'rw',
                ],
                [
                    'source_locale' => 'rw',
                    'title' => $entry['title_rw'],
                    'description' => $entry['description_rw'],
                    'translation_status' => 'approved',
                    'translated_by' => 'manual',
                    'quality_score' => 100,
                    'is_bible_locked' => false,
                    'reviewed_by' => null,
                    'reviewed_at' => now(),
                ]
            );

            ContentTranslation::updateOrCreate(
                [
                    'content_type' => $video->getMorphClass(),
                    'content_id' => $video->id,
                    'locale' => 'en',
                ],
                [
                    'source_locale' => 'rw',
                    'title' => $entry['title_en'],
                    'description' => $entry['description_en'],
                    'translation_status' => 'approved',
                    'translated_by' => 'manual',
                    'quality_score' => 95,
                    'is_bible_locked' => false,
                    'reviewed_by' => null,
                    'reviewed_at' => now(),
                ]
            );

            ContentTranslation::updateOrCreate(
                [
                    'content_type' => $video->getMorphClass(),
                    'content_id' => $video->id,
                    'locale' => 'fr',
                ],
                [
                    'source_locale' => 'rw',
                    'title' => $entry['title_fr'],
                    'description' => $entry['description_fr'],
                    'translation_status' => 'approved',
                    'translated_by' => 'manual',
                    'quality_score' => 95,
                    'is_bible_locked' => false,
                    'reviewed_by' => null,
                    'reviewed_at' => now(),
                ]
            );
        }
    }

    private function extractYoutubeId(string $url): ?string
    {
        $parts = parse_url($url);
        if (!$parts) {
            return null;
        }

        if (!empty($parts['host']) && str_contains($parts['host'], 'youtu.be')) {
            return ltrim($parts['path'] ?? '', '/');
        }

        if (!empty($parts['query'])) {
            parse_str($parts['query'], $query);
            if (!empty($query['v'])) {
                return $query['v'];
            }
        }

        if (!empty($parts['path'])) {
            $segments = array_values(array_filter(explode('/', $parts['path'])));
            $key = array_search('embed', $segments, true);
            if ($key !== false && isset($segments[$key + 1])) {
                return $segments[$key + 1];
            }
        }

        return null;
    }
}



