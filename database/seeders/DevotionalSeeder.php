<?php

namespace Database\Seeders;

use App\Models\Devotional;
use Illuminate\Database\Seeder;

class DevotionalSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            [
                'title' => 'Kwiringira Imana mu gihe cy ibigeragezo',
                'excerpt' => 'Iyo ibihe bikomeye byegeranye, ijambo ry Imana ridukomeza kandi riduha amahoro adashingiye ku bihe.',
                'body' => "Igeragezwa ntirisobanura ko Imana yakuvuyeho. Kenshi ni umwanya wo gukomera mu kwizera no kwiga kumva ijwi ryayo.\n\nSoma Ijambo buri munsi, usenge utizigama, kandi uzirikane ko Imana ikora nubwo utabona ibisubizo ako kanya.\n\nUyu munsi, hitamo kwizera aho gutinya. Imana iragukunda kandi iri kugendana nawe.",
                'scripture_reference' => 'Yakobo 1:2-4',
                'author' => 'Beacon of God Ministries',
            ],
            [
                'title' => 'Amahoro ya Kristo mu mutima',
                'excerpt' => 'Amahoro ya Kristo aturuka ku kwiringira Imana, si ku kuba ibintu byose byatunganye.',
                'body' => "Isi ishobora kuduha inkuru nyinshi zitera ubwoba, ariko Yesu aduha amahoro y ukuri.\n\nFata akanya ushire umutima ku Mana. Vuga uti: 'Mwami, nguhaye ibitekerezo byanjye byose.'\n\nAmahoro ye azarinda umutima wawe n ibitekerezo byawe.",
                'scripture_reference' => 'Abafilipi 4:6-7',
                'author' => 'Beacon of God Ministries',
            ],
            [
                'title' => 'Kubabarira nk uko twababariwe',
                'excerpt' => 'Kubabarira ni inzira yo kurekura uburemere no kwakira ubwisanzure bwo mu mutima.',
                'body' => "Kubabarira ntibivuga kwemera ibibi, ahubwo ni kurekura ububabare mu maboko y Imana.\n\nIyo tubabariye, umutima urakira, kandi umubano wacu n Imana urushaho gukomera.\n\nUyu munsi, saba Imana imbaraga zo kubabarira uwo wagiriye inzika.",
                'scripture_reference' => 'Abefeso 4:31-32',
                'author' => 'Beacon of God Ministries',
            ],
            [
                'title' => 'Gukomera mu isengesho',
                'excerpt' => 'Isengesho rihoraho rituma twumva neza icyerekezo cy Imana mu buzima bwacu.',
                'body' => "Isengesho si umugenzo gusa; ni ikiganiro cy urukundo hagati yawe n Imana.\n\nNubwo amagambo yaba make, komeza kwegera Imana buri munsi.\n\nIsengesho rituma umutima wacu uhinduka mbere y uko ibibazo bihinduka.",
                'scripture_reference' => '1 Abatesalonike 5:17',
                'author' => 'Beacon of God Ministries',
            ],
            [
                'title' => 'Umucyo w Ijambo ry Imana',
                'excerpt' => 'Ijambo ry Imana riduha ubuyobozi bwizewe mu nzira z ubuzima.',
                'body' => "Iyo tutazi aho tugana, Ijambo ry Imana ritubera itara.\n\nRifungura ubwenge, rikaduhumuriza, rikadukomeza mu kugenda neza.\n\nShyira gahunda yo gusoma Bibiliya buri munsi, nubwo ari umurongo umwe.",
                'scripture_reference' => 'Zaburi 119:105',
                'author' => 'Beacon of God Ministries',
            ],
            [
                'title' => 'Kuba umwizerwa mu bito',
                'excerpt' => 'Kuba umwizerwa mu byo dufite ubu bitwigisha uko twakirwa n inshingano nini ejo.',
                'body' => "Imana ireba umutima w umwizerwa no mu bintu byoroshye.\n\nNtugapfobye ibyo ufite uyu munsi; birashobora kuba intangiriro y umugisha ukomeye.\n\nKomeza gukora neza, mu kuri no mu bwizerwa.",
                'scripture_reference' => 'Luka 16:10',
                'author' => 'Beacon of God Ministries',
            ],
            [
                'title' => 'Imbaraga nshya buri gitondo',
                'excerpt' => 'Ubuntu bw Imana bushya buri gitondo butuma dutangira umunsi dufite ibyiringiro.',
                'body' => "Nubwo ejo hashize hari hatoroshye, uyu munsi Imana iguhaye amahirwe mashya.\n\nWubake umunsi wawe ku gushima no ku kwizera.\n\nImbaraga zayo zirahagije ku rugendo rwawe rw uyu munsi.",
                'scripture_reference' => 'Amaganya 3:22-23',
                'author' => 'Beacon of God Ministries',
            ],
            [
                'title' => 'Kugendera mu kuri',
                'excerpt' => 'Ukuri kutubohora kandi kutuyobora ku buzima bufite intego.',
                'body' => "Isi ishobora guhindagura ibitekerezo, ariko Ijambo ry Imana rihoraho.\n\nHitamo ukuri nubwo byaba bigusaba guhindura inzira.\n\nKugendera mu kuri ni urugendo rw umunsi ku munsi, rufashwa n Umwuka Wera.",
                'scripture_reference' => 'Yohana 8:31-32',
                'author' => 'Beacon of God Ministries',
            ],
            [
                'title' => 'Kwicisha bugufi imbere y Imana',
                'excerpt' => 'Kwicisha bugufi kudufungurira urugi rw ubuntu n ubuyobozi bw Imana.',
                'body' => "Imana irwanya abibone, ariko abicisha bugufi ikabaha ubuntu.\n\nTureke ubwibone, twemere kwigishwa no gukosorwa n Ijambo.\n\nMu kwicisha bugufi, turushaho kuba hafi y umutima w Imana.",
                'scripture_reference' => 'Yakobo 4:6',
                'author' => 'Beacon of God Ministries',
            ],
            [
                'title' => 'Ibyiringiro biduteranya imbere',
                'excerpt' => 'Ibyiringiro byo muri Kristo biduha imbaraga zo gukomeza nubwo habaho inzitizi.',
                'body' => "Ibyiringiro byacu ntibishingiye ku mbaraga zacu, ahubwo bishingiye ku masezerano y Imana.\n\nIgihe ibintu bitinze, komeza kwizera ko Imana ikora.\n\nIbyo yagutangiyeho izabirangiza neza.",
                'scripture_reference' => 'Abaroma 15:13',
                'author' => 'Beacon of God Ministries',
            ],
            [
                'title' => 'Urukundo rutava ku Mana',
                'excerpt' => 'Nta kintu na kimwe gishobora kudutandukanya n urukundo rw Imana ruri muri Kristo.',
                'body' => "Iyo twumva ducitse intege, twibuke ko urukundo rw Imana rudahinduka.\n\nNtirushingira ku byo dukora, ahubwo rushingira ku kamere yayo.\n\nUru rukundo rutuma duhaguruka kandi rugatuma dukomeza.",
                'scripture_reference' => 'Abaroma 8:38-39',
                'author' => 'Beacon of God Ministries',
            ],
            [
                'title' => 'Guhitamo umunezero',
                'excerpt' => 'Umunezero wo mu Mwami ni imbaraga zacu no mu bihe by ibibazo.',
                'body' => "Umunezero wa gikristo si uguhora useka; ni ukwizera ko Imana iri kumwe nawe.\n\nShima Imana ku byo yagukoreye n ibyo igiye kugukorera.\n\nUmunezero wo mu Mwami uzaguha imbaraga nshya.",
                'scripture_reference' => 'Nehemiya 8:10',
                'author' => 'Beacon of God Ministries',
            ],
            [
                'title' => 'Kumva no kumvira ijwi ry Imana',
                'excerpt' => 'Intama zimenya ijwi ry Umushumba, kandi zigamije kumukurikira.',
                'body' => "Ijwi ry Imana riza mu Ijambo ryayo, mu Mwuka Wera, no mu nama nziza.\n\nFata igihe cyo gutuza imbere yayo kugira ngo uyumve neza.\n\nIyo umwumviye, inzira igira amahoro.",
                'scripture_reference' => 'Yohana 10:27',
                'author' => 'Beacon of God Ministries',
            ],
            [
                'title' => 'Guhangana n ubwoba',
                'excerpt' => 'Imana ntitwahawe umwuka w ubwoba, ahubwo twahawe imbaraga n urukundo.',
                'body' => "Ubwoba bushobora kuduhagarika, ariko kwizera kudutera gutera intambwe.\n\nVuga ukuri kw Ijambo ry Imana ku byagutera ubwoba.\n\nUwo muri kumwe aruta uwo mu isi.",
                'scripture_reference' => '2 Timoteyo 1:7',
                'author' => 'Beacon of God Ministries',
            ],
            [
                'title' => 'Umutima ushimira',
                'excerpt' => 'Gushima Imana mu bihe byose bituma tubona ubuntu bwayo buri munsi.',
                'body' => "Gushima si uko ibintu byose byagenze neza; ni ukwemera ko Imana ari nziza.\n\nTangira uyu munsi ushimira ku mpano z ubuzima, umwuka, n agakiza.\n\nUmutima ushimira urushaho kubona umugisha.",
                'scripture_reference' => '1 Abatesalonike 5:18',
                'author' => 'Beacon of God Ministries',
            ],
            [
                'title' => 'Kubaka urugo ku rutare',
                'excerpt' => 'Ijambo ry Imana ni urufatiro rukomeye rw urugo n ubuzima.',
                'body' => "Imvura n imiyaga biraza mu buzima bwa buri wese.\n\nAriko uwubaka ku Ijambo ry Imana agumana guhagarara.\n\nSenga ku muryango wawe kandi mwige Ijambo hamwe.",
                'scripture_reference' => 'Matayo 7:24-25',
                'author' => 'Beacon of God Ministries',
            ],
            [
                'title' => 'Kuba umunyu n umucyo',
                'excerpt' => 'Abakristo bahamagarirwa kugira ingaruka nziza mu muryango no mu gihugu.',
                'body' => "Uburyo uvuga, ukora, n uko ukunda abandi ni ubutumwa bwiza buboneka.\n\nReka ubuzima bwawe buvuge Kristu aho uri hose.\n\nUyu munsi, kora igikorwa kimwe cy urukundo gifatika.",
                'scripture_reference' => 'Matayo 5:13-16',
                'author' => 'Beacon of God Ministries',
            ],
            [
                'title' => 'Kwihangana kugeza ku iherezo',
                'excerpt' => 'Kwihangana mu kwizera kudutegurira kubona imbuto zirambye.',
                'body' => "Hari igihe ibyifuzo bitinda, ariko Imana ikora mu buryo bwayo bwiza.\n\nNtucike intege; komeza inzira y ukwizera.\n\nUwihangana azabona icyo asezeranijwe.",
                'scripture_reference' => 'Abaheburayo 10:36',
                'author' => 'Beacon of God Ministries',
            ],
            [
                'title' => 'Umwuka Wera adufasha',
                'excerpt' => 'Umwuka Wera atwigisha, akaduhumuriza kandi akatwibutsa ukuri kwa Kristo.',
                'body' => "Nturi wenyine mu rugendo rwo kwizera.\n\nUmwuka Wera agufasha gusobanukirwa Ijambo no kubaho ubuzima bushimisha Imana.\n\nMusabe buri munsi kukuyobora mu kuri kose.",
                'scripture_reference' => 'Yohana 14:26',
                'author' => 'Beacon of God Ministries',
            ],
            [
                'title' => 'Urukundo rwerekana ko turi abe',
                'excerpt' => 'Urukundo ni ikimenyetso nyakuri cy umwigishwa wa Yesu.',
                'body' => "Nubwo twaba dufite impano nyinshi, urukundo ni rwo rwuzuzanya byose.\n\nKunda utitaye ku nyungu zawe gusa, ahubwo witaye no ku bandi.\n\nMuri urwo rukundo, isi ibona Kristo muri twe.",
                'scripture_reference' => 'Yohana 13:34-35',
                'author' => 'Beacon of God Ministries',
            ],
        ];

        foreach ($items as $index => $item) {
            $publishedAt = now()->subDays(20 - $index)->setHour(6)->setMinute(0);
            $imagePool = [
                'admin/assets/images/banner/1.jpg',
                'admin/assets/images/banner/2.jpg',
                'admin/assets/images/banner/3.jpg',
                'admin/assets/images/banner/4.jpg',
                'admin/assets/images/banner/5.jpg',
                'admin/assets/images/banner/6.jpg',
                'landingpage/hero-divine-background.webp',
                'landingpage/video-sermons.webp',
            ];
            $coverImage = $imagePool[$index % count($imagePool)];
            $rwBody = $this->buildKinyarwandaBody($item['title'], $item['scripture_reference']);

            $devotional = Devotional::updateOrCreate(
                ['title' => $item['title']],
                [
                    'excerpt' => $item['excerpt'],
                    'body' => $rwBody,
                    'cover_image' => $coverImage,
                    'scripture_reference' => $item['scripture_reference'],
                    'author' => $item['author'],
                    'published_at' => $publishedAt,
                    'featured' => $index < 6,
                    'is_published' => true,
                    'sort_order' => $index + 1,
                ]
            );

            $translations = [
                'en' => [
                    'title' => $this->toEnglishTitle($item['title']),
                    'excerpt' => 'Daily devotional reflection from Beacon of God Ministries.',
                    'body' => $this->toEnglishBody($item['title'], $item['scripture_reference']),
                ],
                'fr' => [
                    'title' => $this->toFrenchTitle($item['title']),
                    'excerpt' => 'Meditation devotionnelle quotidienne de Beacon of God Ministries.',
                    'body' => $this->toFrenchBody($item['title'], $item['scripture_reference']),
                ],
                'rw' => [
                    'title' => $item['title'],
                    'excerpt' => $item['excerpt'],
                    'body' => $rwBody,
                ],
            ];

            foreach ($translations as $locale => $payload) {
                $devotional->translations()->updateOrCreate(
                    ['locale' => $locale],
                    array_merge($payload, [
                        'source_locale' => $locale,
                        'translation_status' => 'approved',
                        'translated_by' => 'manual',
                        'quality_score' => 100,
                        'is_bible_locked' => false,
                        'reviewed_by' => null,
                        'reviewed_at' => now(),
                    ])
                );
            }
        }
    }

    private function toEnglishTitle(string $rwTitle): string
    {
        return match ($rwTitle) {
            'Kwiringira Imana mu gihe cy ibigeragezo' => 'Trusting God in Times of Trial',
            'Amahoro ya Kristo mu mutima' => 'Christ\'s Peace in the Heart',
            'Kubabarira nk uko twababariwe' => 'Forgive as We Have Been Forgiven',
            'Gukomera mu isengesho' => 'Persevere in Prayer',
            'Umucyo w Ijambo ry Imana' => 'The Light of God\'s Word',
            'Kuba umwizerwa mu bito' => 'Faithful in Small Things',
            'Imbaraga nshya buri gitondo' => 'New Strength Every Morning',
            'Kugendera mu kuri' => 'Walking in Truth',
            'Kwicisha bugufi imbere y Imana' => 'Humility Before God',
            'Ibyiringiro biduteranya imbere' => 'Hope that Moves Us Forward',
            'Urukundo rutava ku Mana' => 'The Unfailing Love of God',
            'Guhitamo umunezero' => 'Choosing Joy',
            'Kumva no kumvira ijwi ry Imana' => 'Hearing and Obeying God\'s Voice',
            'Guhangana n ubwoba' => 'Overcoming Fear',
            'Umutima ushimira' => 'A Grateful Heart',
            'Kubaka urugo ku rutare' => 'Building a Home on the Rock',
            'Kuba umunyu n umucyo' => 'Being Salt and Light',
            'Kwihangana kugeza ku iherezo' => 'Enduring to the End',
            'Umwuka Wera adufasha' => 'The Holy Spirit Helps Us',
            default => 'Love Shows We Belong to Christ',
        };
    }

    private function toFrenchTitle(string $rwTitle): string
    {
        return match ($rwTitle) {
            'Kwiringira Imana mu gihe cy ibigeragezo' => 'Faire Confiance a Dieu dans l Epreuve',
            'Amahoro ya Kristo mu mutima' => 'La Paix de Christ dans le Coeur',
            'Kubabarira nk uko twababariwe' => 'Pardonner Comme Nous Avons Ete Pardonnés',
            'Gukomera mu isengesho' => 'Persévérer dans la Prière',
            'Umucyo w Ijambo ry Imana' => 'La Lumière de la Parole de Dieu',
            'Kuba umwizerwa mu bito' => 'Fidèle dans les Petites Choses',
            'Imbaraga nshya buri gitondo' => 'Une Force Nouvelle Chaque Matin',
            'Kugendera mu kuri' => 'Marcher dans la Vérité',
            'Kwicisha bugufi imbere y Imana' => 'L Humilité Devant Dieu',
            'Ibyiringiro biduteranya imbere' => 'Une Espérance Qui Nous Fait Avancer',
            'Urukundo rutava ku Mana' => 'L Amour Inébranlable de Dieu',
            'Guhitamo umunezero' => 'Choisir la Joie',
            'Kumva no kumvira ijwi ry Imana' => 'Entendre et Obéir a la Voix de Dieu',
            'Guhangana n ubwoba' => 'Affronter la Peur',
            'Umutima ushimira' => 'Un Coeur Reconnaissant',
            'Kubaka urugo ku rutare' => 'Construire Son Foyer sur le Roc',
            'Kuba umunyu n umucyo' => 'Etre le Sel et la Lumière',
            'Kwihangana kugeza ku iherezo' => 'Persévérer Jusqu a la Fin',
            'Umwuka Wera adufasha' => 'Le Saint-Esprit Nous Aide',
            default => 'L Amour Montre que Nous Appartenons au Christ',
        };
    }

    private function buildKinyarwandaBody(string $title, string $reference): string
    {
        return "UMURONGO NYAMUTIMA: {$reference}\n\nINSHINGA Y UYU MUNSI\n{$title} ni ubutumwa buduhamagarira kureba ku Mana kuruta uko tureba ku bibazo. Ijambo ry Imana riduha icyerekezo, rikadukomeza kandi rikadutoza kuguma mu kuri no mu kwizera. Ibi si amagambo gusa yo kuduhumuriza, ahubwo ni umurongo wo kubaho buri munsi.\n\nICYO DUSOBANUKIRWA N IRIJAMBO\nIyo twiga Ijambo ry Imana twitonze, tubona ko Imana ikunda kutuyobora buhoro buhoro, ikadukura mu bwoba ikatugeza ku mahoro. Nubwo ibihe biba bikomeye, Imana ikomeza kuba indahemuka. Ntabwo idusaba gusobanukirwa byose mbere; idusaba kuyizera no kuyumvira. Iyo tubikoze, umutima uraruhuka kandi ubuzima bugenda bugira umusaruro.\n\nGUSHYIRA MU BIKORWA UYU MUNSI\n1) Tangira umunsi wawe usenga byibura iminota 10, uvuga ku murongo wa {$reference}.\n2) Andika ibintu bitatu ushimira Imana uyu munsi, nubwo byaba bito.\n3) Fata icyemezo kimwe gifatika cyo kubaho mu kuri: kubabarira, kuvugisha ukuri, cyangwa gufasha undi muntu.\n4) Nimugoroba, subiza amaso inyuma urebe aho Imana yakuyoboye.\n\nISENGESHO RISOZA\nMwami Mana, urakoze kuko Ijambo ryawe rinyubaka kandi rikankomeza. Nyigisha kugendera mu kuri kwawe no kuguma mu kwizera nubwo ibihe bitoroshye. Mpa umutima wumva, umenya kubabarira, no gukunda abantu nk uko wabikunze. Umwuka Wera ampindure buri munsi, kugira ngo ubuzima bwanjye bugaragaze Kristo. Mu izina rya Yesu, Amen.\n\nINTAMBWE Y UMWUKA\nUrahamagariwe gusoma iri jambo inshuro ebyiri uyu munsi no kurisangiza undi muntu umwe. Ibyo ukoze uyu munsi birashobora gutangira impinduka ndende mu buzima bwawe no mu buzima bw abandi.";
    }

    private function toEnglishBody(string $title, string $reference): string
    {
        return "KEY SCRIPTURE: {$reference}\n\nTODAY'S REFLECTION\n{$this->toEnglishTitle($title)} calls us to keep our eyes on God more than on pressure around us. Scripture does not only comfort us; it forms our character and direction for daily life.\n\nBIBLICAL INSIGHT\nGod often works progressively. He may not answer every question immediately, but He always remains faithful. In every season, obedience and trust create room for peace, clarity, and spiritual growth.\n\nPRACTICAL RESPONSE\n1) Spend at least 10 focused minutes in prayer around {$reference}.\n2) Write three gratitude points from your current day.\n3) Practice one concrete act of obedience: forgive, speak truth, or serve someone.\n4) Before sleeping, review how God guided your steps.\n\nPRAYER\nLord, thank You for Your living Word. Teach me to walk in faith, to choose truth, and to love as Christ loved. Strengthen my heart by Your Spirit, and let my life reflect Your grace in every decision. In Jesus' name, Amen.\n\nSPIRITUAL ACTION\nRead this devotional twice today and share it with one person who needs encouragement.";
    }

    private function toFrenchBody(string $title, string $reference): string
    {
        return "VERSET CLE: {$reference}\n\nREFLEXION DU JOUR\n{$this->toFrenchTitle($title)} nous rappelle de regarder a Dieu avant de regarder aux circonstances. La Parole ne nous console pas seulement; elle nous forme et nous conduit.\n\nCOMPREHENSION BIBLIQUE\nDieu agit souvent par etapes. Il ne repond pas toujours immediatement a toutes nos questions, mais Il demeure fidele. Dans chaque saison, l obeissance et la confiance ouvrent la voie a la paix et a la maturite spirituelle.\n\nAPPLICATION PRATIQUE\n1) Prenez au moins 10 minutes de priere autour de {$reference}.\n2) Ecrivez trois sujets de reconnaissance aujourd hui.\n3) Posez un acte concret d obeissance: pardonner, dire la verite, ou servir quelqu un.\n4) Le soir, relisez votre journee pour discerner la main de Dieu.\n\nPRIERE\nSeigneur, merci pour Ta Parole vivante. Apprends-moi a marcher par la foi, a choisir la verite, et a aimer comme Christ. Fortifie mon coeur par Ton Esprit, afin que ma vie Te glorifie chaque jour. Au nom de Jesus, Amen.\n\nACTION SPIRITUELLE\nLisez ce devotionnel deux fois aujourd hui et partagez-le avec une personne qui a besoin d encouragement.";
    }
}








