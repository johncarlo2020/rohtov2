<?php

namespace Database\Seeders;

use App\Models\Answer;
use App\Models\Developer;
use App\Models\Question;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class QuestionSeeder extends Seeder
{
    public function run(): void
    {
        $this->jlg();
        $this->wct();
        $this->pgb();
        $this->mahSing();
        $this->tslaw();
        $this->uda();
        $this->ppb();
        $this->maxim();
        $this->impianEmas();
        $this->tongTor();
        $this->rfPrincessCove();
        $this->keckSeng();
        $this->malton();
        $this->sunway();
        $this->horizonHills();
        $this->sutera();
        $this->genting();
        $this->kprj();
        $this->webest();
    }

    private function createQuestion($developer, $questionText, $answers)
    {
        $q = Question::create([
            'developer_id' => $developer->id,
            'question' => $questionText,
        ]);

        foreach ($answers as $ans) {
            Answer::create([
                'question_id' => $q->id,
                'answer' => $ans['text'],
                'is_correct' => $ans['correct'],
            ]);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | STATION 1 - JLG
    |--------------------------------------------------------------------------
    */

    private function jlg()
    {
        $dev = Developer::where('name', 'JLG')->first();

        $this->createQuestion($dev, 'What type of property is Sanubari?', [
            ['text' => 'High-rise serviced residences', 'correct' => 0],
            ['text' => 'Semi-detached factories', 'correct' => 0],
            ['text' => 'Double-storey terrace homes', 'correct' => 1],
            ['text' => 'Commercial shop offices', 'correct' => 0],
        ]);

        $this->createQuestion($dev, 'Where is Onn Eight located?', [
            ['text' => "Bandar Dato' Onn", 'correct' => 1],
            ['text' => 'Iskandar Puteri', 'correct' => 0],
            ['text' => 'Skudai', 'correct' => 0],
            ['text' => 'Kulai', 'correct' => 0],
        ]);

        $this->createQuestion($dev, 'What is a key feature of the 2-Storey Terrace Homes at Bandar Tiram?', [
            ['text' => 'Private beachfront access for every home', 'correct' => 0],
            ['text' => 'Located in a Low Carbon City (5 Diamond) certified township', 'correct' => 1],
            ['text' => 'High-rise serviced apartments with sea views', 'correct' => 0],
            ['text' => 'Industrial warehouse development', 'correct' => 0],
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | STATION 2 - WCT
    |--------------------------------------------------------------------------
    */

    private function wct()
    {
        $dev = Developer::where('name', 'WCT')->first();

        $this->createQuestion($dev, 'What is a key advantage of Adison @ W City Larkinton?', [
            ['text' => 'Direct access to a private marina', 'correct' => 0],
            ['text' => 'Located just 6KM from the Woodlands Checkpoint', 'correct' => 1],
            ['text' => 'Beachfront serviced apartments', 'correct' => 0],
            ['text' => 'Industrial warehouse development', 'correct' => 0],
        ]);

        $this->createQuestion($dev, 'When is Adison @ W City Larkinton expected to be completed?', [
            ['text' => '2025', 'correct' => 0],
            ['text' => '2026', 'correct' => 0],
            ['text' => '2027', 'correct' => 0],
            ['text' => '2028', 'correct' => 1],
        ]);

        $this->createQuestion($dev, 'What is the size of the W City Larkinton masterplan township?', [
            ['text' => '28 acres', 'correct' => 0],
            ['text' => '68 acres', 'correct' => 1],
            ['text' => '108 acres', 'correct' => 0],
            ['text' => '168 acres', 'correct' => 0],
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | STATION 3 - PGB
    |--------------------------------------------------------------------------
    */

    private function pgb()
    {
        $dev = Developer::where('name', 'PGB')->first();

        $this->createQuestion($dev, 'Where is CALIA Residences located?', [
            ['text' => 'Iskandar Puteri', 'correct' => 0],
            ['text' => 'Danga Bay, Johor Bahru', 'correct' => 1],
            ['text' => "Bandar Dato' Onn", 'correct' => 0],
            ['text' => 'Kulai', 'correct' => 0],
        ]);

        $this->createQuestion($dev, 'How many lifestyle facilities are available at CALIA Residences?', [
            ['text' => '15 lifestyle facilities', 'correct' => 0],
            ['text' => '50 lifestyle facilities', 'correct' => 0],
            ['text' => '20 lifestyle facilities', 'correct' => 0],
            ['text' => '30 holistic lifestyle facilities', 'correct' => 1],
        ]);

        $this->createQuestion($dev, 'What transportation convenience is offered to residents?', [
            ['text' => 'Shuttle service to CIQ and RTS', 'correct' => 1],
            ['text' => 'Free ferry service to Singapore', 'correct' => 0],
            ['text' => 'Private helicopter transfers', 'correct' => 0],
            ['text' => 'Direct MRT station within the development', 'correct' => 0],
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | STATION 4 - MAH SING
    |--------------------------------------------------------------------------
    */

    private function mahSing()
    {
        $dev = Developer::where('name', 'MahSing')->first();

        $this->createQuestion($dev, 'What is the tenure of M Minori?', [
            ['text' => 'Leasehold', 'correct' => 0],
            ['text' => 'Freehold', 'correct' => 1],
            ['text' => 'Commercial Title', 'correct' => 0],
            ['text' => 'Temporary Occupation License', 'correct' => 0],
        ]);

        $this->createQuestion($dev, 'Where is M Tiara located?', [
            ['text' => 'Skudai, Johor', 'correct' => 1],
            ['text' => 'Seri Austin, Johor Bahru', 'correct' => 0],
            ['text' => 'Danga Bay, Johor Bahru', 'correct' => 0],
            ['text' => "Bandar Dato' Onn", 'correct' => 0],
        ]);

        $this->createQuestion($dev, 'Which of the following is NOT a feature of Meridin East?', [
            ['text' => 'Freehold tenure', 'correct' => 0],
            ['text' => 'Private yacht marina', 'correct' => 1],
            ['text' => 'Family-friendly township living', 'correct' => 0],
            ['text' => 'Affordable monthly instalments', 'correct' => 0],
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | STATION 5 - TSLAW LAND
    |--------------------------------------------------------------------------
    */

    private function tslaw()
    {
        $dev = Developer::where('name', 'TSLaw')->first();

        $this->createQuestion($dev, 'What is the starting price of Skyline (Eastside) @ OneSentosa?', [
            ['text' => 'From RM307K*', 'correct' => 0],
            ['text' => 'From RM407K*', 'correct' => 0],
            ['text' => 'From RM507K*', 'correct' => 1],
            ['text' => 'From RM707K*', 'correct' => 0],
        ]);

        $this->createQuestion($dev, 'Which of the following statements is TRUE about Skyline (Eastside) @ OneSentosa?', [
            ['text' => 'It is a beachfront resort development', 'correct' => 0],
            ['text' => 'It consists of landed terrace homes only', 'correct' => 0],
            ['text' => 'It is an industrial business park', 'correct' => 0],
            ['text' => 'It offers excellent connectivity and future-ready transit access.', 'correct' => 1],
        ]);

        $this->createQuestion($dev, 'Where is Skyline (Eastside) @ OneSentosa located?', [
            ['text' => 'Skudai, Johor', 'correct' => 0],
            ['text' => 'Iskandar Puteri, Johor', 'correct' => 0],
            ['text' => 'Taman Sentosa, Johor Bahru', 'correct' => 1],
            ['text' => 'Pasir Gudang, Johor', 'correct' => 0],
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | STATION 6 - UDA
    |--------------------------------------------------------------------------
    */

    private function uda()
    {
        $dev = Developer::where('name', 'UDA')->first();

        $this->createQuestion($dev, 'Sedili Residensi is specially developed to support which housing initiative?', [
            ['text' => 'PPAM (Program Perumahan Awam Malaysia)', 'correct' => 1],
            ['text' => 'PR1MA Malaysia', 'correct' => 0],
            ['text' => 'Rumah Selangorku', 'correct' => 0],
            ['text' => 'My First Home Scheme', 'correct' => 0],
        ]);

        $this->createQuestion($dev, 'What is a key advantage of UDA Heights - Semi-Detached Homes?', [
            ['text' => 'Beachfront location', 'correct' => 0],
            ['text' => 'Private marina access', 'correct' => 0],
            ['text' => 'Fully furnished units', 'correct' => 0],
            ['text' => 'Ready-to-move-in homes', 'correct' => 1],
        ]);

        $this->createQuestion($dev, 'What type of homes are available at Mutiara Residence?', [
            ['text' => 'Semi-detached homes', 'correct' => 0],
            ['text' => '2- & 3-storey townhouses', 'correct' => 1],
            ['text' => 'Serviced apartment', 'correct' => 0],
            ['text' => 'Shop offices', 'correct' => 0],
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | STATION 7 - PPB PROPERTIES
    |--------------------------------------------------------------------------
    */

    private function ppb()
    {
        $dev = Developer::where('name', 'Southern Marina')->first();

        $this->createQuestion($dev, 'What is the starting price of Southern Marina Residences?', [
            ['text' => 'From RM2xxK*', 'correct' => 0],
            ['text' => 'From RM6xxK*', 'correct' => 1],
            ['text' => 'From RM9xxK*', 'correct' => 0],
            ['text' => 'From RM1.5Million*', 'correct' => 0],
        ]);

        $this->createQuestion($dev, 'Which of the following is NOT a feature of Southern Marina Residences?', [
            ['text' => 'Freehold tenure', 'correct' => 0],
            ['text' => 'Low-density living', 'correct' => 0],
            ['text' => 'Private golf course', 'correct' => 1],
            ['text' => 'Prestigious waterfront location', 'correct' => 0],
        ]);

        $this->createQuestion($dev, 'What is the tenure of Southern Marina Residences?', [
            ['text' => 'Leasehold', 'correct' => 0],
            ['text' => 'Commercial Title', 'correct' => 0],
            ['text' => 'Temporary Occupation License', 'correct' => 0],
            ['text' => 'Freehold', 'correct' => 1],
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | STATION 8 - MAXIM
    |--------------------------------------------------------------------------
    */

    private function maxim()
    {
        $dev = Developer::where('name', 'Maxim')->first();

        $this->createQuestion($dev, 'Approximately how far is The Address from the upcoming RTS Link and CIQ?', [
            ['text' => '3km', 'correct' => 1],
            ['text' => '8km', 'correct' => 0],
            ['text' => '15km', 'correct' => 0],
            ['text' => '12km', 'correct' => 0],
        ]);

        $this->createQuestion($dev, 'What innovative feature helps create a cleaner and smarter community at The Address?', [
            ['text' => 'Rainwater Harvesting System', 'correct' => 0],
            ['text' => 'Solar Farm', 'correct' => 0],
            ['text' => 'Electric Vehicle Factory', 'correct' => 0],
            ['text' => 'Smart Waste Collection', 'correct' => 1],
        ]);

        $this->createQuestion($dev, 'What green certification has The Address achieved?', [
            ['text' => 'GreenRE Gold', 'correct' => 0],
            ['text' => 'GreenRE Silver', 'correct' => 1],
            ['text' => 'GreenRE Platinum', 'correct' => 0],
            ['text' => 'LEED Platinum', 'correct' => 0],
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | STATION 9 - IMPIAN EMAS
    |--------------------------------------------------------------------------
    */

    private function impianEmas()
    {
        $dev = Developer::where('name', 'Gunung Impian')->first();

        $this->createQuestion($dev, 'What type of homes are offered at Honeydale Residence?', [
            ['text' => '2-storey cluster homes', 'correct' => 1],
            ['text' => 'Semi-detached homes', 'correct' => 0],
            ['text' => 'Serviced apartments', 'correct' => 0],
            ['text' => 'Shop offices', 'correct' => 0],
        ]);

        $this->createQuestion($dev, 'What is the layout size of the homes at Iconia Garden Residence - 2-storey superlink terrace home?', [
            ['text' => "22' × 75'", 'correct' => 0],
            ['text' => "34' × 75'", 'correct' => 0],
            ['text' => "40' × 80'", 'correct' => 0],
            ['text' => "24' × 80'", 'correct' => 1],
        ]);

        $this->createQuestion($dev, 'Iconia Garden Residence is thoughtfully designed to support which lifestyle?', [
            ['text' => 'Student accommodation', 'correct' => 0],
            ['text' => 'Multi-generational living', 'correct' => 1],
            ['text' => 'Short-term holiday stays', 'correct' => 0],
            ['text' => 'Industrial workforce housing', 'correct' => 0],
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | STATION 10 - TONGTOR DEVELOPMENT
    |--------------------------------------------------------------------------
    */

    private function tongTor()
    {
        $dev = Developer::where('name', 'Tong Tor')->first();

        $this->createQuestion($dev, 'Rosewood I is located in which township?', [
            ['text' => 'Taman Impian Emas', 'correct' => 0],
            ['text' => "Bandar Dato' Onn", 'correct' => 0],
            ['text' => 'Terra Heights @ Bukit Amber', 'correct' => 1],
            ['text' => 'Puteri Harbour', 'correct' => 0],
        ]);

        $this->createQuestion($dev, 'How far is Rosewood I from the North–South Expressway?', [
            ['text' => '5 minutes', 'correct' => 1],
            ['text' => '10 minutes', 'correct' => 0],
            ['text' => '15 minutes', 'correct' => 0],
            ['text' => '20 minutes', 'correct' => 0],
        ]);

        $this->createQuestion($dev, 'What exclusive recreational facility is available to residents of Rosewood II?', [
            ['text' => 'A private golf course', 'correct' => 0],
            ['text' => "A 1.5-acre private residents' park", 'correct' => 1],
            ['text' => 'A yacht marina', 'correct' => 0],
            ['text' => 'A theme park', 'correct' => 0],
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | STATION 11 - R&F PRINCESS COVE
    |--------------------------------------------------------------------------
    */

    private function rfPrincessCove()
    {
        $dev = Developer::where('name', 'R&F')->first();

        $this->createQuestion($dev, 'How is R&F Princess Cove Phase 3 connected to CIQ and the upcoming RTS?', [
            ['text' => 'Via an underground tunnel', 'correct' => 0],
            ['text' => 'Via a private monorail', 'correct' => 0],
            ['text' => 'Via a 650m covered link bridge', 'correct' => 1],
            ['text' => 'Via a ferry terminal', 'correct' => 0],
        ]);

        $this->createQuestion($dev, 'What is the estimated monthly instalment for R&F Princess Cove Phase 3?', [
            ['text' => 'SGD1,500 (approximately RM4,800*)', 'correct' => 0],
            ['text' => 'SGD1,000 (approximately RM3,200*)', 'correct' => 0],
            ['text' => 'SGD500 (approximately RM1,600*)', 'correct' => 0],
            ['text' => 'SGD700 (approximately RM2,200*)', 'correct' => 1],
        ]);

        $this->createQuestion($dev, 'R&F Princess Cove Phase 3 is ideal for which group of buyers?', [
            ['text' => 'Industrial business owners', 'correct' => 0],
            ['text' => 'Hotel operators', 'correct' => 0],
            ['text' => 'Second-home buyers and investors', 'correct' => 1],
            ['text' => 'University students', 'correct' => 0],
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | STATION 12 - KECK SENG GROUP
    |--------------------------------------------------------------------------
    */

    private function keckSeng()
    {
        $dev = Developer::where('name', 'Keck Seng')->first();

        $this->createQuestion($dev, 'What type of development is Daya 1 Residences?', [
            ['text' => 'Double-storey terrace homes', 'correct' => 0],
            ['text' => 'Serviced apartments', 'correct' => 1],
            ['text' => 'Semi-detached homes', 'correct' => 0],
            ['text' => 'Shop offices', 'correct' => 0],
        ]);

        $this->createQuestion($dev, 'TDC@CTIVE Lifestyle Square is ideal for which type of businesses?', [
            ['text' => 'Manufacturing factories only', 'correct' => 0],
            ['text' => 'Residential homeowners only', 'correct' => 0],
            ['text' => 'Warehousing and logistics companies only', 'correct' => 0],
            ['text' => 'Retailers, F&B outlets, and service businesses', 'correct' => 1],
        ]);

        $this->createQuestion($dev, 'Where is Ruby Hills III located?', [
            ['text' => 'Bandar Baru Kangkar Pulai (BBKP)', 'correct' => 1],
            ['text' => 'Taman Daya', 'correct' => 0],
            ['text' => 'Taman Daya', 'correct' => 0],
            ['text' => 'Iskandar Puteri', 'correct' => 0],
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | STATION 13 - MALTON
    |--------------------------------------------------------------------------
    */

    private function malton()
    {
        $dev = Developer::where('name', 'Malton')->first();

        $this->createQuestion($dev, 'When is River Park Bangsar South targeted for completion?', [
            ['text' => '2025', 'correct' => 0],
            ['text' => '2026', 'correct' => 1],
            ['text' => '2027', 'correct' => 0],
            ['text' => '2028', 'correct' => 0],
        ]);

        $this->createQuestion($dev, 'How far is Park Green from Pavilion Bukit Jalil?', [
            ['text' => '300 metres', 'correct' => 0],
            ['text' => '1 kilometre', 'correct' => 0],
            ['text' => '30 metres', 'correct' => 1],
            ['text' => '3 kilometres', 'correct' => 0],
        ]);

        $this->createQuestion($dev, 'Which major highways are easily accessible from Mutiara Kempas?', [
            ['text' => 'ELITE Highway and MEX Highway', 'correct' => 0],
            ['text' => 'East Coast Expressway and LPT2', 'correct' => 0],
            ['text' => 'SILK Highway and DUKE', 'correct' => 0],
            ['text' => 'North–South Expressway (NSE) and Eastern Dispersal Link (EDL)', 'correct' => 1],
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | STATION 14 - SUNWAY PROPERTY
    |--------------------------------------------------------------------------
    */

    private function sunway()
    {
        $dev = Developer::where('name', 'Sunway')->first();

        $this->createQuestion($dev, 'What unique design feature is offered in the Loft Plus units at Sunway LakeHills?', [
            ['text' => 'Private rooftop garden', 'correct' => 0],
            ['text' => '4.8m double-volume ceilings', 'correct' => 1],
            ['text' => 'Double garage', 'correct' => 0],
            ['text' => 'Smart home automation', 'correct' => 0],
        ]);

        $this->createQuestion($dev, 'What makes Sunway Sakura Residence 2 unique in Johor?', [
            ['text' => "It is Johor's first beachfront eco township", 'correct' => 0],
            ['text' => "It is Malaysia's first floating residential community", 'correct' => 0],
            ['text' => "It is Johor's first Modern Japanese Eco Homes with Japanese-inspired design", 'correct' => 1],
            ['text' => 'It is the first underground residential development', 'correct' => 0],
        ]);

        $this->createQuestion($dev, 'How far is Sunway Majestic from the upcoming RTS Station (MY–SG)?', [
            ['text' => '10km', 'correct' => 0],
            ['text' => '9km', 'correct' => 0],
            ['text' => '8km', 'correct' => 0],
            ['text' => '3km', 'correct' => 1],
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | STATION 15 - HORIZON HILLS
    |--------------------------------------------------------------------------
    */

    private function horizonHills()
    {
        $dev = Developer::where('name', 'Horizon Hills')->first();

        $this->createQuestion($dev, 'Pavilion 2 is located within which prestigious precinct of Horizon Hills?', [
            ['text' => 'The Grove', 'correct' => 0],
            ['text' => 'The Valley', 'correct' => 0],
            ['text' => 'The Peak', 'correct' => 1],
            ['text' => 'The Gardens', 'correct' => 0],
        ]);

        $this->createQuestion($dev, 'What type of homes are offered at Pavilion 2?', [
            ['text' => 'Serviced apartments', 'correct' => 0],
            ['text' => 'Double-storey terrace houses', 'correct' => 0],
            ['text' => 'Shop offices', 'correct' => 0],
            ['text' => 'Premium semi-detached homes', 'correct' => 1],
        ]);

        $this->createQuestion($dev, 'Which feature contributes to the peaceful environment at Pavilion 2?', [
            ['text' => 'Beachfront promenade', 'correct' => 0],
            ['text' => 'Golf course access', 'correct' => 0],
            ['text' => 'Pocket parks and peaceful cul-de-sacs', 'correct' => 1],
            ['text' => 'Marina boardwalk', 'correct' => 0],
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | STATION 16 - SUTERA
    |--------------------------------------------------------------------------
    */

    private function sutera()
    {
        $dev = Developer::where('name', 'Tanah Sutera')->first();

        $this->createQuestion($dev, 'What type of homes are offered at Sutera Garden Village (SGV)?', [
            ['text' => 'Spacious Semi-D homes', 'correct' => 1],
            ['text' => 'Serviced apartments', 'correct' => 0],
            ['text' => 'Double-storey terrace houses', 'correct' => 0],
            ['text' => 'Shop offices', 'correct' => 0],
        ]);

        $this->createQuestion($dev, 'Approximately how many lifestyle amenities are available at Sutera Garden Village?', [
            ['text' => 'Over 100', 'correct' => 0],
            ['text' => 'Over 80', 'correct' => 0],
            ['text' => 'Over 60', 'correct' => 0],
            ['text' => 'Over 30', 'correct' => 1],
        ]);

        $this->createQuestion($dev, 'What type of development is The Seed?', [
            ['text' => 'High-rise serviced apartment', 'correct' => 0],
            ['text' => 'Low-density townhouse community', 'correct' => 1],
            ['text' => 'Semi-detached housing estate', 'correct' => 0],
            ['text' => 'Commercial business park', 'correct' => 0],
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | STATION 17 - GENTING PROPERTY
    |--------------------------------------------------------------------------
    */

    private function genting()
    {
        $dev = Developer::where('name', 'Genting')->first();

        $this->createQuestion($dev, 'How many bedrooms and bathrooms does each Bayu Idaman home offer?', [
            ['text' => '4 bedrooms & 4 bathrooms', 'correct' => 1],
            ['text' => '3 bedrooms & 3 bathrooms', 'correct' => 0],
            ['text' => '5 bedrooms & 4 bathrooms', 'correct' => 0],
            ['text' => '4 bedrooms & 3 bathrooms', 'correct' => 0],
        ]);

        $this->createQuestion($dev, 'What outdoor feature is included with Bayu Idaman homes?', [
            ['text' => 'Rooftop garden', 'correct' => 0],
            ['text' => 'Private swimming pool', 'correct' => 0],
            ['text' => 'Golf putting green', 'correct' => 0],
            ['text' => 'Private back lane garden', 'correct' => 1],
        ]);

        $this->createQuestion($dev, 'What type of homes are offered at Bayu Idaman?', [
            ['text' => 'Semi-detached homes', 'correct' => 0],
            ['text' => 'Modern terrace homes', 'correct' => 1],
            ['text' => 'Serviced apartments', 'correct' => 0],
            ['text' => 'Bungalows', 'correct' => 0],
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | STATION 18 - KPRJ
    |--------------------------------------------------------------------------
    */

    private function kprj()
    {
        $dev = Developer::where('name', 'KPRJ')->first();

        $this->createQuestion($dev, 'Where is Jauhar Bayu Damai located?', [
            ['text' => "Bandar Dato' Onn, Johor Bahru", 'correct' => 0],
            ['text' => 'Taman Bayu Damai, Pengerang', 'correct' => 1],
            ['text' => 'Iskandar Puteri', 'correct' => 0],
            ['text' => 'Kulai', 'correct' => 0],
        ]);

        $this->createQuestion($dev, 'Which of the following best describes Jauhar Bayu Damai?', [
            ['text' => 'A luxury beachfront condominium', 'correct' => 0],
            ['text' => 'A commercial office development', 'correct' => 0],
            ['text' => 'An industrial mixed-use township', 'correct' => 0],
            ['text' => 'A modern residential development offering comfortable family living in a peaceful community', 'correct' => 1],
        ]);

        $this->createQuestion($dev, 'What is one of the location advantages of Jauhar Bayu Damai?', [
            ['text' => 'Direct access to an international airport', 'correct' => 0],
            ['text' => 'Easy access to essential amenities and nearby townships', 'correct' => 1],
            ['text' => 'Walking distance to the RTS Station', 'correct' => 0],
            ['text' => 'Private marina access', 'correct' => 0],
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | STATION 19 - WEBEST GROUP
    |--------------------------------------------------------------------------
    */

    private function webest()
    {
        $dev = Developer::where('name', 'Webest')->first();

        $this->createQuestion($dev, 'How is Southbay best described?', [
            ['text' => 'A luxury beachfront resort', 'correct' => 0],
            ['text' => 'A premier waterfront mixed-use development', 'correct' => 1],
            ['text' => 'A high-rise residential township', 'correct' => 0],
            ['text' => 'An industrial business park', 'correct' => 0],
        ]);

        $this->createQuestion($dev, "Which two values are at the heart of Southbay's development?", [
            ['text' => 'Manufacturing and logistics', 'correct' => 0],
            ['text' => 'Retail and entertainment only', 'correct' => 0],
            ['text' => 'Tourism and agriculture', 'correct' => 0],
            ['text' => 'Wellness and community engagement', 'correct' => 1],
        ]);

        $this->createQuestion($dev, 'Where is 29 Reserve located?', [
            ['text' => 'Ayer Keroh, Melaka', 'correct' => 0],
            ['text' => 'Kota Syahbandar, Melaka', 'correct' => 1],
            ['text' => 'Bukit Beruang, Melaka', 'correct' => 0],
            ['text' => 'Alor Gajah, Melaka', 'correct' => 0],
        ]);
    }
}
