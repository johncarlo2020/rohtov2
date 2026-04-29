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
        $this->jrk();
        $this->mTerra();
        $this->mAspira();
        $this->landGeneral();
        $this->malton();
        $this->windsor();
        $this->selangorDredging();
        $this->lbs();
        $this->matrix();
        $this->saujana();
        $this->eo();
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

    private function jrk()
    {
        $dev = Developer::where('name', 'JRK Group')->first();

        $this->createQuestion($dev,'Which of the following best describes JRK Celestia in Puchong?',[
            ['text'=>'Industrial warehouse development','correct'=>0],
            ['text'=>'Agricultural land project','correct'=>0],
            ['text'=>'High-rise condominium for modern living','correct'=>1],
            ['text'=>'Landed gated housing','correct'=>0],
        ]);

        $this->createQuestion($dev,'Which of the following is a key feature of JRK Celestia?',[
            ['text'=>'Beachfront sea view living','correct'=>0],
            ['text'=>'Gated & guarded with security surveillance','correct'=>1],
            ['text'=>'Factory production facilities','correct'=>0],
            ['text'=>'No lifestyle amenities','correct'=>0],
        ]);

        $this->createQuestion($dev,'Which award did JRK Group receive at the PropertyGuru Asia Awards Malaysia 2025?',[
            ['text'=>'Best Luxury Condo Development','correct'=>0],
            ['text'=>'Best Township Development','correct'=>0],
            ['text'=>'Best Commercial Project','correct'=>0],
            ['text'=>'Rising Star Award','correct'=>1],
        ]);
    }

    private function mTerra()
    {
        $dev = Developer::where('name', 'Mah Sing Group Berhad M Terra')->first();

        $this->createQuestion($dev,'Which of the following best describes M Terra, Puchong?',[
            ['text'=>'Industrial warehouse development','correct'=>0],
            ['text'=>'Luxury beachfront resort','correct'=>0],
            ['text'=>'Affordable lakeside high-rise living','correct'=>1],
            ['text'=>'Agricultural land investment','correct'=>0],
        ]);

        $this->createQuestion($dev,'Which of the following is a key feature of M Terra?',[
            ['text'=>'Located 5km away from nearest public transport','correct'=>0],
            ['text'=>'No nearby amenities','correct'=>0],
            ['text'=>'Approximately 500m to Puchong Perdana LRT Station','correct'=>1],
            ['text'=>'Landed bungalow development','correct'=>0],
        ]);

        $this->createQuestion($dev,'Which award has M Terra / its developer received?',[
            ['text'=>'Best Commercial Tower Development','correct'=>0],
            ['text'=>'Best Township Masterplan','correct'=>0],
            ['text'=>'Best Luxury Landed Development','correct'=>0],
            ['text'=>'Best Value for Money High-Rise Development','correct'=>1],
        ]);
    }

    private function mAspira()
    {
        $dev = Developer::where('name', 'Mah Sing Group Berhad M Aspira')->first();

        $this->createQuestion($dev,'Where is M Aspira located?',[
            ['text'=>'Johor Bahru','correct'=>0],
            ['text'=>'Penang','correct'=>0],
            ['text'=>'Taman Desa, Kuala Lumpur','correct'=>1],
            ['text'=>'Cyberjaya','correct'=>0],
        ]);

        $this->createQuestion($dev,'Which of the following is a key feature of M Aspira?',[
            ['text'=>'Beachfront sea view','correct'=>0],
            ['text'=>'FREE shuttle service to Kuchai MRT Station','correct'=>1],
            ['text'=>'No nearby public transport','correct'=>0],
            ['text'=>'Industrial warehouse concept','correct'=>0],
        ]);

        $this->createQuestion($dev,'Which award has the developer received in 2025?',[
            ['text'=>'Best Luxury Condo Development','correct'=>0],
            ['text'=>'Best Township Development','correct'=>0],
            ['text'=>'People’s Choice Award – Top 10 Developer of the Year','correct'=>1],
            ['text'=>'Best Commercial Project','correct'=>0],
        ]);
    }

    private function landGeneral()
    {
        $dev = Developer::where('name', 'Land & General Berhad')->first();

        $this->createQuestion($dev,'Which of the following best describes The WYN Residences?',[
            ['text'=>'Industrial warehouse development','correct'=>0],
            ['text'=>'Agricultural land project','correct'=>0],
            ['text'=>'Luxury beachfront resort','correct'=>0],
            ['text'=>'Move-in ready high-rise home in a prime Puchong location','correct'=>1],
        ]);

        $this->createQuestion($dev,'Which of the following is a key feature of The WYN Residences?',[
            ['text'=>'Bare unit without furnishings','correct'=>0],
            ['text'=>'Located far from public transport','correct'=>0],
            ['text'=>'Fully furnished and ready to move in','correct'=>1],
            ['text'=>'Only studio units available','correct'=>0],
        ]);

        $this->createQuestion($dev,'Which of the following is NOT a benefit offered by The WYN Residences?',[
            ['text'=>'Zero downpayment scheme','correct'=>0],
            ['text'=>'MOT subsidy','correct'=>0],
            ['text'=>'Near LRT Puchong Jaya','correct'=>0],
            ['text'=>'Freehold landed bungalow','correct'=>1],
        ]);
    }

    private function malton()
    {
        $dev = Developer::where('name', 'Malton Berhad')->first();

        $this->createQuestion($dev,'What is the key highlight of Park Green Pavilion?',[
            ['text'=>'5km away from shopping mall','correct'=>0],
            ['text'=>'30 meters to Pavilion Bukit Jalil','correct'=>1],
            ['text'=>'Located in rural area','correct'=>0],
            ['text'=>'Industrial development','correct'=>0],
        ]);

        $this->createQuestion($dev,'Which of the following is a key feature of Mutiara Lake Puchong?',[
            ['text'=>'High-density 2,000 units','correct'=>0],
            ['text'=>'No facilities','correct'=>0],
            ['text'=>'Low-density with only 526 units','correct'=>1],
            ['text'=>'Commercial office tower','correct'=>0],
        ]);

        $this->createQuestion($dev,'What is the expected completion timeline for River Park?',[
            ['text'=>'2024','correct'=>0],
            ['text'=>'2025','correct'=>0],
            ['text'=>'2026','correct'=>1],
            ['text'=>'2030','correct'=>0],
        ]);
    }

    private function windsor()
    {
        $dev = Developer::where('name', 'Windsor Land')->first();

        $this->createQuestion($dev,'Which of the following is a key feature of ALAIA @ Titiwangsa?',[
            ['text'=>'No facilities provided','correct'=>0],
            ['text'=>'Sky facilities with KL skyline views','correct'=>1],
            ['text'=>'Located in rural area','correct'=>0],
            ['text'=>'Factory production space','correct'=>0],
        ]);

        $this->createQuestion($dev,'Which of the following best describes Windsor Villa @ Cyberjaya?',[
            ['text'=>'High-rise serviced apartment','correct'=>0],
            ['text'=>'Studio-only units','correct'=>0],
            ['text'=>'Freehold 3-storey link villas','correct'=>1],
            ['text'=>'Commercial office tower','correct'=>0],
        ]);

        $this->createQuestion($dev,'Which project features sky facilities with KL skyline views?',[
            ['text'=>'Windsor Villa @ Cyberjaya','correct'=>0],
            ['text'=>'Both projects','correct'=>0],
            ['text'=>'ALAIA @ Titiwangsa','correct'=>1],
            ['text'=>'None of the above','correct'=>0],
        ]);
    }

    private function selangorDredging()
    {
        $dev = Developer::where('name', 'Selangor Dredging Berhad')->first();

        $this->createQuestion($dev,'Which project is known for lakeside resort-style living?',[
            ['text'=>'DaMai Residence','correct'=>0],
            ['text'=>'Both projects','correct'=>0],
            ['text'=>'Elina Senai','correct'=>1],
            ['text'=>'None of the above','correct'=>0],
        ]);

        $this->createQuestion($dev,'Which of the following is a key feature of DaMai?',[
            ['text'=>'Located in Johor','correct'=>0],
            ['text'=>'Leasehold development','correct'=>0],
            ['text'=>'Freehold luxury high-rise in Taman Melawati','correct'=>1],
            ['text'=>'Industrial warehouse concept','correct'=>0],
        ]);

        $this->createQuestion($dev,'Which of the following best describes Elina Senai?',[
            ['text'=>'High-rise luxury in KL city centre','correct'=>0],
            ['text'=>'Industrial commercial development','correct'=>0],
            ['text'=>'Resort-inspired residential in Puchong','correct'=>1],
            ['text'=>'Office tower','correct'=>0],
        ]);
    }

    private function lbs()
    {
        $dev = Developer::where('name', 'LBS Bina Group Berhad')->first();

        $this->createQuestion($dev,'What is the starting price of KITA Sejati @ KITA Cybersouth?',[
            ['text'=>'RM299,800','correct'=>0],
            ['text'=>'RM399,800','correct'=>1],
            ['text'=>'RM499,800','correct'=>0],
            ['text'=>'RM599,800','correct'=>0],
        ]);

        $this->createQuestion($dev,'Which of the following best describes AULICA, Puchong?',[
            ['text'=>'High-rise serviced apartment','correct'=>0],
            ['text'=>'Industrial warehouse development','correct'=>0],
            ['text'=>'Double-storey terrace house','correct'=>1],
            ['text'=>'Agricultural land project','correct'=>0],
        ]);

        $this->createQuestion($dev,'Which of the following is a key feature of KITA Sejati?',[
            ['text'=>'Located in rural area with no access','correct'=>0],
            ['text'=>'Fully furnished and move-in ready','correct'=>1],
            ['text'=>'Beachfront living','correct'=>0],
            ['text'=>'Office tower development','correct'=>0],
        ]);
    }

    private function matrix()
    {
        $dev = Developer::where('name', 'Matrix Concepts Holdings Berhad')->first();

        $this->createQuestion($dev,'Where is Levia Residence located?',[
            ['text'=>'Johor Bahru','correct'=>0],
            ['text'=>'Persiaran Wawasan, Puchong','correct'=>1],
            ['text'=>'Cyberjaya','correct'=>0],
            ['text'=>'Penang','correct'=>0],
        ]);

        $this->createQuestion($dev,'Which of the following is a key feature of Levia Residence?',[
            ['text'=>'Located far from public transport','correct'=>0],
            ['text'=>'Walking distance / near to LRT Pusat Bandar Puchong','correct'=>1],
            ['text'=>'Beachfront living','correct'=>0],
            ['text'=>'Industrial warehouse concept','correct'=>0],
        ]);

        $this->createQuestion($dev,'Which of the following best describes Levia Residence?',[
            ['text'=>'Landed bungalow development','correct'=>0],
            ['text'=>'Agricultural land project','correct'=>0],
            ['text'=>'Modern high-rise residential living','correct'=>1],
            ['text'=>'Commercial office tower','correct'=>0],
        ]);
    }

    private function saujana()
    {
        $dev = Developer::where('name', 'Saujana Development Sdn Bhd')->first();

        $this->createQuestion($dev,'Which of the following is a key feature of Residensi Rimbun Saujana?',[
            ['text'=>'Leasehold development','correct'=>0],
            ['text'=>'Freehold low-density living','correct'=>1],
            ['text'=>'Industrial warehouse concept','correct'=>0],
            ['text'=>'Commercial office tower','correct'=>0],
        ]);

        $this->createQuestion($dev,'Which of the following unit features is available?',[
            ['text'=>'Studio-only units','correct'=>0],
            ['text'=>'No outdoor space','correct'=>0],
            ['text'=>'Balcony or lanai layouts','correct'=>1],
            ['text'=>'Office suites','correct'=>0],
        ]);

        $this->createQuestion($dev,'Which of the following is an exclusive privilege offered?',[
            ['text'=>'Free car for every purchase','correct'=>0],
            ['text'=>'7% Bumiputera discount','correct'=>1],
            ['text'=>'Free international travel package','correct'=>0],
            ['text'=>'Lifetime zero maintenance fees','correct'=>0],
        ]);
    }

    private function eo()
    {
        $dev = Developer::where('name', 'Eastern & Oriental Berhad')->first();

        $this->createQuestion($dev,'Which project is located within a waterfront township in Andaman Island, Penang?',[
            ['text'=>'Avéa @ Andaman Island','correct'=>0],
            ['text'=>'Maris @ Andaman Island','correct'=>0],
            ['text'=>'The Lume @ Andaman Island','correct'=>0],
            ['text'=>'All of the above','correct'=>1],
        ]);

        $this->createQuestion($dev,'Which project offers fully furnished homes?',[
            ['text'=>'Avéa @ Andaman Island','correct'=>0],
            ['text'=>'Maris @ Andaman Island','correct'=>1],
            ['text'=>'The Lume @ Andaman Island','correct'=>0],
            ['text'=>'None of the above','correct'=>0],
        ]);

        $this->createQuestion($dev,'Which project is move-in ready?',[
            ['text'=>'Avéa @ Andaman Island','correct'=>1],
            ['text'=>'Maris @ Andaman Island','correct'=>0],
            ['text'=>'The Lume @ Andaman Island','correct'=>0],
            ['text'=>'None of the above','correct'=>0],
        ]);
    }
}
