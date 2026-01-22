<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CurriculumSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            // FORM I - PHYSICS
            [
                'title' => 'Introduction to Physics - Matter',
                'content' => 'Matter is anything that has mass and occupies space. It exists in three main states: Solid (fixed shape and volume), Liquid (fixed volume, no fixed shape), and Gas (neither fixed shape nor volume).',
                'summary' => 'Basics of matter and its states.',
                'tags' => 'physics, form1, matter, sayansi',
                'keywords' => ['matter', 'mass', 'space', 'solid', 'liquid', 'gas', 'hali', 'maada'],
                'language' => 'en',
            ],
            [
                'title' => 'Physics - Maada (Matter)',
                'content' => 'Maada ni kitu chochote chenye masi na kinachochukua nafasi. Maada hupatikana katika hali kuu tatu: Uimara (Solid), Kimiminika (Liquid), na Gesi (Gas).',
                'summary' => 'Maelezo ya maada katika Kiswahili.',
                'tags' => 'physics, form1, maada, sayansi',
                'keywords' => ['maada', 'masi', 'nafasi', 'uimara', 'kimiminika', 'gesi', 'matter'],
                'language' => 'sw',
            ],
            // FORM II - BIOLOGY
            [
                'title' => 'Biology - Classification of Living Things',
                'content' => 'Classification is the process of grouping organisms based on their similarities. The five kingdoms include: Monera, Protoctista, Fungi, Plantae, and Animalia. Taxonomy is the science of classification.',
                'summary' => 'Biological classification and the five kingdoms.',
                'tags' => 'biology, form2, classification, kingdoms',
                'keywords' => ['classification', 'kingdoms', 'monera', 'fungi', 'plantae', 'animalia', 'uainishaji'],
                'language' => 'en',
            ],
            // FORM III - CHEMISTRY
            [
                'title' => 'Chemistry - Chemical Equations',
                'content' => 'A chemical equation represents a chemical reaction using symbols and formulas. Reactants are on the left side, and products are on the right. Equations must be balanced to satisfy the Law of Conservation of Mass.',
                'summary' => 'Understanding chemical equations and balancing.',
                'tags' => 'chemistry, form3, equations, reactions',
                'keywords' => ['chemistry', 'equations', 'balanced', 'reactants', 'products', 'kemikali', 'milinganyo'],
                'language' => 'en',
            ],
            // FORM IV - CIVICS
            [
                'title' => 'Civics - Human Rights',
                'content' => 'Human rights are basic rights and freedoms that belong to every person. In Tanzania, these are protected by the Constitution. They include the right to life, freedom of expression, and right to education.',
                'summary' => 'Human rights in the Tanzanian context.',
                'tags' => 'civics, form4, rights, constitution',
                'keywords' => ['rights', 'civics', 'human rights', 'constitution', 'tanzania', 'haki', 'binadamu'],
                'language' => 'en',
            ],
            // FORM V & VI (ADVANCED)
            [
                'title' => 'Advanced Physics - Mechanics (Projectiles)',
                'content' => 'A projectile is an object thrown into the space upon which the only acting force is gravity. Key concepts include horizontal range, maximum height, and time of flight. $R = (u^2 \sin 2\theta) / g$.',
                'summary' => 'Projectile motion for A-level students.',
                'tags' => 'physics, form5, form6, mechanics, projectiles',
                'keywords' => ['projectiles', 'gravity', 'mechanics', 'velocity', 'range', 'height'],
                'language' => 'en',
            ],
            [
                'title' => 'History - Pre-colonial African Societies',
                'content' => 'Before colonialism, African societies were organized into various political systems: centralized (e.g., Buganda, Zulu) and decentralized/stateless. Trade like the Long Distance Trade was prominent.',
                'summary' => 'Pre-colonial political systems and trade.',
                'tags' => 'history, form1, form5, africa, trade',
                'keywords' => ['history', 'africa', 'colonialism', 'trade', 'buganda', 'zulu', 'historia'],
                'language' => 'en',
            ],
             [
                'title' => 'Geography - Solar System',
                'content' => 'The solar system consists of the sun and everything that orbits around it, including eight planets: Mercury, Venus, Earth, Mars, Jupiter, Saturn, Uranus, and Neptune. Earth is the only planet known to support life.',
                'summary' => 'Overview of the solar system.',
                'tags' => 'geography, form1, solar, planets',
                'keywords' => ['solar system', 'planets', 'earth', 'sun', 'orbit', 'mfumo wa jua', 'sayari'],
                'language' => 'en',
            ],
        ];

        foreach ($data as $item) {
            \App\Models\Curriculum::updateOrCreate(
                ['title' => $item['title'], 'language' => $item['language']],
                $item
            );
        }
    }
}
