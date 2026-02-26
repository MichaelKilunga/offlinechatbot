<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CurriculumSeeder extends Seeder
{
    public function run(): void
    {
        $data = [

            // ══════════════════════════════════════
            //  PHYSICS  (Form I – VI)
            // ══════════════════════════════════════
            [
                'title'    => 'Physics - Introduction to Matter (Form I)',
                'content'  => 'Matter is anything that has mass and occupies space. It exists in three states: Solid (definite shape and volume), Liquid (definite volume, no fixed shape), and Gas (neither fixed shape nor volume). Changes of state: Melting (solid→liquid), Freezing (liquid→solid), Evaporation/Boiling (liquid→gas), Condensation (gas→liquid), Sublimation (solid→gas directly). Density = Mass ÷ Volume (kg/m³).',
                'summary'  => 'States of matter, changes of state, and density.',
                'tags'     => 'physics, form1, matter, states, density',
                'keywords' => ['matter', 'mass', 'volume', 'solid', 'liquid', 'gas', 'density', 'melting', 'boiling', 'sublimation'],
                'language' => 'en',
            ],
            [
                'title'    => 'Physics - Force and Motion (Form I & II)',
                'content'  => 'A force is a push or pull acting on an object. Newton\'s Laws: 1st Law – an object remains at rest or uniform motion unless acted on by an external force (Inertia). 2nd Law – F = ma (force = mass × acceleration). 3rd Law – every action has an equal and opposite reaction. Weight W = mg. Friction opposes motion. Velocity = displacement ÷ time. Acceleration = change in velocity ÷ time.',
                'summary'  => 'Newton\'s three laws of motion, force, weight, and acceleration.',
                'tags'     => 'physics, form1, form2, force, motion, newton, acceleration',
                'keywords' => ['force', 'motion', 'newton', 'acceleration', 'velocity', 'inertia', 'mass', 'weight', 'friction', 'F=ma'],
                'language' => 'en',
            ],
            [
                'title'    => 'Physics - Energy, Work and Power (Form II)',
                'content'  => 'Work is done when a force moves an object in the direction of the force: W = F × d (Joules). Energy is the ability to do work. Kinetic Energy KE = ½mv². Potential Energy PE = mgh. Law of Conservation of Energy: energy cannot be created or destroyed, only converted. Power is the rate of doing work: P = W/t (Watts). Efficiency = (useful output energy ÷ total input energy) × 100%.',
                'summary'  => 'Work, KE, PE, conservation of energy, and power.',
                'tags'     => 'physics, form2, energy, work, power, kinetic, potential',
                'keywords' => ['energy', 'work', 'power', 'kinetic', 'potential', 'joule', 'watt', 'conservation', 'efficiency', 'force'],
                'language' => 'en',
            ],
            [
                'title'    => 'Physics - Light and Optics (Form II)',
                'content'  => 'Light travels in straight lines at 3×10⁸ m/s in a vacuum. Reflection: angle of incidence = angle of reflection (measured from normal). Refraction occurs when light passes between media of different densities – Snell\'s Law: n₁sinθ₁ = n₂sinθ₂. Refractive index n = sin i / sin r. Lenses: Convex (converging) – used in cameras and magnifying glasses. Concave (diverging) – used to correct short-sight.',
                'summary'  => 'Light: reflection, refraction, Snell\'s Law, and lenses.',
                'tags'     => 'physics, form2, light, optics, reflection, refraction, lenses',
                'keywords' => ['light', 'reflection', 'refraction', 'snell', 'lens', 'convex', 'concave', 'optics', 'refractive index', 'normal'],
                'language' => 'en',
            ],
            [
                'title'    => 'Physics - Waves and Sound (Form III)',
                'content'  => 'Waves transfer energy without transferring matter. Transverse waves: vibration perpendicular to direction of travel (e.g. light, water waves). Longitudinal waves: vibration parallel to direction of travel (e.g. sound). Key quantities: Amplitude (A), Wavelength (λ), Frequency (f), Period (T=1/f), Wave speed v = fλ. Sound requires a medium; speed in air ≈ 340 m/s. Ultrasound (>20 kHz) is used in medical imaging.',
                'summary'  => 'Wave types, properties, speed formula, and sound.',
                'tags'     => 'physics, form3, waves, sound, transverse, longitudinal, frequency',
                'keywords' => ['waves', 'sound', 'transverse', 'longitudinal', 'frequency', 'wavelength', 'amplitude', 'speed', 'ultrasound', 'period'],
                'language' => 'en',
            ],
            [
                'title'    => 'Physics - Electricity and Ohm\'s Law (Form III & IV)',
                'content'  => 'Electric current (I) is the flow of charge: I = Q/t (Amperes). Ohm\'s Law: V = IR (Voltage = Current × Resistance). Resistance in Series: R_total = R₁ + R₂ + … Resistance in Parallel: 1/R_total = 1/R₁ + 1/R₂ + … Power: P = VI = I²R = V²/R (Watts). EMF is the energy supplied per unit charge by a source. Kirchhoff\'s Laws: sum of currents at a junction = 0; sum of EMFs = sum of potential drops in a loop.',
                'summary'  => 'Current, Ohm\'s Law, series/parallel circuits, and power.',
                'tags'     => 'physics, form3, form4, electricity, ohm, current, voltage, resistance',
                'keywords' => ['electricity', 'current', 'voltage', 'resistance', 'ohm', 'series', 'parallel', 'power', 'EMF', 'kirchhoff'],
                'language' => 'en',
            ],
            [
                'title'    => 'Physics - Magnetism and Electromagnetism (Form IV)',
                'content'  => 'Magnets have north and south poles; like poles repel, unlike poles attract. Magnetic field lines run from N to S outside a magnet. Electromagnetism: a current-carrying conductor produces a magnetic field (right-hand rule). Fleming\'s Left-Hand Rule gives the direction of force on a current in a magnetic field (motors). Fleming\'s Right-Hand Rule gives the direction of induced current (generators). Faraday\'s Law: EMF = −dΦ/dt.',
                'summary'  => 'Magnetism, electromagnetic induction, motors, and generators.',
                'tags'     => 'physics, form4, magnetism, electromagnetism, faraday, motor, generator',
                'keywords' => ['magnetism', 'magnet', 'electromagnetic', 'induction', 'faraday', 'motor', 'generator', 'fleming', 'flux', 'EMF'],
                'language' => 'en',
            ],
            [
                'title'    => 'Physics - Radioactivity and Nuclear Physics (Form IV)',
                'content'  => 'Radioactivity is the spontaneous emission of particles/rays from unstable nuclei. Three types: Alpha (α) – helium nuclei, stopped by paper; Beta (β) – fast electrons, stopped by aluminium; Gamma (γ) – high-energy EM radiation, needs thick lead/concrete. Half-life is the time for half the nuclei to decay. Nuclear fission splits heavy nuclei releasing enormous energy (nuclear reactors). Nuclear fusion joins light nuclei (occurs in stars).',
                'summary'  => 'Types of radioactive decay, half-life, fission, and fusion.',
                'tags'     => 'physics, form4, radioactivity, nuclear, alpha, beta, gamma, half-life',
                'keywords' => ['radioactivity', 'nuclear', 'alpha', 'beta', 'gamma', 'half-life', 'fission', 'fusion', 'decay', 'radiation'],
                'language' => 'en',
            ],
            [
                'title'    => 'Physics - Mechanics: Projectiles & Circular Motion (Form V & VI)',
                'content'  => 'A projectile moves under gravity alone. Horizontal: x = ucosθ·t (constant velocity). Vertical: y = usinθ·t − ½gt². Range R = u²sin2θ / g. Maximum height H = u²sin²θ / 2g. Circular motion requires centripetal force F = mv²/r directed toward the centre. Centripetal acceleration a = v²/r = ω²r. Angular velocity ω = 2πf. For orbits: gravitational force provides centripetal force: GMm/r² = mv²/r.',
                'summary'  => 'Projectile motion, range, centripetal force, and circular motion.',
                'tags'     => 'physics, form5, form6, projectiles, circular motion, mechanics, advanced',
                'keywords' => ['projectile', 'circular motion', 'centripetal', 'range', 'gravity', 'orbit', 'angular velocity', 'mechanics', 'advanced'],
                'language' => 'en',
            ],

            // ══════════════════════════════════════
            //  BIOLOGY  (Form I – VI)
            // ══════════════════════════════════════
            [
                'title'    => 'Biology - Cell Structure and Function (Form I)',
                'content'  => 'The cell is the basic unit of life. Prokaryotes (e.g. bacteria) have no membrane-bound nucleus. Eukaryotes (plants, animals, fungi) have a true nucleus. Key organelles: Cell membrane (controls entry/exit), Nucleus (contains DNA, controls cell activities), Cytoplasm (site of chemical reactions), Mitochondria (ATP production – powerhouse), Ribosomes (protein synthesis), Chloroplasts (photosynthesis – plants only), Vacuole (storage), Cell wall (plants – rigidity).',
                'summary'  => 'Cell types, organelles, and their functions.',
                'tags'     => 'biology, form1, cell, organelles, nucleus, mitochondria',
                'keywords' => ['cell', 'nucleus', 'membrane', 'mitochondria', 'ribosome', 'chloroplast', 'organelle', 'prokaryote', 'eukaryote', 'DNA'],
                'language' => 'en',
            ],
            [
                'title'    => 'Biology - Nutrition and Digestion (Form I & II)',
                'content'  => 'Nutrition provides the body with energy and materials for growth. Classes of food: Carbohydrates (energy), Proteins (growth/repair), Fats (insulation/energy), Vitamins, Minerals, Water, and Fibre. Digestion breaks large food molecules into small ones. Organs: Mouth (amylase breaks starch), Stomach (pepsin + HCl breaks proteins), Small intestine (lipase, pancreatic amylase; absorption of nutrients), Large intestine (water absorption). Villi increase surface area for absorption.',
                'summary'  => 'Food classes, digestive organs, enzymes, and absorption.',
                'tags'     => 'biology, form1, form2, nutrition, digestion, enzymes, absorption',
                'keywords' => ['nutrition', 'digestion', 'carbohydrates', 'proteins', 'enzymes', 'stomach', 'intestine', 'villi', 'absorption', 'amylase'],
                'language' => 'en',
            ],
            [
                'title'    => 'Biology - Photosynthesis and Respiration (Form II)',
                'content'  => 'Photosynthesis: 6CO₂ + 6H₂O + light energy → C₆H₁₂O₆ + 6O₂. Occurs in chloroplasts (chlorophyll traps light). Factors affecting rate: light intensity, CO₂ concentration, temperature. Aerobic Respiration: C₆H₁₂O₆ + 6O₂ → 6CO₂ + 6H₂O + 38 ATP. Occurs in mitochondria. Anaerobic Respiration (no oxygen): in yeast → C₂H₅OH + CO₂ (fermentation); in humans → lactic acid (muscle fatigue).',
                'summary'  => 'Photosynthesis equation, limiting factors, and aerobic/anaerobic respiration.',
                'tags'     => 'biology, form2, photosynthesis, respiration, chlorophyll, ATP',
                'keywords' => ['photosynthesis', 'respiration', 'chlorophyll', 'glucose', 'oxygen', 'ATP', 'mitochondria', 'fermentation', 'anaerobic', 'aerobic'],
                'language' => 'en',
            ],
            [
                'title'    => 'Biology - Classification of Living Things (Form II)',
                'content'  => 'Classification groups organisms by shared characteristics. Five Kingdoms: Monera (bacteria – prokaryotes), Protoctista (amoeba, algae), Fungi (mushrooms, yeast – absorb nutrients), Plantae (multicellular, photosynthetic), Animalia (multicellular, ingest food). Hierarchy: Kingdom → Phylum → Class → Order → Family → Genus → Species. Binomial nomenclature: Genus species (e.g. Homo sapiens). Vertebrates have a backbone; invertebrates do not.',
                'summary'  => 'Five kingdoms, taxonomic hierarchy, and binomial naming.',
                'tags'     => 'biology, form2, classification, kingdoms, taxonomy, vertebrates',
                'keywords' => ['classification', 'kingdoms', 'monera', 'fungi', 'animalia', 'taxonomy', 'binomial', 'vertebrates', 'invertebrates', 'species'],
                'language' => 'en',
            ],
            [
                'title'    => 'Biology - Transport in Plants and Animals (Form II & III)',
                'content'  => 'Plants transport water and minerals via xylem (upward, by transpiration pull) and sugars via phloem (bidirectional). Transpiration is the evaporation of water through stomata. In humans, the heart (4 chambers) pumps blood through arteries (away from heart), veins (toward heart), and capillaries (exchange). Blood components: red blood cells (haemoglobin carries O₂), white blood cells (immunity), platelets (clotting), and plasma.',
                'summary'  => 'Plant transport (xylem/phloem) and human circulatory system.',
                'tags'     => 'biology, form2, form3, transport, xylem, phloem, heart, blood',
                'keywords' => ['xylem', 'phloem', 'transpiration', 'heart', 'blood', 'artery', 'vein', 'capillary', 'haemoglobin', 'stomata'],
                'language' => 'en',
            ],
            [
                'title'    => 'Biology - Human Reproduction (Form III)',
                'content'  => 'Male reproductive system: testes (produce sperm and testosterone), epididymis, vas deferens, urethra, penis. Female reproductive system: ovaries (produce ova and oestrogen/progesterone), fallopian tubes, uterus, cervix, vagina. Fertilisation occurs in the fallopian tube. The fertilised egg (zygote) implants in the uterus. Gestation period: ~9 months (38 weeks). The placenta exchanges nutrients and gases between mother and fetus.',
                'summary'  => 'Male and female reproductive organs, fertilisation, and gestation.',
                'tags'     => 'biology, form3, reproduction, fertilisation, uterus, placenta, pregnancy',
                'keywords' => ['reproduction', 'sperm', 'ovum', 'fertilisation', 'uterus', 'placenta', 'testes', 'ovaries', 'pregnancy', 'gestation'],
                'language' => 'en',
            ],
            [
                'title'    => 'Biology - Genetics and Heredity (Form IV & V)',
                'content'  => 'Genetics is the study of heredity. DNA (deoxyribonucleic acid) carries genetic information in a double-helix structure. Genes are segments of DNA on chromosomes. Mendel\'s Laws: Law of Segregation – alleles separate during gamete formation. Law of Independent Assortment – genes for different traits are inherited independently. Dominant alleles (shown as capital, e.g. A) mask recessive alleles (a). Genotype is the genetic makeup; phenotype is the observable trait. Mutation is a change in DNA sequence.',
                'summary'  => 'DNA, genes, Mendel\'s laws, dominant/recessive, genotype/phenotype.',
                'tags'     => 'biology, form4, form5, genetics, heredity, DNA, mendel, alleles',
                'keywords' => ['genetics', 'DNA', 'genes', 'mendel', 'dominant', 'recessive', 'alleles', 'genotype', 'phenotype', 'chromosome', 'mutation'],
                'language' => 'en',
            ],
            [
                'title'    => 'Biology - Ecology and Environment (Form IV)',
                'content'  => 'Ecology is the study of relationships between organisms and their environment. Ecosystem = biotic (living) + abiotic (non-living) components. Food chain: Producer → Primary Consumer → Secondary Consumer → Tertiary Consumer. Food webs show interconnected food chains. Energy flows; matter cycles (carbon, nitrogen, water cycles). Biodiversity is the variety of life in an area. Threats: deforestation, pollution, climate change. Conservation: protected areas, captive breeding.',
                'summary'  => 'Ecosystems, food chains/webs, energy flow, and conservation.',
                'tags'     => 'biology, form4, ecology, ecosystem, food chain, biodiversity, conservation',
                'keywords' => ['ecology', 'ecosystem', 'food chain', 'food web', 'biodiversity', 'producer', 'consumer', 'conservation', 'carbon cycle', 'environment'],
                'language' => 'en',
            ],

            // ══════════════════════════════════════
            //  CHEMISTRY  (Form I – VI)
            // ══════════════════════════════════════
            [
                'title'    => 'Chemistry - Laboratory Safety and Equipment (Form I)',
                'content'  => 'Laboratory safety rules: always wear protective equipment (goggles, lab coat, gloves); never taste chemicals; heat liquids carefully by pointing test tubes away from people; know the location of fire extinguishers and first aid. Common equipment: Beaker (mixing), Conical flask (reactions), Burette (accurate liquid volume), Pipette (measuring), Bunsen burner (heating). Hazard symbols: Flammable (flame), Corrosive (acid drops on hand), Toxic (skull), Oxidising (circle with flame).',
                'summary'  => 'Lab safety rules, common equipment, and hazard symbols.',
                'tags'     => 'chemistry, form1, laboratory, safety, equipment, hazard',
                'keywords' => ['laboratory', 'safety', 'equipment', 'beaker', 'burner', 'hazard', 'goggles', 'flammable', 'corrosive', 'toxic'],
                'language' => 'en',
            ],
            [
                'title'    => 'Chemistry - Atomic Structure and the Periodic Table (Form II)',
                'content'  => 'An atom consists of a nucleus (protons + neutrons) surrounded by electrons. Atomic number = number of protons. Mass number = protons + neutrons. Isotopes: same element, different neutron numbers (e.g. ¹²C and ¹⁴C). The Periodic Table (Mendeleev, 1869) arranges elements by increasing atomic number. Groups (columns) have similar properties. Periods (rows) show increasing electron shells. Metals (left), non-metals (right), metalloids (middle staircase).',
                'summary'  => 'Atomic structure, isotopes, and Periodic Table organisation.',
                'tags'     => 'chemistry, form2, atomic structure, periodic table, isotopes, protons',
                'keywords' => ['atom', 'proton', 'electron', 'neutron', 'atomic number', 'mass number', 'isotope', 'periodic table', 'mendeleev', 'shell'],
                'language' => 'en',
            ],
            [
                'title'    => 'Chemistry - Chemical Bonding (Form II & III)',
                'content'  => 'Ionic bonding: transfer of electrons between metal and non-metal, forming ions held by electrostatic attraction (e.g. NaCl). Properties: high melting point, conducts electricity when molten/dissolved. Covalent bonding: sharing of electrons between non-metals (e.g. H₂O, CH₄). Properties: low melting point, poor conductor. Metallic bonding: sea of delocalised electrons around positive ion lattice – explains conductivity and malleability of metals.',
                'summary'  => 'Ionic, covalent, and metallic bonding with properties.',
                'tags'     => 'chemistry, form2, form3, bonding, ionic, covalent, metallic',
                'keywords' => ['bonding', 'ionic', 'covalent', 'metallic', 'electron', 'ion', 'NaCl', 'electronegativity', 'lattice', 'sharing'],
                'language' => 'en',
            ],
            [
                'title'    => 'Chemistry - Acids, Bases and Salts (Form III)',
                'content'  => 'Acids donate H⁺ ions (protons) – e.g. HCl, H₂SO₄, HNO₃. Bases accept H⁺ – e.g. NaOH, Ca(OH)₂. Alkalis are soluble bases. pH scale: 0 = strong acid, 7 = neutral, 14 = strong alkali. Indicators: litmus (red in acid, blue in base), universal indicator gives pH value. Neutralisation: Acid + Base → Salt + Water. Acid + Metal → Salt + Hydrogen. Acid + Carbonate → Salt + Water + CO₂. Common salts: NaCl, CuSO₄, CaCO₃.',
                'summary'  => 'Acids, bases, pH, indicators, and neutralisation reactions.',
                'tags'     => 'chemistry, form3, acids, bases, pH, salts, neutralisation',
                'keywords' => ['acid', 'base', 'alkali', 'pH', 'neutral', 'salt', 'neutralisation', 'litmus', 'HCl', 'indicator'],
                'language' => 'en',
            ],
            [
                'title'    => 'Chemistry - Chemical Equations and Stoichiometry (Form III)',
                'content'  => 'A chemical equation shows reactants (left) → products (right) using symbols and formulae. Must be balanced to obey Conservation of Mass (Lavoisier). Balancing steps: count atoms on each side; add coefficients (not subscripts) to balance. Mole concept: 1 mole = 6.02×10²³ particles (Avogadro\'s number). Molar mass = sum of atomic masses (g/mol). Moles = mass ÷ molar mass. Stoichiometry uses mole ratios from balanced equations to calculate reactant/product amounts.',
                'summary'  => 'Balancing equations, mole concept, and stoichiometry.',
                'tags'     => 'chemistry, form3, equations, balancing, stoichiometry, mole, avogadro',
                'keywords' => ['equations', 'balancing', 'stoichiometry', 'mole', 'avogadro', 'molar mass', 'reactants', 'products', 'coefficients', 'conservation'],
                'language' => 'en',
            ],
            [
                'title'    => 'Chemistry - Organic Chemistry (Form V & VI)',
                'content'  => 'Organic chemistry studies carbon compounds. Hydrocarbons contain only C and H. Alkanes (CₙH₂ₙ₊₂) – saturated, single bonds, e.g. methane (CH₄), ethane (C₂H₆). Alkenes (CₙH₂ₙ) – unsaturated, one double bond, e.g. ethene (C₂H₄) – decolourises bromine water. Alcohols: –OH group, e.g. ethanol (C₂H₅OH). Carboxylic acids: –COOH group, e.g. ethanoic acid (CH₃COOH). Esterification: alcohol + acid → ester + water (pleasant smell). Polymers: long chains of monomers (e.g. polythene).',
                'summary'  => 'Alkanes, alkenes, alcohols, acids, esterification, and polymers.',
                'tags'     => 'chemistry, form5, form6, organic, alkane, alkene, alcohol, polymer, advanced',
                'keywords' => ['organic', 'alkane', 'alkene', 'alcohol', 'ester', 'polymer', 'methane', 'ethanol', 'carboxylic', 'hydrocarbon'],
                'language' => 'en',
            ],

            // ══════════════════════════════════════
            //  MATHEMATICS  (Form I – VI)
            // ══════════════════════════════════════
            [
                'title'    => 'Mathematics - Numbers and Number System (Form I)',
                'content'  => 'Types of numbers: Natural (1,2,3…), Whole (0,1,2,3…), Integers (…-2,-1,0,1,2…), Rational (p/q where q≠0), Irrational (π, √2), Real (all above). BODMAS/PEMDAS: Brackets → Orders (powers/roots) → Division → Multiplication → Addition → Subtraction. Factors and Multiples: HCF (Highest Common Factor), LCM (Lowest Common Multiple). Prime numbers have exactly two factors: 1 and themselves. Standard form: a × 10ⁿ where 1 ≤ a < 10.',
                'summary'  => 'Number types, BODMAS, HCF, LCM, primes, and standard form.',
                'tags'     => 'mathematics, form1, numbers, BODMAS, HCF, LCM, primes',
                'keywords' => ['numbers', 'integer', 'rational', 'irrational', 'BODMAS', 'HCF', 'LCM', 'prime', 'standard form', 'factor'],
                'language' => 'en',
            ],
            [
                'title'    => 'Mathematics - Algebra: Linear and Quadratic Equations (Form II & III)',
                'content'  => 'Algebra uses letters (variables) to represent unknown quantities. Linear equation: ax + b = 0; solution x = −b/a. Simultaneous equations (two unknowns): solve by substitution or elimination. Quadratic equation: ax² + bx + c = 0. Solutions by: factorisation, completing the square, or quadratic formula x = (−b ± √(b²−4ac)) / 2a. Discriminant b²−4ac: >0 (two real roots), =0 (one repeated root), <0 (no real roots). Inequalities: treat as equation but flip the sign when multiplying/dividing by a negative.',
                'summary'  => 'Linear, simultaneous, and quadratic equations with the formula.',
                'tags'     => 'mathematics, form2, form3, algebra, linear, quadratic, equations',
                'keywords' => ['algebra', 'linear', 'quadratic', 'equation', 'substitution', 'elimination', 'discriminant', 'factorisation', 'variable', 'simultaneous'],
                'language' => 'en',
            ],
            [
                'title'    => 'Mathematics - Geometry: Shapes, Angles and Theorems (Form I-III)',
                'content'  => 'Angles in a triangle sum to 180°. Angles on a straight line = 180°. Angles at a point = 360°. Pythagoras\' Theorem: a² + b² = c² (right-angled triangles). Circle theorems: angle at centre = 2× angle at circumference; angles in same segment are equal; opposite angles of a cyclic quadrilateral add to 180°. Area formulas: Triangle = ½bh; Circle = πr²; Trapezium = ½(a+b)h. Surface area and volume of cylinder, cone, sphere: V(sphere) = ⁴⁄₃πr³.',
                'summary'  => 'Angle rules, Pythagoras, circle theorems, and area/volume formulas.',
                'tags'     => 'mathematics, form1, form2, form3, geometry, pythagoras, angles, circle',
                'keywords' => ['geometry', 'pythagoras', 'triangle', 'circle', 'angle', 'area', 'volume', 'trapezium', 'theorem', 'sphere'],
                'language' => 'en',
            ],
            [
                'title'    => 'Mathematics - Trigonometry (Form III & IV)',
                'content'  => 'Trigonometry studies relationships between angles and sides of triangles. For right-angled triangles: sinθ = opposite/hypotenuse, cosθ = adjacent/hypotenuse, tanθ = opposite/adjacent – remembered as SOH-CAH-TOA. Special angles: sin30°=0.5, cos60°=0.5, sin45°=cos45°=√2/2, tan45°=1. For non-right triangles: Sine Rule a/sinA = b/sinB = c/sinC. Cosine Rule: a² = b² + c² − 2bc·cosA. Area of any triangle = ½ab·sinC.',
                'summary'  => 'SOH-CAH-TOA, special angles, sine rule, cosine rule.',
                'tags'     => 'mathematics, form3, form4, trigonometry, sine, cosine, tangent, triangle',
                'keywords' => ['trigonometry', 'sine', 'cosine', 'tangent', 'SOH-CAH-TOA', 'sine rule', 'cosine rule', 'hypotenuse', 'angle', 'triangle'],
                'language' => 'en',
            ],
            [
                'title'    => 'Mathematics - Statistics and Probability (Form III & IV)',
                'content'  => 'Statistics involves collecting, organising, and interpreting data. Measures of central tendency: Mean = Σx/n (sum/count), Median = middle value when ordered, Mode = most frequent value. Measures of spread: Range = max−min, Standard deviation measures average distance from mean. Probability: P(E) = favourable outcomes / total outcomes; 0 ≤ P ≤ 1. For mutually exclusive events: P(A or B) = P(A)+P(B). For independent events: P(A and B) = P(A)×P(B).',
                'summary'  => 'Mean, median, mode, range, and probability rules.',
                'tags'     => 'mathematics, form3, form4, statistics, probability, mean, median, mode',
                'keywords' => ['statistics', 'probability', 'mean', 'median', 'mode', 'range', 'standard deviation', 'data', 'frequency', 'independent'],
                'language' => 'en',
            ],
            [
                'title'    => 'Mathematics - Calculus: Differentiation and Integration (Form V & VI)',
                'content'  => 'Calculus is the study of rates of change and accumulation. Differentiation: dy/dx measures the gradient (rate of change). Rules: Power rule – d/dx(xⁿ) = nxⁿ⁻¹; Product rule; Chain rule. Integration (reverse of differentiation): ∫xⁿ dx = xⁿ⁺¹/(n+1) + C. Definite integrals calculate area under a curve: ∫ₐᵇ f(x) dx. Applications: finding maxima/minima (set dy/dx=0), velocity, acceleration, areas, and volumes. Developed independently by Newton and Leibniz.',
                'summary'  => 'Differentiation rules, integration, and applications of calculus.',
                'tags'     => 'mathematics, form5, form6, calculus, differentiation, integration, advanced',
                'keywords' => ['calculus', 'differentiation', 'integration', 'derivative', 'integral', 'gradient', 'area', 'newton', 'leibniz', 'power rule'],
                'language' => 'en',
            ],

            // ══════════════════════════════════════
            //  HISTORY  (Form I – VI)
            // ══════════════════════════════════════
            [
                'title'    => 'History - Early African Civilisations (Form I)',
                'content'  => 'Africa produced great early civilisations. Ancient Egypt (Nile Valley): famous for pyramids, hieroglyphics, and advanced irrigation. Kush/Nubia (present-day Sudan): iron-smelting centre, rivalled Egypt. Mali Empire (West Africa): rich in gold and salt; Emperor Mansa Musa was among history\'s wealthiest rulers. Axum (modern Ethiopia): major trade hub and early Christian kingdom. Great Zimbabwe: large stone city, centre of gold trade in East/Southern Africa.',
                'summary'  => 'Key early African civilisations: Egypt, Mali, Axum, Zimbabwe.',
                'tags'     => 'history, form1, africa, civilisations, egypt, mali, axum, zimbabwe',
                'keywords' => ['civilisation', 'egypt', 'mali', 'axum', 'zimbabwe', 'mansa musa', 'nubia', 'gold', 'pyramids', 'iron smelting'],
                'language' => 'en',
            ],
            [
                'title'    => 'History - The Slave Trade (Form II)',
                'content'  => 'Two major slave trades affected Africa. Trans-Atlantic Slave Trade (16th–19th century): enslaved West Africans transported to the Americas; Britain abolished it in 1807. East African Slave Trade: operated mainly through Zanzibar; enslaved people taken to Arabia, India, and Persia. Effects on Africa: depopulation, social disruption, wars. Zanzibar was the largest slave market in East Africa. The trade was finally abolished there in 1873 (under British pressure on Sultan Barghash).',
                'summary'  => 'Trans-Atlantic and East African slave trades, effects, and abolition.',
                'tags'     => 'history, form2, slave trade, zanzibar, atlantic, abolition',
                'keywords' => ['slave trade', 'slavery', 'zanzibar', 'trans-atlantic', 'abolition', 'africa', 'west africa', 'barghash', 'americas', 'depopulation'],
                'language' => 'en',
            ],
            [
                'title'    => 'History - Colonisation of Africa (Form III)',
                'content'  => 'The Berlin Conference (1884–85) divided Africa among European powers without African participation – called the "Scramble for Africa". Tanzania (then Tanganyika) was colonised by Germany (German East Africa) after Berlin, then handed to Britain after WWI (League of Nations mandate). Reasons for colonisation: raw materials, markets, strategic interests, and "civilising mission". African resistance: Maji Maji Rebellion (1905–07) in Tanganyika against German rule.',
                'summary'  => 'Berlin Conference, scramble for Africa, German colonial rule in Tanganyika.',
                'tags'     => 'history, form3, colonialism, berlin conference, tanganyika, germany, maji maji',
                'keywords' => ['colonialism', 'berlin conference', 'tanganyika', 'germany', 'scramble for africa', 'maji maji', 'resistance', 'colonisation', 'mandate', 'europe'],
                'language' => 'en',
            ],
            [
                'title'    => 'History - African Nationalism and Independence (Form IV)',
                'content'  => 'Nationalism is the desire of people to govern themselves. Factors that promoted African nationalism: World Wars (Africans fought for European freedoms but had none), Pan-Africanism (Marcus Garvey, W.E.B. Du Bois), education, and urbanisation. Tanzania\'s independence: TANU (founded 1954 by Julius Nyerere) led peaceful independence movement; Tanganyika became independent on 9 December 1961. Zanzibar Revolution: January 1964. United Republic of Tanzania: 26 April 1964.',
                'summary'  => 'African nationalism, TANU, Nyerere, and Tanzania\'s independence in 1961.',
                'tags'     => 'history, form4, nationalism, independence, TANU, nyerere, tanzania, 1961',
                'keywords' => ['nationalism', 'independence', 'TANU', 'nyerere', 'tanzania', 'tanganyika', 'zanzibar', 'pan-africanism', 'colonialism', 'freedom'],
                'language' => 'en',
            ],
            [
                'title'    => 'History - World War II and Cold War (Form V)',
                'content'  => 'WWII (1939–45) was caused by German aggression under Hitler (Nazi), invasion of Poland, and failure of appeasement. Allied powers (Britain, France, USA, USSR) defeated the Axis (Germany, Italy, Japan). Holocaust: systematic murder of 6 million Jews. Outcomes: UN formed (1945) to maintain peace; decolonisation accelerated. Cold War (1947–1991): ideological tension between USA (capitalism) and USSR (communism); arms race, space race, proxy wars (Korea, Vietnam). Berlin Wall (1961–1989) symbolised the divide.',
                'summary'  => 'Causes of WWII, outcomes, UN, and Cold War rivalry.',
                'tags'     => 'history, form5, world war 2, cold war, UN, decolonisation, advanced',
                'keywords' => ['world war 2', 'ww2', 'cold war', 'UN', 'hitler', 'holocaust', 'usa', 'ussr', 'decolonisation', 'berlin wall'],
                'language' => 'en',
            ],

            // ══════════════════════════════════════
            //  GEOGRAPHY  (Form I – IV)
            // ══════════════════════════════════════
            [
                'title'    => 'Geography - Maps and Map Reading (Form I)',
                'content'  => 'A map is a representation of the Earth\'s surface on a flat surface. Essential map elements: Title, Scale (e.g. 1:50,000), North arrow, Key/Legend, Grid references. Types: Topographic maps show relief using contour lines (lines joining equal heights). Scale: Representative Fraction (1:50,000 means 1 cm = 500 m). Direction: Cardinal points (N, S, E, W) and compass bearings (000°–360°). Grid references: 4-figure (area) and 6-figure (point).',
                'summary'  => 'Map elements, types, scale, contour lines, and grid references.',
                'tags'     => 'geography, form1, maps, scale, contour, grid reference, direction',
                'keywords' => ['map', 'scale', 'contour', 'grid reference', 'topographic', 'north', 'legend', 'bearing', 'relief', 'elevation'],
                'language' => 'en',
            ],
            [
                'title'    => 'Geography - Weather, Climate and Tanzania (Form II)',
                'content'  => 'Weather is the short-term atmospheric conditions; climate is the average over 30+ years. Factors affecting climate: latitude, altitude, distance from the sea, prevailing winds, ocean currents. Tanzania\'s climate zones: Coastal (hot, humid); Highlands (cool, 15–20°C); Low plateau (semi-arid). Rainfall seasons: Long rains (Masika – March to May); Short rains (Vuli – October to December). Tanzania\'s wettest area: Southern Highlands (Mbeya, Ruvuma – over 1,500 mm/year).',
                'summary'  => 'Weather vs climate, influencing factors, and Tanzania\'s climate zones.',
                'tags'     => 'geography, form2, weather, climate, tanzania, rainfall, seasons',
                'keywords' => ['weather', 'climate', 'latitude', 'altitude', 'masika', 'vuli', 'rainfall', 'tanzania', 'humidity', 'temperature'],
                'language' => 'en',
            ],
            [
                'title'    => 'Geography - Population and Settlement (Form III)',
                'content'  => 'Population: the total number of people in an area. Key measures: Birth rate (per 1,000 people/year), Death rate, Natural increase rate = birth rate − death rate, Population density = people/km². Tanzania population: ~62 million (2023). Population distribution in Tanzania is uneven – dense in highlands, Kilimanjaro, cities; sparse in arid central areas. Settlement types: Rural (villages) vs Urban (towns, cities). Urbanisation: movement from rural to urban areas.',
                'summary'  => 'Population measures, Tanzania\'s distribution, and urbanisation.',
                'tags'     => 'geography, form3, population, settlement, urbanisation, density, tanzania',
                'keywords' => ['population', 'birth rate', 'death rate', 'density', 'urbanisation', 'settlement', 'rural', 'urban', 'tanzania', 'distribution'],
                'language' => 'en',
            ],
            [
                'title'    => 'Geography - Agriculture in Tanzania (Form II & III)',
                'content'  => 'Agriculture is Tanzania\'s backbone, employing ~65% of the population. Types: Subsistence (food for family – e.g. maize, cassava) and Commercial (cash crops for export – e.g. coffee, tea, sisal, tobacco, cotton). Irrigation schemes: Kilimanjaro region (coffee), Morogoro (rice). Soil types: Clay (holds water), Sandy (drains fast), Loam (best for farming). Challenges: unreliable rainfall, poor infrastructure, small farm size. Green Revolution: improved seeds, fertilisers.',
                'summary'  => 'Types of farming, cash crops, soil, and challenges in Tanzania.',
                'tags'     => 'geography, form2, form3, agriculture, farming, crops, soil, tanzania',
                'keywords' => ['agriculture', 'farming', 'cassava', 'maize', 'coffee', 'sisal', 'irrigation', 'soil', 'subsistence', 'commercial'],
                'language' => 'en',
            ],

            // ══════════════════════════════════════
            //  CIVICS  (Form I – IV)
            // ══════════════════════════════════════
            [
                'title'    => 'Civics - Citizenship and Rights (Form I)',
                'content'  => 'Citizenship is the legal relationship between an individual and a state. Ways to acquire Tanzanian citizenship: By birth, By descent (Tanzanian parent), By registration or Naturalisation. Rights of citizens: right to vote, education, free speech, fair trial, and freedom of religion. Duties of citizens: obey the law, pay taxes, respect others\' rights, protect national resources, participate in national development. The National Constitution (1977, amended) is the supreme law.',
                'summary'  => 'Citizenship acquisition, rights, duties, and the Tanzanian constitution.',
                'tags'     => 'civics, form1, citizenship, rights, duties, constitution, tanzania',
                'keywords' => ['citizenship', 'rights', 'duties', 'constitution', 'tanzania', 'vote', 'tax', 'naturalisation', 'law', 'freedom'],
                'language' => 'en',
            ],
            [
                'title'    => 'Civics - Government Structure of Tanzania (Form II)',
                'content'  => 'Tanzania has three arms of government: Legislature (Parliament / Bunge) – makes laws; has 393 seats. Executive (President + Cabinet) – implements laws; President is elected every 5 years. Judiciary (Courts) – interprets laws; highest court is the Court of Appeal. Tanzania Mainland and Zanzibar share the Union government but Zanzibar has its own House of Representatives. Local Government: Districts, Municipalities, Cities handle local services. General elections are held every 5 years.',
                'summary'  => 'Three arms of government, Parliament, President, Judiciary, and Local Government.',
                'tags'     => 'civics, form2, government, legislature, executive, judiciary, tanzania, elections',
                'keywords' => ['government', 'parliament', 'bunge', 'president', 'judiciary', 'court', 'election', 'zanzibar', 'constitution', 'legislature'],
                'language' => 'en',
            ],
            [
                'title'    => 'Civics - Human Rights and the Constitution (Form III)',
                'content'  => 'The Universal Declaration of Human Rights (UDHR, 1948) lists 30 rights all humans hold. Key categories: Civil and political rights (right to life, free speech, fair trial); Economic, social, cultural rights (right to work, education, health). In Tanzania these are protected in Part III of the 1977 Constitution. The Human Rights Commission investigates violations. Rights can be limited during public emergencies. Tanzania signed major international human rights treaties.',
                'summary'  => 'UDHR, human rights categories, and Tanzanian constitutional protection.',
                'tags'     => 'civics, form3, human rights, UDHR, constitution, Tanzania',
                'keywords' => ['human rights', 'UDHR', 'constitution', 'civil rights', 'equality', 'freedom', 'right to education', 'tanzania', 'commission', 'treaty'],
                'language' => 'en',
            ],

            // ══════════════════════════════════════
            //  COMMERCE & BOOK-KEEPING (Form I – IV)
            // ══════════════════════════════════════
            [
                'title'    => 'Commerce - Trade, Markets and Business (Form I & II)',
                'content'  => 'Trade is the buying and selling of goods and services. Home trade: Wholesale (manufacturer → retailer in bulk) and Retail (retailer → consumer). Foreign trade: Import (buying from abroad), Export (selling abroad). A market is where buyers and sellers exchange goods. Functions of a market: sets prices, allocates resources, creates competition. Entrepreneurship: identifying needs and organising resources to meet them profitably. Profit = Revenue – Total Costs. Break-even: Revenue = Total Costs.',
                'summary'  => 'Trade types, wholesale/retail, markets, and basic entrepreneurship.',
                'tags'     => 'commerce, form1, form2, trade, market, wholesale, retail, profit',
                'keywords' => ['trade', 'market', 'wholesale', 'retail', 'import', 'export', 'profit', 'revenue', 'cost', 'entrepreneur'],
                'language' => 'en',
            ],
            [
                'title'    => 'Commerce - Book-keeping: Double Entry (Form II & III)',
                'content'  => 'Book-keeping records financial transactions systematically. Double-entry principle: every transaction has two equal and opposite entries (Debit and Credit). Accounts: Assets (what you own), Liabilities (what you owe), Capital = Assets − Liabilities. Key books: Journal (first record), Ledger (classified accounts – each account has Debit and Credit sides), Cash Book (records cash receipts and payments). Trial Balance: if Debit totals = Credit totals, books are balanced. Balance Sheet shows financial position at a date.',
                'summary'  => 'Double-entry, ledger accounts, trial balance, and balance sheet.',
                'tags'     => 'commerce, form2, form3, bookkeeping, double entry, ledger, trial balance, balance sheet',
                'keywords' => ['bookkeeping', 'double entry', 'debit', 'credit', 'assets', 'liabilities', 'capital', 'ledger', 'trial balance', 'balance sheet'],
                'language' => 'en',
            ],

            // ══════════════════════════════════════
            //  ENGLISH LANGUAGE  (Form I – VI)
            // ══════════════════════════════════════
            [
                'title'    => 'English - Grammar: Parts of Speech (Form I & II)',
                'content'  => 'The eight parts of speech: Noun (names person/place/thing – John, Tanzania, book), Pronoun (replaces noun – he, she, they), Verb (action or state – run, is), Adjective (describes noun – tall, beautiful), Adverb (modifies verb/adjective/adverb – quickly, very), Preposition (shows relationship – in, on, under), Conjunction (joins words/clauses – and, but, because), Interjection (exclamation – Oh!, Wow!). Tense: Past, Present, Future.',
                'summary'  => 'Eight parts of speech with examples and tense overview.',
                'tags'     => 'english, form1, form2, grammar, parts of speech, tenses, noun, verb',
                'keywords' => ['grammar', 'noun', 'verb', 'adjective', 'adverb', 'pronoun', 'conjunction', 'preposition', 'tense', 'sentence'],
                'language' => 'en',
            ],
            [
                'title'    => 'English - Essay Writing and Composition (Form I-IV)',
                'content'  => 'An essay has three main parts: Introduction (introduces the topic and states the thesis), Body (paragraphs each with a topic sentence, evidence, and explanation), Conclusion (summarises and restates the main point). Types: Descriptive (describes a person/place), Narrative (tells a story – use chronological order), Argumentative (supports one view with evidence), Expository (explains a topic). Tips: use connectives (furthermore, however, in conclusion); vary sentence structure; proofread.',
                'summary'  => 'Essay structure, types, and writing techniques.',
                'tags'     => 'english, form1, form2, form3, form4, essay, writing, composition',
                'keywords' => ['essay', 'composition', 'introduction', 'conclusion', 'paragraph', 'narrative', 'descriptive', 'argumentative', 'thesis', 'connectives'],
                'language' => 'en',
            ],

            // ══════════════════════════════════════
            //  AGRICULTURE  (Form I – IV)
            // ══════════════════════════════════════
            [
                'title'    => 'Agriculture - Soil and Crop Production (Form I & II)',
                'content'  => 'Soil is the top layer of the Earth supporting plant growth. Composition: rock particles (45%), air (25%), water (25%), organic matter/humus (5%). Soil profile: O horizon (organic), A horizon (topsoil – most fertile), B horizon (subsoil), C horizon (parent rock). Soil texture: Clay (fine, water-retentive), Sandy (coarse, drains fast), Loam (best – mixture). Crop production requires: land preparation (ploughing, harrowing), planting, weeding, fertilising, pest control, and harvesting.',
                'summary'  => 'Soil composition, profile, texture, and steps in crop production.',
                'tags'     => 'agriculture, form1, form2, soil, crop production, humus, fertility',
                'keywords' => ['soil', 'crop', 'humus', 'clay', 'loam', 'sandy', 'fertility', 'harvest', 'fertiliser', 'ploughing'],
                'language' => 'en',
            ],
            [
                'title'    => 'Agriculture - Livestock Keeping (Form II & III)',
                'content'  => 'Livestock keeping involves rearing animals for food, labour, and income. Common livestock in Tanzania: cattle (milk, meat, hides), goats, sheep, poultry (eggs, meat), pigs. Systems: Zero-grazing (confined) vs Free-range (open pasture). Cattle breeds: local (Zebu – drought tolerant) and exotic (Friesian – high milk yield). Animal health: vaccination prevents diseases (FMD, anthrax); deworming; tick control (dipping). Nutrition: roughage (grass, hay) and concentrates (maize meal, minerals).',
                'summary'  => 'Livestock types, keeping systems, breeds, and animal health.',
                'tags'     => 'agriculture, form2, form3, livestock, cattle, poultry, veterinary, health',
                'keywords' => ['livestock', 'cattle', 'poultry', 'zebu', 'friesian', 'vaccination', 'disease', 'grazing', 'dipping', 'nutrition'],
                'language' => 'en',
            ],

            // ══════════════════════════════════════
            //  COMPUTER STUDIES  (Form I – IV)
            // ══════════════════════════════════════
            [
                'title'    => 'Computer Studies - Introduction to Computers (Form I & II)',
                'content'  => 'A computer is an electronic device that processes data to produce information. Basic parts: Input (keyboard, mouse), Processing (CPU – Central Processing Unit), Storage (RAM = temporary; ROM = permanent; Hard disk = long-term), Output (monitor, printer). Software: System software (operating system – Windows, Linux) and Application software (Word, Excel, browsers). Binary system: computers use 0s and 1s. 1 Byte = 8 bits; 1 KB = 1,024 bytes; 1 MB = 1,024 KB; 1 GB = 1,024 MB.',
                'summary'  => 'Computer components, software types, and data storage units.',
                'tags'     => 'computer, form1, form2, hardware, software, CPU, RAM, binary',
                'keywords' => ['computer', 'CPU', 'RAM', 'ROM', 'hardware', 'software', 'operating system', 'binary', 'input', 'output', 'storage'],
                'language' => 'en',
            ],
        ];

        foreach ($data as $item) {
            \App\Models\Curriculum::updateOrCreate(
                ['title' => $item['title'], 'language' => $item['language']],
                $item
            );
        }

        $this->command->info('✅ English curriculum seeded: ' . count($data) . ' entries added.');
    }
}
