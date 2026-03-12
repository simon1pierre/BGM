<?php

namespace Database\Seeders;

use App\Models\ContentTranslation;
use App\Models\VideoSeries;
use Illuminate\Database\Seeder;

class VideoSeriesSeeder extends Seeder
{
    public function run(): void
    {
        $seriesList = [
            [
                'rw' => [
                    'title' => "Ubutumwa bw'Abamarayika Batatu",
                    'description' => "Urukurikirane rw'inyigisho ku butumwa bwo mu Ibyahishuwe 14 no guhamagarira abantu gusenga Imana by'ukuri.",
                ],
                'en' => [
                    'title' => "Three Angels' Messages",
                    'description' => 'A sermon series on Revelation 14 and the end-time call to true worship.',
                ],
                'fr' => [
                    'title' => 'Messages des Trois Anges',
                    'description' => "Une serie de predications sur Apocalypse 14 et l'appel final a la vraie adoration.",
                ],
                'sort_order' => 1,
            ],
            [
                'rw' => [
                    'title' => "Isabato n'Irema",
                    'description' => "Inyigisho zigaragaza Isabato nk'ikimenyetso cy'irema n'ubusabane n'Imana.",
                ],
                'en' => [
                    'title' => 'Sabbath and Creation',
                    'description' => "Teachings on the Sabbath as a sign of creation and covenant relationship with God.",
                ],
                'fr' => [
                    'title' => 'Sabbat et Creation',
                    'description' => "Enseignements sur le Sabbat comme signe de la creation et de l'alliance avec Dieu.",
                ],
                'sort_order' => 2,
            ],
            [
                'rw' => [
                    'title' => "Yesu Umutambyi Mukuru",
                    'description' => "Urukurikirane ku murimo wa Kristo mu buturo bwo mu ijuru no kwizera gukiza.",
                ],
                'en' => [
                    'title' => 'Jesus Our High Priest',
                    'description' => "A series on Christ's ministry in the heavenly sanctuary and saving faith.",
                ],
                'fr' => [
                    'title' => 'Jesus Notre Souverain Sacrificateur',
                    'description' => "Une serie sur le ministere du Christ dans le sanctuaire celeste et la foi salvatrice.",
                ],
                'sort_order' => 3,
            ],
            [
                'rw' => [
                    'title' => "Ubuzima buzira umuze bwa Gikristo",
                    'description' => "Inyigisho ku mibereho myiza, kwirinda no kubaha Imana mu mibiri yacu.",
                ],
                'en' => [
                    'title' => 'Christian Health Message',
                    'description' => 'Sermons on healthful living, temperance, and honoring God with our bodies.',
                ],
                'fr' => [
                    'title' => 'Message de Sante Chretienne',
                    'description' => 'Predications sur la sante, la temperance et la gloire de Dieu dans notre corps.',
                ],
                'sort_order' => 4,
            ],
            [
                'rw' => [
                    'title' => "Umuryango wubakiye kuri Kristo",
                    'description' => "Urukurikirane rwigisha kubaka urugo rw'amasengesho, urukundo n'ubudahemuka.",
                ],
                'en' => [
                    'title' => 'Christ-Centered Family',
                    'description' => 'A practical series on prayerful homes, marriage fidelity, and biblical parenting.',
                ],
                'fr' => [
                    'title' => 'Famille Centree sur le Christ',
                    'description' => 'Une serie pratique sur la famille de priere, la fidelite et leducation biblique.',
                ],
                'sort_order' => 5,
            ],
            [
                'rw' => [
                    'title' => "Ubutumwa bw'Ububyutse n'Ivugurura",
                    'description' => "Inyigisho zihamagarira kwihana, kuvugurura umutima no gukomera mu kwizera.",
                ],
                'en' => [
                    'title' => 'Revival and Reformation',
                    'description' => 'Messages calling believers to repentance, spiritual renewal, and deeper faithfulness.',
                ],
                'fr' => [
                    'title' => 'Reveil et Reforme',
                    'description' => 'Messages appelant a la repentance, au renouveau spirituel et a la fidelite.',
                ],
                'sort_order' => 6,
            ],
            [
                'rw' => [
                    'title' => "Daniyeli n'Ibyahishuwe",
                    'description' => "Urukurikirane rw'ubuhanuzi busobanura ibihe n'ibyiringiro byo kugaruka kwa Kristo.",
                ],
                'en' => [
                    'title' => 'Daniel and Revelation',
                    'description' => "A prophecy series explaining end-time events and the hope of Christ's return.",
                ],
                'fr' => [
                    'title' => 'Daniel et Apocalypse',
                    'description' => "Une serie prophetique sur les temps de la fin et l'esperance du retour du Christ.",
                ],
                'sort_order' => 7,
            ],
            [
                'rw' => [
                    'title' => "Kwitegura Kugaruka kwa Yesu",
                    'description' => "Inyigisho zo gufasha abizera kuba maso, gusenga no gukorera Imana mu gihe cya nyuma.",
                ],
                'en' => [
                    'title' => "Preparing for Jesus' Return",
                    'description' => 'Sermons to help believers watch, pray, and serve faithfully in the last days.',
                ],
                'fr' => [
                    'title' => 'Preparation au Retour de Jesus',
                    'description' => 'Predications pour aider les croyants a veiller, prier et servir fidelement.',
                ],
                'sort_order' => 8,
            ],
        ];

        foreach ($seriesList as $entry) {
            $series = VideoSeries::updateOrCreate(
                ['title' => $entry['rw']['title']],
                [
                    'title' => $entry['rw']['title'],
                    'description' => $entry['rw']['description'],
                    'sort_order' => $entry['sort_order'],
                    'is_active' => true,
                ]
            );

            foreach (['rw', 'en', 'fr'] as $locale) {
                ContentTranslation::updateOrCreate(
                    [
                        'content_type' => $series->getMorphClass(),
                        'content_id' => $series->id,
                        'locale' => $locale,
                    ],
                    [
                        'source_locale' => 'rw',
                        'title' => $entry[$locale]['title'],
                        'description' => $entry[$locale]['description'],
                        'translation_status' => 'approved',
                        'translated_by' => 'manual',
                        'quality_score' => 100.0,
                        'is_bible_locked' => false,
                        'reviewed_by' => null,
                        'reviewed_at' => now(),
                    ]
                );
            }
        }
    }
}









