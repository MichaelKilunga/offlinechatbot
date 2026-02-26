<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Curriculum;

class SwahiliCurriculumSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            // FIZIKIA (Form I - VI)
            [
                'title' => 'Fizikia - Utangulizi wa Fizikia na Vipimo (Kidato cha Kwanza)',
                'content' => 'Fizikia ni tawi la sayansi linalohusu maada na nishati. Vipimo ni muhimu katika fizikia. Vipimo vya msingi vya SI ni: Mita (m) kwa urefu, Kilogramu (kg) kwa masi, na Sekunde (s) kwa muda. Ala za kupimia ni pamoja na rula, mizani, na saa. Msongamano (Density) ni masi kwa kila ujazo (D = m/v).',
                'summary' => 'Misingi ya fizikia, vipimo vya SI na msongamano.',
                'tags' => 'fizikia, kichele, vipimo, msongamano, form1',
                'keywords' => ['fizikia', 'vipimo', 'masi', 'urefu', 'muda', 'msongamano', 'density'],
                'language' => 'sw',
            ],
            [
                'title' => 'Fizikia - Nguvu, Mwendo na Sheria za Newton (Kidato cha Kwanza na Pili)',
                'content' => 'Nguvu ni msukumo au mvutano (kipimo chake ni Newton). Sheria za Newton: 1) Sheria ya Inertia: Kitu huendelea kuwa kimya au katika mwendo usiobadilika hadi kiathiriwe na nguvu. 2) F = ma: Nguvu ni zao la masi na mchapuko. 3) Kwa kila tendo kuna mmenyuko sawa na kinyume. Kasi (Velocity) na Mchapuko (Acceleration) ni sehemu muhimu za mwendo.',
                'summary' => 'Nguvu, mwendo na sheria tatu za Newton.',
                'tags' => 'fizikia, nguvu, mwendo, newton, mchapuko, form1, form2',
                'keywords' => ['nguvu', 'mwendo', 'newton', 'mchapuko', 'inertia', 'force', 'acceleration'],
                'language' => 'sw',
            ],
            [
                'title' => 'Fizikia - Kazi, Nishati na Nguvu (Kidato cha Pili)',
                'content' => 'Kazi hufanyika nguvu inaposababisha mwendo (W = Fd, kipimo ni Joule). Nishati ni uwezo wa kufanya kazi. Aina za nishati: Nishati ya Kinetic (KE = 1/2 mv²) na Nishati ya Potential (PE = mgh). Nguvu (Power) ni kasi ya kufanya kazi (P = W/t, kipimo ni Watt). Sheria ya Uhifadhi wa Nishati: Nishati haiumbwi wala kuharibiwa.',
                'summary' => 'Uhusiano kati ya kazi, nishati (kinetic/potential) na nguvu.',
                'tags' => 'fizikia, kazi, nishati, nguvu, nishati_kinetic, form2',
                'keywords' => ['kazi', 'nishati', 'nguvu', 'joule', 'watt', 'kinetic', 'potential'],
                'language' => 'sw',
            ],
            [
                'title' => 'Fizikia - Mwanga na Optiki (Kidato cha Pili)',
                'content' => 'Mwanga husafiri katika mstari ulionyooka. Akisi (Reflection) hutokea kwenye vioo. Kinamo (Refraction) hutokea mwanga ukipita kwenye midia tofauti (mfano maji na hewa). Lenzi mbonyeo (Convex) na lenzi mbonyeo (Concave) hutumiwa kurekebisha uoni na katika kamera. Upinde wa mvua hutokea kwa kutawanyika kwa mwanga mweupe.',
                'summary' => 'Tabia za mwanga: kuakisi, kukinama na lenzi.',
                'tags' => 'fizikia, mwanga, vioo, lenzi, reflection, refraction, form2',
                'keywords' => ['mwanga', 'akisi', 'kinamo', 'lenzi', 'optiki', 'vioo'],
                'language' => 'sw',
            ],
            [
                'title' => 'Fizikia - Umeme na Magneti (Kidato cha Tatu na Nne)',
                'content' => 'Umeme ni mtiririko wa chaji. Sheria ya Ohm: V = IR (Voltage = Ampea × Upinzani). Mizunguko inaweza kuwa ya mfululizo (Series) au sambamba (Parallel). Magneti ina ncha mbili: Kaskazini na Kusini. Ncha zinazofanana hufukuzana, zisizofanana huvutana. Umeme unaweza kuzalisha uga wa sumaku (Electromagnetism).',
                'summary' => 'Sheria ya Ohm, mizunguko ya umeme na misingi ya magneti.',
                'tags' => 'fizikia, umeme, magneti, sheria_ya_ohm, voltage, form3, form4',
                'keywords' => ['umeme', 'magneti', 'ohm', 'voltage', 'upinzani', 'mzunguko'],
                'language' => 'sw',
            ],
            // BIOLOJIA (Form I - VI)
            [
                'title' => 'Biolojia - Seli: Muundo na Kazi (Kidato cha Kwanza)',
                'content' => 'Seli ni kitengo cha msingi cha uhai. Seli za mimea zina ukuta wa seli na kloroplast (kwa ajili ya usanisinuru), wakati seli za wanyama hazina. Sehemu kuu: Kiini (Nucleus - udhibiti), Saitoplasm (Cytoplasm - athari za kemikali), na Utando wa seli (Cell Membrane - udhibiti wa kuingia/kutoka). Mitochondria huzalisha nishati.',
                'summary' => 'Muundo wa seli za mimea na wanyama na kazi za oganeli.',
                'tags' => 'biolojia, seli, mimea, wanyama, nucleus, form1',
                'keywords' => ['seli', 'kiini', 'mitochondria', 'kloroplast', 'uhai'],
                'language' => 'sw',
            ],
            [
                'title' => 'Biolojia - Usanisinuru na Upumuaji (Kidato cha Pili)',
                'content' => 'Usanisinuru (Photosynthesis): 6CO2 + 6H2O + Mwanga -> C6H12O6 + 6O2. Hutokea kwenye mimea ya kijani. Upumuaji (Respiration) ni mchakato wa kutoa nishati kutoka kwenye chakula. Upumuaji wa kutumia oksijeni (Aerobic) huzalisha nishati nyingi zaidi kuliko upumuaji bila oksijeni (Anaerobic).',
                'summary' => 'Mchakato wa mimea kujitengenezea chakula na viumbe kutoa nishati.',
                'tags' => 'biolojia, usanisinuru, upumuaji, nishati, oksijeni, form2',
                'keywords' => ['usanisinuru', 'upumuaji', 'oksijeni', 'nishati', 'mimea'],
                'language' => 'sw',
            ],
            [
                'title' => 'Biolojia - Uainishaji wa Viumbe Hai (Kidato cha Pili na Tatu)',
                'content' => 'Viumbe huainishwa katika falme tano: Monera, Protoctista, Fungi, Plantae (Mimea), na Animalia (Wanyama). Mfumo wa jina la kisayansi (Binomial Nomenclature) unatumia Jenasi na Spishi (mfano: Panthera leo - Simba). Viumbe wenye uti wa mgongo (Vertebrates) ni pamoja na samaki, amfibia, reptilia, ndege na mamalia.',
                'summary' => 'Falme tano za viumbe na mfumo wa uainishaji kuanzia ufalme hadi spishi.',
                'tags' => 'biolojia, uainishaji, wanyama, mimea, spishi, form2, form3',
                'keywords' => ['uainishaji', 'wanyama', 'mimea', 'fungi', 'spishi', 'ufalme'],
                'language' => 'sw',
            ],
            [
                'title' => 'Biolojia - Uzazi kwa Binadamu (Kidato cha Tatu)',
                'content' => 'Uzazi ni mchakato wa kuongeza idadi ya viumbe. Mfumo wa uzazi wa kiume (korodani hutoa manii) na wa kike (ovari hutoa mayai). Urutubisho (Fertilization) hutokea mrija wa falopio. Mimba huchukua takriban miezi 9. Afya ya uzazi na kuzuia magonjwa ya zinaa (STI/HIV) ni muhimu sana.',
                'summary' => 'Mifumo ya uzazi, urutubisho, mimba na afya ya uzazi.',
                'tags' => 'biolojia, uzazi, mimba, afya, binadamu, form3',
                'keywords' => ['uzazi', 'mimba', 'kiume', 'kike', 'urutubisho'],
                'language' => 'sw',
            ],
            [
                'title' => 'Biolojia - Jenetiki na Urithi (Kidato cha Nne na Tano)',
                'content' => 'Jenetiki ni elimu ya urithi. DNA hubeba taarifa za kimaumbile. Sheria za Mendel zinaeleza jinsi sifa (kama rangi ya macho au urefu) zinavyopitishwa. Sifa zinazotawala (Dominant) na sifa zinazofichwa (Recessive). Jenotipu ni muundo wa jeni, na Fenotipu ni sifa zinazoonekana.',
                'summary' => 'Misingi ya urithi, DNA, na sheria za Mendel.',
                'tags' => 'biolojia, jenetiki, urithi, DNA, mendel, form4, form5',
                'keywords' => ['jenetiki', 'urithi', 'DNA', 'jeni', 'mendeleev'],
                'language' => 'sw',
            ],
            // KEMIA (Form I - VI)
            [
                'title' => 'Kemia - Utangulizi wa Kemia na Maabara (Kidato cha Kwanza)',
                'content' => 'Kemia ni elimu ya maada na mabadiliko yake. Usalama wa maabara ni kipaumbele: vaa koti la maabara, miwani, na usile maabara. Vifaa muhimu: Beaker (kuchanganyia), Flask (kufanyia athari), Burette na Pipette (vipimo sahihi). Alama za hatari: kuwaka moto, kuteketeza, na sumu.',
                'summary' => 'Misingi ya kemia, usalama wa maabara na vifaa vya kisayansi.',
                'tags' => 'kemia, maabara, usalama, vifaa, form1',
                'keywords' => ['kemia', 'maabara', 'usalama', 'vifaa'],
                'language' => 'sw',
            ],
            [
                'title' => 'Kemia - Muundo wa Atomu na Jedwali la Vipengele (Kidato cha Pili)',
                'content' => 'Atomu ina protoni na neutroni kwenye kiini (nucleus), na elektroni zinazozunguka. Namba ya atomiki ni idadi ya protoni. Jedwali la Vipengele hupanga vipengele kwa namba ya atomiki. Makundi (Group) yana sifa zinazofanana. Metalodi (Metals) zipo kushoto, na zisizo-metali zipo kulia.',
                'summary' => 'Sehemu za atomu na mpangilio wa vipengele kwenye jedwali (periodic table).',
                'tags' => 'kemia, atomu, elektroni, protoni, jedwali_la_vipengele, form2',
                'keywords' => ['atomu', 'elektroni', 'kipengele', 'jedwali'],
                'language' => 'sw',
            ],
            [
                'title' => 'Kemia - Miungano ya Kemikali (Kidato cha Pili na Tatu)',
                'content' => 'Muungano wa Ioni (Ionic) hutokea kati ya metali na zisizo-metali kwa kutoa/kupokea elektroni. Muungano wa Kovalenti (Covalent) hutokea kwa kushirikiana elektroni kati ya zisizo-metali. Muungano wa Metali (Metallic) unahusu bahari ya elektroni. Sifa za miungano: Ioni ina kiwango kikubwa cha kuyeyuka.',
                'summary' => 'Aina za miungano ya kemikali na sifa zake.',
                'tags' => 'kemia, miungano, ioni, kovalenti, metali, form2, form3',
                'keywords' => ['miungano', 'ioni', 'kovalenti', 'elektroni'],
                'language' => 'sw',
            ],
            [
                'title' => 'Kemia - Asidi, Besi na Chumvi (Kidato cha Tatu)',
                'content' => 'Asidi huwa na pH chini ya 7 na kugeuza litmus kuwa nyekundu. Besi (Alkali) huwa na pH juu ya 7 na kugeuza litmus kuwa bluu. pH 7 ni neutral. Athari ya Asidi + Besi -> Chumvi + Maji (Neutralization). Mifano: Asidi ya Kloridi (HCl) na Soda ya Magadi (NaOH).',
                'summary' => 'Maana ya pH, asidi, besi na mchakato wa neutralization.',
                'tags' => 'kemia, asidi, besi, chumvi, pH, form3',
                'keywords' => ['asidi', 'besi', 'chumvi', 'pH', 'litmus'],
                'language' => 'sw',
            ],
            [
                'title' => 'Kemia - Kemia Oganiki (Kidato cha Nne na Tano)',
                'content' => 'Kemia Oganiki inahusu misombo ya kaboni. Hidrokaboni: Alkeni (Alkanes - miungano ya pekee), Alkeni (Alkenes - miungano miwili), Alkani (Alkynes - miungano mitatu). Pombe (Alcohols), Asidi oganiki, na Esta. Polima (Polymers) kama plastiki huzalishwa kutokana na monoma.',
                'summary' => 'Muundo wa hidrokaboni na misingi ya kemia ya kaboni.',
                'tags' => 'kemia, oganiki, kaboni, hidrokaboni, plastiki, form4, form5',
                'keywords' => ['kaboni', 'oganiki', 'alkane', 'pombe'],
                'language' => 'sw',
            ],
            // HISABATI (Form I - VI)
            [
                'title' => 'Hisabati - Namba na Hesabu za Msingi (Kidato cha Kwanza)',
                'content' => 'Aina za namba: Namba asilia (1,2,3...), namba nzima (0,1,2...), na namba kamili (integers). BODMAS: Mabano, Maandishi, Kugawanya, Kuzidisha, Kujumlisha, na Kutoa. Vigawo (Factors) na Viidadi (Multiples). Namba tasa (Prime numbers) zina vigawo viwili tu: 1 na yenyewe.',
                'summary' => 'Aina za namba, mpangilio wa hesabu (BODMAS) na namba tasa.',
                'tags' => 'hisabati, namba, BODMAS, namba_tasa, form1',
                'keywords' => ['namba', 'BODMAS', 'vigawo', 'prime'],
                'language' => 'sw',
            ],
            [
                'title' => 'Hisabati - Algebra na Milinganyo (Kidato cha Pili na Tatu)',
                'content' => 'Algebra inatumia herufi (vigezo) kuwakilisha namba. Milinganyo ya mstari (Linear): ax + b = c. Milinganyo pacha: kutatua variables mbili. Milinganyo ya kiduara (Quadratic): ax² + bx + c = 0. Kanuni ya Quadratic: x = [-b ± √(b² - 4ac)] / 2a. Discriminant huamua idadi ya majibu.',
                'summary' => 'Algebra, milinganyo ya mstari na quadratic.',
                'tags' => 'hisabati, algebra, milinganyo, quadratic, x, form2, form3',
                'keywords' => ['algebra', 'milinganyo', 'quadratic', 'vigezo'],
                'language' => 'sw',
            ],
            [
                'title' => 'Hisabati - Jiometri na Trigonometria (Kidato cha Kwanza - Nne)',
                'content' => 'Jiometri: pembe katika mstari ulionyooka (180°), katika pembetatu (180°). Nadharia ya Pythagoras: a² + b² = c² kwa pembetatu ya mstatili. Trigonometria: SOHCAHTOA (Sin = kinyume/hipotenusi, Cos = jirani/hipotenusi, Tan = kinyume/jirani). Eneo la mduara = πr².',
                'summary' => 'Misingi ya maumbo, pembe, nadharia ya Pythagoras na trig.',
                'tags' => 'hisabati, jiometri, pembe, pythagoras, trigonometry, mduara, form1, form4',
                'keywords' => ['jiometri', 'pembe', 'pythagoras', 'sin', 'cos', 'tan'],
                'language' => 'sw',
            ],
            [
                'title' => 'Hisabati - Takwimu na Uwezekano (Kidato cha Tatu na Nne)',
                'content' => 'Takwimu: Wastani (Mean - jumla/idadi), Katikati (Median - namba ya katikati), na Mode (namba inayotokea zaidi). Uwezekano (Probability): P(E) = maokeo yanayotakiwa / jumla ya maokeo. Uwezekano huwa kati ya 0 na 1. Grafu za data: Bar grafu, Histogram, na Pie chart.',
                'summary' => 'Uchambuzi wa data (wastani, mode, median) na uwezekano wa matukio.',
                'tags' => 'hisabati, takwimu, wastani, uwezekano, probability, data, form3, form4',
                'keywords' => ['takwimu', 'wastani', 'mode', 'median', 'uwezekano'],
                'language' => 'sw',
            ],
            [
                'title' => 'Hisabati - Kalkulasi (Calculus) (Kidato cha Tano na Sita)',
                'content' => 'Kalkulasi inahusu mabadiliko. Kutofautisha (Differentiation) hutafuta mteremko (gradient): d/dx(xⁿ) = nxⁿ⁻¹. Kuunganisha (Integration) ni kinyume chake: ∫xⁿ dx = (xⁿ⁺¹ / n+1) + c. Hutumika kutafuta eneo chini ya mchirizo na mabadiliko ya kasi.',
                'summary' => 'Misingi ya kutofautisha na kuunganisha kwa advanced level.',
                'tags' => 'hisabati, calculus, differentiation, integration, mchapuko, form5, form6',
                'keywords' => ['calculus', 'integration', 'differentiation', 'hesabu'],
                'language' => 'sw',
            ],
            // HISTORIA (Form I - VI)
            [
                'title' => 'Historia - Ustaarabu wa Mapema Afrika (Kidato cha Kwanza)',
                'content' => 'Afrika ina historia ndefu. Misri ya Kale: Piramidi, uandishi wa hieroglyphics. Milki ya Kush na Meroe (Sudan). Milki ya Mali (Mansa Musa alikuwa tajiri sana). Aksum (Ethiopia): biashara na ukristo. Zimbabwe Kuu: mji wa mawe na biashara ya dhahabu. Waafrika walikuwa na serikali zilizopangwa kabla ya ukoloni.',
                'summary' => 'Ustaarabu mkubwa wa kale nchini Afrika: Misri, Mali, na Aksum.',
                'tags' => 'historia, ustaarabu, misri, mali, mansa_musa, form1',
                'keywords' => ['historia', 'ustaarabu', 'misri', 'mali', 'zimbabwe'],
                'language' => 'sw',
            ],
            [
                'title' => 'Historia - Biashara ya Watumwa (Kidato cha Pili)',
                'content' => 'Biashara ya watumwa ilileta athari mbaya. Biashara ya kuvuka Atlantiki (Trans-Atlantic): Waafrika walipelekwa Marekani kufanya kazi mashambani. Biashara ya Bahari ya Hindi: Zanzibar ilikuwa kituo kikuu cha soko la watumwa. Athari: kupungua kwa watu, vita, na uharibifu wa kijamii. Kukomeshwa kwa utumwa kulitokea karne ya 19.',
                'summary' => 'Historia ya utumwa Afrika na mchakato wa kukomeshwa kwake.',
                'tags' => 'historia, utumwa, zanzibar, atlantiki, biashara, form2',
                'keywords' => ['utumwa', 'zanzibar', 'biashara', 'slave'],
                'language' => 'sw',
            ],
            [
                'title' => 'Historia - Ukoloni na Mapambano ya Uhuru (Kidato cha Tatu na Nne)',
                'content' => 'Mkutano wa Berlin (1884-85) uligawanya Afrika. Tanganyika ilitawaliwa na Wajerumani kisha Waingereza baada ya WWI. Upinzani: Vita vya Maji Maji (1905-07) dhidi ya Wajerumani. Uhuru: TANU (1954) chini ya Nyerere ilipigania uhuru wa amani. Uhuru wa Tanganyika 9 Des 1961. Muungano na Zanzibar 26 Aprili 1964 kuunda Tanzania.',
                'summary' => 'Uvamizi wa kikoloni, upinzani wa maji maji, na safari ya uhuru wa Tanzania.',
                'tags' => 'historia, ukoloni, uhuru, nyerere, TANU, maji_maji, form3, form4',
                'keywords' => ['ukoloni', 'uhuru', 'nyerere', 'tanzania', 'muungano'],
                'language' => 'sw',
            ],
            [
                'title' => 'Historia - Vita Vikuu vya Dunia na Cold War (Kidato cha Tano na Sita)',
                'content' => 'Vita vikuu vya dunia (WWI na WWII) vilidhoofisha nchi za Ulaya na kuharakisha uhuru wa Afrika. Uundaji wa UN (1945). Cold War (1947-1991): mfarakano kati ya USA (ubepari) na USSR (ujamaa). Harakati za Pan-Africanism (Garvey, Du Bois). Mapambano dhidi ya Apartheid nchini Afrika Kusini (Nelson Mandela).',
                'summary' => 'Athari za vita vya dunia, umoja wa mataifa na siasa za dunia.',
                'tags' => 'historia, UN, vita_vikuu, cold_war, apartheid, mandela, form5, form6',
                'keywords' => ['vita', 'UN', 'mandela', 'usa', 'ussr'],
                'language' => 'sw',
            ],
            // JIOGRAFIA (Form I - VI)
            [
                'title' => 'Jiografia - Ramani na Upimaji wa Ardhi (Kidato cha Kwanza)',
                'content' => 'Ramani ni picha ya dunia kwenye karatasi bapa. Sifa za ramani: Kichwa, Kipimo (Scale), Mwelekeo (Kaskazini), Alama (Legend). Upimaji wa ardhi unatumia gridi (4-figure au 6-figure codes). Muinuko na mabonde vinaonyeshwa kwa mistari ya kontua (contours).',
                'summary' => 'Misingi ya ramani, alama na mbinu za kusoma ramani.',
                'tags' => 'jiografia, ramani, kontua, kaskazini, form1',
                'keywords' => ['ramani', 'gridi', 'scale', 'kontua'],
                'language' => 'sw',
            ],
            [
                'title' => 'Jiografia - Hali ya Hewa na Tabianchi (Kidato cha Pili)',
                'content' => 'Hali ya hewa ni hali ya anga kwa muda mfupi; Tabianchi ni wastani wa miaka mingi. Tanzania ina kanda za joto (pwani) na baridi (nyanda za juu). Misimu ya mvua: Masika (Machi-Mei) na Vuli (Okt-Des). Mabadiliko ya tabianchi yanasababisha kuyeyuka kwa barafu na ukame.',
                'summary' => 'Tofauti kati ya hali ya hewa na tabianchi na misimu ya Tanzania.',
                'tags' => 'jiografia, tabianchi, mvua, masika, vuli, mazingira, form2',
                'keywords' => ['tabianchi', 'mvua', 'joto', 'mazingira'],
                'language' => 'sw',
            ],
            [
                'title' => 'Jiografia - Watu na Makazi (Kidato cha Tatu)',
                'content' => 'Idadi ya watu (Population) huathiriwa na uzazi na kifo. Tanzania ina watu wapatao milioni 60+. Usambazaji si wa usawa; watu wengi wapo mijini (Dar es Salaam) au mikoa yenye rutuba. Makazi yanaweza kuwa ya vijijini (kilimo) au mijini (biashara). Uhamiaji (Migration) kutoka kijijini kwenda mjini ni changamoto ya kijamii.',
                'summary' => 'Idadi ya watu, msongamano, na aina za makazi Tanzania.',
                'tags' => 'jiografia, watu, makazi, uhamiaji, miji, form3',
                'keywords' => ['watu', 'idadi', 'makazi', 'miji'],
                'language' => 'sw',
            ],
            [
                'title' => 'Jiografia - Kilimo nchini Tanzania (Kidato cha Pili na Tatu)',
                'content' => 'Kilimo ni uti wa mgongo wa uchumi. Kilimo cha kujikimu (kula tu) na kilimo cha biashara (zao la biashara kama kahawa, chai, pamba, katani). Udongo wa tifutifu (loam) ni bora. Changamoto: mvua zisizotabirika, ukosefu wa mbolea na teknolojia duni.',
                'summary' => 'Umuhimu wa kilimo, mazao makuu na aina za udongo.',
                'tags' => 'jiografia, kilimo, udongo, kahawa, pamba, uchumi, form2, form3',
                'keywords' => ['kilimo', 'udongo', 'uchumi', 'zao'],
                'language' => 'sw',
            ],
            // KIRAIA (Form I - IV)
            [
                'title' => 'Kiraia - Uraia, Haki na Wajibu (Kidato cha Kwanza)',
                'content' => 'Uraia ni hali ya kuwa mwanachama wa nchi. Unaweza kupata kwa kuzaliwa au kuandikishwa. Haki za raia: kuishi, kuabudu, kutoa maoni. Wajibu wa raia: kulipa kodi, kulinda amani, na kufuata sheria. Katiba ni sheria mama ya nchi.',
                'summary'  => 'Maana ya uraia, haki za binadamu na wajibu wa raia kwa nchi yake.',
                'tags'     => 'kiraia, uraia, haki, wajibu, katiba, form1',
                'keywords' => ['uraia', 'haki', 'wajibu', 'katiba'],
                'language' => 'sw',
            ],
            [
                'title' => 'Kiraia - Muundo wa Serikali ya Tanzania (Kidato cha Pili)',
                'content' => 'Serikali ina mihimili mitatu: 1) Serikali (Executive) - inafanya kazi. 2) Bunge (Legislature) - kutunga sheria. 3) Mahakama (Judiciary) - kutafsiri sheria. Tanzania Bara na Zanzibar zina muungano. Rais ni mkuu wa nchi na serikali. Chaguzi hufanyika kila miaka mitano.',
                'summary'  => 'Mihimili mitatu ya dola na muundo wa uongozi Tanzania.',
                'tags'     => 'kiraia, serikali, bunge, mahakama, rais, muungano, form2',
                'keywords' => ['serikali', 'bunge', 'mahakama', 'rais'],
                'language' => 'sw',
            ],
            // BIASHARA NA HESABU (Form I - IV)
            [
                'title' => 'Biashara - Utangulizi wa Biashara na Masoko (Kidato cha Kwanza na Pili)',
                'content' => 'Biashara ni ubadilishaji wa bidhaa kwa pesa. Biashara ya ndani (Wholesale na Retail) na biashara ya nje (Import/Export). Soko ni mahali pa muuzaji na mnunuzi. Ujasiriamali unahusu kubuni fursa na kuingiza faida. Faida = Mapato - Gharama.',
                'summary'  => 'Misingi ya biashara, aina za masoko na hesabu ya faida.',
                'tags'     => 'biashara, soko, faida, kodi, ujasiriamali, form1, form2',
                'keywords' => ['biashara', 'soko', 'faida', 'mapato'],
                'language' => 'sw',
            ],
            [
                'title' => 'Biashara - Utunzaji wa Kumbukumbu za Fedha (Kidato cha Pili na Tatu)',
                'content' => 'Book-keeping ni kurekodi miamala. Mfumo wa Double Entry: kila muamala unarekodiwa Debit na Credit. Vitabu: Cash Book (fedha), Ledger (akaunti), na Balance Sheet (mali na madeni). Mali = Madeni + Mitaji (Accounting Equation).',
                'summary'  => 'Misingi ya kutunza mahesabu ya biashara na vitabu vya fedha.',
                'tags'     => 'biashara, bookkeeping, debit, credit, balance_sheet, form2, form3',
                'keywords' => ['bookkeeping', 'fedha', 'hesabu', 'mali'],
                'language' => 'sw',
            ],
            // KISWAHILI (Form I - VI)
            [
                'title' => 'Kiswahili - Sarufi na Ngeli za Nomino (Kidato cha Kwanza na Pili)',
                'content' => 'Sarufi ni sheria za lugha. Ngeli ni makundi ya majina (nomino). Mifano: A-WA (watu), KI-VI (vitu), LI-YA (tunda/matunda), U-I (mti/miti). Upatanisho wa kishazi ni muhimu ili sentensi iwe na maana sahihi.',
                'summary'  => 'Makundi ya ngeli katika Kiswahili na sheria za sarufi.',
                'tags'     => 'kiswahili, sarufi, ngeli, nomino, sentensi, form1, form2',
                'keywords' => ['ngeli', 'sarufi', 'nomino', 'lugha'],
                'language' => 'sw',
            ],
            [
                'title' => 'Kiswahili - Fasihi Simulizi na Fasihi Andishi (Kidato cha Pili - Nne)',
                'content' => 'Fasihi Simulizi: methali, vitendawili, hadithi (ngano), na nyimbo zilizopitishwa kwa mdomo. Fasihi Andishi: riwaya, tamthilia, na ushairi. Uhakiki unahusu kuchambua maudhui (ujumbe) na fani (jinsi kilivyoandikwa). Waandishi maarufu: Shaaban Robert, Euphrase Kezilahabi.',
                'summary'  => 'Aina za fasihi na mbinu za uhakiki wa kazi za fasihi.',
                'tags'     => 'kiswahili, fasihi, methali, riwaya, tamthilia, ushairi, form2, form4',
                'keywords' => ['fasihi', 'methali', 'hadithi', 'riwaya'],
                'language' => 'sw',
            ],
            // ELIMU YA AFYA (F1-F4)
            [
                'title' => 'Elimu ya Afya - Magonjwa na Kinga (Kidato cha Kwanza na Pili)',
                'content' => 'Afya ni hali ya ukamilifu wa mwili na akili. Magonjwa ya kuambukiza: Malaria (mbu), Kipuupindu (maji machafu), TB (hewa). Kinga: chanjo, usafi wa mazingira, na mlo kamili. UKIMWI (AIDS) unazuiliwa kwa elimu na tabia salama.',
                'summary'  => 'Magonjwa makuu Tanzania, kinga na umuhimu wa usafi.',
                'tags'     => 'afya, magonjwa, malaria, ukimwi, usafi, mlo, form1, form2',
                'keywords' => ['afya', 'kifo', 'magonjwa', 'usafi'],
                'language' => 'sw',
            ],
            // TEKNOLOJIA YA HABARI (ICT)
            [
                'title' => 'TEHAMA - Utangulizi wa Kompyuta (Kidato cha Kwanza na Pili)',
                'content' => 'Kompyuta ni kifaa cha kielektroniki. Hardware: CPU (ubongo), RAM (kumbukumbu), Monitor. Software: Operating System (Windows) na Apps (Word, Excel). Mtandao (Internet) unatuunganisha na dunia. Usalama wa mtandao ni muhimu kuzuia virusi.',
                'summary'  => 'Sehemu za kompyuta, programu na matumizi ya mtandao.',
                'tags'     => 'tehama, kompyuta, software, hardware, internet, form1, form2',
                'keywords' => ['kompyuta', 'hardware', 'software', 'internet'],
                'language' => 'sw',
            ],
        ];

        foreach ($data as $item) {
            Curriculum::updateOrCreate(
                ['title' => $item['title'], 'language' => $item['language']],
                $item
            );
        }

        $this->command->info('✅ Swahili curriculum rebuilt comprehensively: ' . count($data) . ' entries added.');
    }
}
