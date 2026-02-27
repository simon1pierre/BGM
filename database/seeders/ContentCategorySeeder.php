<?php

namespace Database\Seeders;

use App\Models\ContentCategory;
use App\Models\ContentTranslation;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ContentCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'slug' => 'inyigisho-z-ibyanditswe',
                'type' => 'video',
                'rw' => [
                    'title' => "Inyigisho z'Ibyanditswe",
                    'description' => "Icyiciro gikubiyemo inyigisho zisobanura Bibiliya mu buryo bwimbitse kandi bwubaka ukwizera.",
                ],
                'en' => [
                    'title' => 'Biblical Teachings',
                    'description' => 'A category for in-depth Bible teachings that strengthen faith and understanding.',
                ],
                'fr' => [
                    'title' => 'Enseignements Bibliques',
                    'description' => "Categorie des enseignements bibliques approfondis pour fortifier la foi et la comprehension.",
                ],
            ],
            [
                'slug' => 'ubuhamya-bw-ukuri',
                'type' => 'video',
                'rw' => [
                    'title' => "Ubuhamya bw'Ukuri",
                    'description' => "Ubuhamya bw'abizera bwerekana uburyo Imana ikora mu buzima bwa buri munsi.",
                ],
                'en' => [
                    'title' => 'Faith Testimonies',
                    'description' => "Believers' testimonies showing how God works in everyday life.",
                ],
                'fr' => [
                    'title' => 'Temoignages de Foi',
                    'description' => "Temoignages des croyants montrant l'oeuvre de Dieu dans la vie quotidienne.",
                ],
            ],
            [
                'slug' => 'amasengesho-n-ubwiyunge',
                'type' => 'video',
                'rw' => [
                    'title' => "Amasengesho n'Ubwiyunge",
                    'description' => "Ubutumwa n'ibiganiro byubaka ubuzima bw'isengesho no kugendana n'Imana.",
                ],
                'en' => [
                    'title' => 'Prayer and Devotion',
                    'description' => 'Messages and sessions that strengthen prayer life and daily devotion.',
                ],
                'fr' => [
                    'title' => 'Priere et Devotion',
                    'description' => 'Messages et enseignements pour affermir la vie de priere et de devotion.',
                ],
            ],
            [
                'slug' => 'inyigisho-z-amajwi',
                'type' => 'audio',
                'rw' => [
                    'title' => "Inyigisho z'Amajwi",
                    'description' => "Icyiciro cy'amajwi y'inyigisho z'ijambo ry'Imana ushobora kumva aho uri hose.",
                ],
                'en' => [
                    'title' => 'Audio Sermons',
                    'description' => "Audio teachings of God's word for listening anywhere, anytime.",
                ],
                'fr' => [
                    'title' => 'Sermons Audio',
                    'description' => "Enseignements audio de la parole de Dieu a ecouter partout et a tout moment.",
                ],
            ],
            [
                'slug' => 'indirimbo-z-ibisigo',
                'type' => 'audio',
                'rw' => [
                    'title' => "Indirimbo z'Ibisigo",
                    'description' => "Indirimbo zo kuramya no guhimbaza Imana zubaka umutima no kwizera.",
                ],
                'en' => [
                    'title' => 'Worship Songs',
                    'description' => 'Songs of worship and praise that uplift hearts and strengthen faith.',
                ],
                'fr' => [
                    'title' => 'Chants de Louange',
                    'description' => 'Chants dadoration et de louange qui elevent les coeurs et fortifient la foi.',
                ],
            ],
            [
                'slug' => 'amasengesho-y-amajwi',
                'type' => 'audio',
                'rw' => [
                    'title' => "Amasengesho y'Amajwi",
                    'description' => "Amajwi y'amasengesho ayobora abizera mu gusenga no kwizera byimbitse.",
                ],
                'en' => [
                    'title' => 'Prayer Audio',
                    'description' => 'Prayer audio sessions that guide believers into deeper prayer.',
                ],
                'fr' => [
                    'title' => 'Audio de Priere',
                    'description' => 'Sessions audio de priere pour accompagner les croyants dans une priere profonde.',
                ],
            ],
            [
                'slug' => 'ibitabo-by-iyobokamana',
                'type' => 'document',
                'rw' => [
                    'title' => "Ibitabo by'Iyobokamana",
                    'description' => "Ibitabo n'inyandiko bigamije gufasha abakristo gukura mu byo kwizera.",
                ],
                'en' => [
                    'title' => 'Spiritual Books',
                    'description' => 'Books and writings designed to help believers grow spiritually.',
                ],
                'fr' => [
                    'title' => 'Livres Spirituels',
                    'description' => 'Livres et ecrits pour aider les croyants a grandir spirituellement.',
                ],
            ],
            [
                'slug' => 'inyigisho-zanditse',
                'type' => 'document',
                'rw' => [
                    'title' => 'Inyigisho Zanditse',
                    'description' => "Inyandiko z'inyigisho zifasha gusobanukirwa ukuri kwa Bibiliya mu buryo bworoshye.",
                ],
                'en' => [
                    'title' => 'Written Teachings',
                    'description' => 'Written teachings that explain biblical truth clearly and practically.',
                ],
                'fr' => [
                    'title' => 'Enseignements Ecrits',
                    'description' => 'Enseignements ecrits qui expliquent clairement et pratiquement la verite biblique.',
                ],
            ],
            [
                'slug' => 'amasomo-y-abana',
                'type' => 'document',
                'rw' => [
                    'title' => "Amasomo y'Abana",
                    'description' => "Ibitabo n'amasomo y'abana bibatoza gukunda Imana no kugendera mu kuri.",
                ],
                'en' => [
                    'title' => "Children's Lessons",
                    'description' => 'Books and lessons that teach children to love God and walk in truth.',
                ],
                'fr' => [
                    'title' => 'Lecons pour Enfants',
                    'description' => 'Livres et lecons pour apprendre aux enfants a aimer Dieu et marcher dans la verite.',
                ],
            ],
            [
                'slug' => 'ivugabutumwa-rusange',
                'type' => 'all',
                'rw' => [
                    'title' => 'Ivugabutumwa Rusange',
                    'description' => "Icyiciro rusange gihuza ubutumwa bw'ivugabutumwa muri video, amajwi n'inyandiko.",
                ],
                'en' => [
                    'title' => 'General Evangelism',
                    'description' => 'General evangelism content across video, audio, and written resources.',
                ],
                'fr' => [
                    'title' => 'Evangelisation Generale',
                    'description' => "Contenu general d'evangelisation en video, audio et documents.",
                ],
            ],
            [
                'slug' => 'urubyiruko-n-imiryango',
                'type' => 'all',
                'rw' => [
                    'title' => "Urubyiruko n'Imiryango",
                    'description' => "Ubutumwa bufasha urubyiruko n'imiryango kubaho mu ndangagaciro z'ijambo ry'Imana.",
                ],
                'en' => [
                    'title' => 'Youth and Families',
                    'description' => "Messages for youth and families to live by God's word and values.",
                ],
                'fr' => [
                    'title' => 'Jeunesse et Familles',
                    'description' => "Messages pour la jeunesse et les familles afin de vivre selon la parole de Dieu.",
                ],
            ],
            [
                'slug' => 'isengesho-n-ububyutse',
                'type' => 'all',
                'rw' => [
                    'title' => "Isengesho n'Ububyutse",
                    'description' => "Icyiciro cy'ubutumwa n'ibikoresho bishyigikira ububyutse n'ubuzima bw'isengesho.",
                ],
                'en' => [
                    'title' => 'Prayer and Revival',
                    'description' => 'Messages and resources supporting spiritual revival and prayer life.',
                ],
                'fr' => [
                    'title' => 'Priere et Reveil',
                    'description' => 'Messages et ressources pour soutenir le reveil spirituel et la vie de priere.',
                ],
            ],
        ];

        foreach ($categories as $data) {
            $category = ContentCategory::updateOrCreate(
                ['slug' => $data['slug'] ?: Str::slug($data['rw']['title'])],
                [
                    'name' => $data['rw']['title'],
                    'slug' => $data['slug'] ?: Str::slug($data['rw']['title']),
                    'type' => $data['type'],
                    'description' => $data['rw']['description'],
                    'is_active' => true,
                ]
            );

            foreach (['rw', 'en', 'fr'] as $locale) {
                ContentTranslation::updateOrCreate(
                    [
                        'content_type' => $category->getMorphClass(),
                        'content_id' => $category->id,
                        'locale' => $locale,
                    ],
                    [
                        'source_locale' => 'rw',
                        'title' => $data[$locale]['title'],
                        'description' => $data[$locale]['description'],
                        'translation_status' => 'approved',
                        'translated_by' => 'manual',
                        'quality_score' => 100,
                        'is_bible_locked' => false,
                        'reviewed_by' => null,
                        'reviewed_at' => now(),
                    ]
                );
            }
        }
    }
}

