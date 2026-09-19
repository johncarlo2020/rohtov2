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
        $this->mahSing();
        $this->pp();
        $this->wct();
        $this->malton();
        $this->rfPrincessCove();
        $this->pgb();
        $this->tropicana();
        $this->tslaw();
        $this->maxim();
        $this->spb();
        $this->solusiKelana();
    }

    private function createQuestion($developer, $questionText, $answers)
    {
        if (!$developer) {
            return;
        }

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
        $dev = Developer::find(1) ?? Developer::where('name', 'JLG')->first();

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
        $dev = Developer::find(4) ?? Developer::where('name', 'WCT')->first();

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
        $dev = Developer::find(7) ?? Developer::where('name', 'PGB')->first();

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
        $dev = Developer::find(2) ?? Developer::whereIn('name', ['MahSing', 'Mah Sing', 'Mahsing'])->first();

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
        $dev = Developer::find(9) ?? Developer::whereIn('name', ['TSLaw', 'TSLAW', 'TSLAW Land', 'tslaw land'])->first();

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
    | STATION 8 - MAXIM
    |--------------------------------------------------------------------------
    */

    private function maxim()
    {
        $dev = Developer::find(10) ?? Developer::whereIn('name', ['Maxim', 'maxim'])->first();

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
    | STATION 11 - R&F PRINCESS COVE
    |--------------------------------------------------------------------------
    */

    private function rfPrincessCove()
    {
        $dev = Developer::find(6) ?? Developer::whereIn('name', ['R&F', 'R&F Princess Cove', 'R&F princess Cove'])->first();

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
    | STATION 13 - MALTON
    |--------------------------------------------------------------------------
    */

    private function malton()
    {
        $dev = Developer::find(5) ?? Developer::whereIn('name', ['Malton', 'malton'])->first();

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
    | STATION 4 - SPB DEVELOPMENT
    |--------------------------------------------------------------------------
    */

    private function spb()
    {
        $dev = Developer::find(11) ?? Developer::whereIn('name', ['SPB', 'SPB Development', 'spb'])->first();

        $this->createQuestion($dev, 'How far is Taman Akasia from the Ayer Hitam Toll?', [
            ['text' => '5 minutes', 'correct' => 0],
            ['text' => '10 minutes', 'correct' => 1],
            ['text' => '20 minutes', 'correct' => 0],
            ['text' => '30 minutes', 'correct' => 0],
        ]);

        $this->createQuestion($dev, 'Where is Taman Nuri located?', [
            ['text' => 'Ayer Keroh, Melaka', 'correct' => 0],
            ['text' => 'Kota Syahbandar, Melaka', 'correct' => 0],
            ['text' => 'Alor Gajah, Melaka', 'correct' => 0],
            ['text' => 'Durian Tunggal, Melaka', 'correct' => 1],
        ]);

        $this->createQuestion($dev, 'What unique feature provides residents of Taman Nuri with direct access to greenery?', [
            ['text' => 'Green backlane access', 'correct' => 1],
            ['text' => 'Rooftop garden', 'correct' => 0],
            ['text' => 'Private park', 'correct' => 0],
            ['text' => 'Waterfront promenade', 'correct' => 0],
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | SOLUSI KELANA
    |--------------------------------------------------------------------------
    */

    private function solusiKelana()
    {
        $dev = Developer::find(12) ?? Developer::whereIn('name', ['Solusi Kelana', 'solusi kelana'])->first();

        $this->createQuestion($dev, 'What is the tenure of Residensi Sinaran @ JB City Centre?', [
            ['text' => '99-year leasehold', 'correct' => 0],
            ['text' => '60-year leasehold', 'correct' => 0],
            ['text' => 'Commercial title', 'correct' => 0],
            ['text' => 'Freehold', 'correct' => 1],
        ]);

        $this->createQuestion($dev, 'How close are medical facilities to Residensi Sinaran?', [
            ['text' => 'Within 10–15km', 'correct' => 0],
            ['text' => 'More than 20km', 'correct' => 0],
            ['text' => 'Within 1–3km', 'correct' => 1],
            ['text' => 'Within 5–8km', 'correct' => 0],
        ]);

        $this->createQuestion($dev, 'Which intercity train route is highlighted as a public transport advantage of Residensi Sinaran?', [
            ['text' => 'CIQ to Woodlands', 'correct' => 1],
            ['text' => 'JB to Senai Airport', 'correct' => 0],
            ['text' => 'JB to Kulai', 'correct' => 0],
            ['text' => 'JB to Iskandar Puteri', 'correct' => 0],
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | TROPICANA
    |--------------------------------------------------------------------------
    */

    private function tropicana()
    {
        $dev = Developer::find(8) ?? Developer::whereIn('name', ['Tropicana', 'TROPICANA', 'tropicana'])->first();

        $this->createQuestion($dev, 'What is the tenure of Skypark Kepler @ Lido Waterfront Boulevard?', [
            ['text' => '99-year leasehold', 'correct' => 0],
            ['text' => 'Freehold', 'correct' => 1],
            ['text' => '60-year leasehold', 'correct' => 0],
            ['text' => 'Malay Reserve', 'correct' => 0],
        ]);

        $this->createQuestion($dev, 'How many curated lifestyle facilities are available at Skypark Kepler @ Lido Waterfront Boulevard?', [
            ['text' => '43', 'correct' => 1],
            ['text' => '25', 'correct' => 0],
            ['text' => '35', 'correct' => 0],
            ['text' => '60', 'correct' => 0],
        ]);

        $this->createQuestion($dev, 'What major green space complements the waterfront lifestyle at Skypark Kepler @ Lido Waterfront Boulevard?', [
            ['text' => 'A 10-acre botanical garden', 'correct' => 0],
            ['text' => 'A 20-acre private golf course', 'correct' => 0],
            ['text' => 'A 32-acre central green park', 'correct' => 1],
            ['text' => 'A 50-acre rainforest reserve', 'correct' => 0],
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | PP (PREMIER PLUS)
    |--------------------------------------------------------------------------
    */

    private function pp()
    {
        $dev = Developer::find(3) ?? Developer::whereIn('name', ['PP', 'Premier Plus', 'PREMIER PLUS'])->first();

        $this->createQuestion($dev, 'Where is Senyum Residences located?', [
            ['text' => 'Jalan Wadi Hana', 'correct' => 1],
            ['text' => 'Bandar Cemerlang', 'correct' => 0],
            ['text' => 'Iskandar Puteri', 'correct' => 0],
            ['text' => 'Skudai', 'correct' => 0],
        ]);
    }
}
