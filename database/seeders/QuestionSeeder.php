<?php

namespace Database\Seeders;

use App\Models\Answer;
use App\Models\Developer;
use App\Models\Question;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QuestionSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Answer::truncate();
        Question::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->malton();
        $this->paramount();
        $this->kerjayaProspek();
        $this->pdcProperties();
        $this->mahSing();
        $this->jayamas();
        $this->airmas();
        $this->skyworld();
        $this->gsd();
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
    | DEV 1 - Malton Berhad (Booth : Q2)
    |--------------------------------------------------------------------------
    */
    private function malton()
    {
        $dev = Developer::where('name', 'like', '%Malton%')->orWhere('id', 1)->first();

        $this->createQuestion($dev, 'Where is River Park Bangsar South located?', [
            ['text' => 'Jalan Ampang, Kuala Lumpur', 'correct' => 0],
            ['text' => 'Jalan Kampung Pasir, Kuala Lumpur', 'correct' => 1],
            ['text' => 'Jalan Kuching, Kuala Lumpur', 'correct' => 0],
            ['text' => 'Jalan Tun Razak, Kuala Lumpur', 'correct' => 0],
        ]);

        $this->createQuestion($dev, 'Which KTM station is located approximately 300m from River Park Bangsar South?', [
            ['text' => 'Mid Valley KTM Station', 'correct' => 0],
            ['text' => 'Seputeh KTM Station', 'correct' => 0],
            ['text' => 'Angkasapuri KTM Station', 'correct' => 1],
            ['text' => 'KL Sentral KTM Station', 'correct' => 0],
        ]);

        $this->createQuestion($dev, 'What is the built-up size range of River Park Bangsar South units?', [
            ['text' => '700 – 750 sq. ft.', 'correct' => 0],
            ['text' => '812 – 861 sq. ft.', 'correct' => 1],
            ['text' => '900 – 950 sq. ft.', 'correct' => 0],
            ['text' => '1,000 – 1,100 sq. ft.', 'correct' => 0],
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | DEV 2 - Paramount Property (Booth : Q3A)
    |--------------------------------------------------------------------------
    */
    private function paramount()
    {
        $dev = Developer::where('name', 'like', '%Paramount%')->orWhere('id', 2)->first();

        $this->createQuestion($dev, 'Where is Seiras Residences located?', [
            ['text' => 'Bukit Mertajam, Penang', 'correct' => 0],
            ['text' => 'Batu Kawan, Penang', 'correct' => 1],
            ['text' => 'Bayan Lepas, Penang', 'correct' => 0],
            ['text' => 'Butterworth, Penang', 'correct' => 0],
        ]);

        $this->createQuestion($dev, 'What type of homes are offered at Embun Hills?', [
            ['text' => '2-storey landed homes', 'correct' => 1],
            ['text' => '3-storey terrace homes', 'correct' => 0],
            ['text' => 'Serviced apartments', 'correct' => 0],
            ['text' => 'Semi-detached homes', 'correct' => 0],
        ]);

        $this->createQuestion($dev, 'How many storeys does Seiras Residences consist of?', [
            ['text' => '33 storeys', 'correct' => 0],
            ['text' => '38 storeys', 'correct' => 0],
            ['text' => '43 storeys', 'correct' => 1],
            ['text' => '48 storeys', 'correct' => 0],
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | DEV 3 - Kerjaya Prospek (Booth : Q4)
    |--------------------------------------------------------------------------
    */
    private function kerjayaProspek()
    {
        $dev = Developer::where('name', 'like', '%Kerjaya%')->orWhere('id', 3)->first();

        $this->createQuestion($dev, 'Where is AndaBreeze Residences located?', [
            ['text' => 'Bayan Lepas, Penang', 'correct' => 0],
            ['text' => 'Batu Kawan, Penang', 'correct' => 1],
            ['text' => 'Georgetown, Penang', 'correct' => 0],
            ['text' => 'Butterworth, Penang', 'correct' => 0],
        ]);

        $this->createQuestion($dev, 'What type of ownership does AndaBreeze Residences offer?', [
            ['text' => 'Leasehold', 'correct' => 0],
            ['text' => 'Freehold', 'correct' => 1],
            ['text' => 'Commercial title', 'correct' => 0],
            ['text' => 'Industrial title', 'correct' => 0],
        ]);

        $this->createQuestion($dev, 'Approximately how many facilities are available at AndaBreeze Residences?', [
            ['text' => '30+ facilities', 'correct' => 0],
            ['text' => '50+ facilities', 'correct' => 0],
            ['text' => '60+ facilities', 'correct' => 0],
            ['text' => '80+ facilities', 'correct' => 1],
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | DEV 4 - PDC Properties (Booth : Q5)
    |--------------------------------------------------------------------------
    */
    private function pdcProperties()
    {
        $dev = Developer::where('name', 'like', '%PDC%')->orWhere('id', 4)->first();

        $this->createQuestion($dev, 'Where is Taman Cassia Cempaka Fasa 2 & Fasa 3 located?', [
            ['text' => 'Bertam, Kepala Batas', 'correct' => 0],
            ['text' => 'Butterworth', 'correct' => 0],
            ['text' => 'Teluk Kumbar, Bayan Lepas', 'correct' => 0],
            ['text' => 'Bandar Cassia, Batu Kawan', 'correct' => 1],
        ]);

        $this->createQuestion($dev, 'Where is Taman Damai Lestari located?', [
            ['text' => 'Bertam, Kepala Batas', 'correct' => 1],
            ['text' => 'Batu Kawan', 'correct' => 0],
            ['text' => 'Bayan Lepas', 'correct' => 0],
            ['text' => 'Butterworth', 'correct' => 0],
        ]);

        $this->createQuestion($dev, 'Which development is located in Jalan M Kuppan, Kampung Jawa, Butterworth?', [
            ['text' => 'Dua Residensi', 'correct' => 0],
            ['text' => 'Jiran Residensi', 'correct' => 1],
            ['text' => 'Taman Cassia Cempaka', 'correct' => 0],
            ['text' => 'Taman Damai Lestari', 'correct' => 0],
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | DEV 5 - Mah Sing Group (Booth : Q6)
    |--------------------------------------------------------------------------
    */
    private function mahSing()
    {
        $dev = Developer::where('name', 'like', '%Mah Sing%')->orWhere('id', 5)->first();

        $this->createQuestion($dev, 'What type of residence is M Amaya?', [
            ['text' => 'Condominium', 'correct' => 0],
            ['text' => 'Semi-detached homes', 'correct' => 0],
            ['text' => 'Landed terrace homes', 'correct' => 0],
            ['text' => 'Serviced residence', 'correct' => 1],
        ]);

        $this->createQuestion($dev, 'Where is M Amaya by Mah Sing located?', [
            ['text' => 'Georgetown, Penang', 'correct' => 0],
            ['text' => 'Batu Kawan, Penang', 'correct' => 0],
            ['text' => 'Bayan Lepas, Penang', 'correct' => 1],
            ['text' => 'Bukit Mertajam, Penang', 'correct' => 0],
        ]);

        $this->createQuestion($dev, 'Which airport is conveniently located to M Amaya?', [
            ['text' => 'Penang International Airport', 'correct' => 1],
            ['text' => 'Langkawi International Airport', 'correct' => 0],
            ['text' => 'Sultan Abdul Halim Airport', 'correct' => 0],
            ['text' => 'Kuala Lumpur International Airport', 'correct' => 0],
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | DEV 6 - Jayamas Property Group (Booth : Q7)
    |--------------------------------------------------------------------------
    */
    private function jayamas()
    {
        $dev = Developer::where('name', 'like', '%Jayamas%')->orWhere('id', 6)->first();

        $this->createQuestion($dev, 'Where is Ventus & Tradesmen located?', [
            ['text' => 'Bayan Lepas, Penang', 'correct' => 0],
            ['text' => 'Batu Maung, Penang', 'correct' => 1],
            ['text' => 'Batu Kawan, Penang', 'correct' => 0],
            ['text' => 'Georgetown, Penang', 'correct' => 0],
        ]);

        $this->createQuestion($dev, 'Which major industrial hub is located near Ventus & Tradesmen?', [
            ['text' => 'Batu Kawan Industrial Park', 'correct' => 0],
            ['text' => 'Penang Science Park', 'correct' => 0],
            ['text' => 'Bayan Lepas Free Industrial Zone (FIZ)', 'correct' => 1],
            ['text' => 'Prai Industrial Estate', 'correct' => 0],
        ]);

        $this->createQuestion($dev, 'Which airport is conveniently accessible from Ventus & Tradesmen?', [
            ['text' => 'Sultan Abdul Halim Airport', 'correct' => 0],
            ['text' => 'Penang International Airport', 'correct' => 1],
            ['text' => 'Kuala Lumpur International Airport', 'correct' => 0],
            ['text' => 'Langkawi International Airport', 'correct' => 0],
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | DEV 7 - Airmas Group of Companies (Booth : Q8)
    |--------------------------------------------------------------------------
    */
    private function airmas()
    {
        $dev = Developer::where('name', 'like', '%Airmas%')->orWhere('id', 7)->first();

        $this->createQuestion($dev, 'Where is Alton Skyvillas located?', [
            ['text' => 'Butterworth, Penang', 'correct' => 0],
            ['text' => 'Bukit Mertajam, Penang', 'correct' => 0],
            ['text' => 'Jelutong, Penang', 'correct' => 1],
            ['text' => 'Permatang Pauh, Penang', 'correct' => 0],
        ]);

        $this->createQuestion($dev, 'What type of development is Taman Tan Sai Gin?', [
            ['text' => 'Double-storey terrace homes', 'correct' => 1],
            ['text' => 'Double-storey bungalows', 'correct' => 0],
            ['text' => 'Semi-detached homes', 'correct' => 0],
            ['text' => 'Serviced apartments', 'correct' => 0],
        ]);

        $this->createQuestion($dev, 'Where is Silkspring Residence located?', [
            ['text' => 'Jelutong, Penang', 'correct' => 0],
            ['text' => 'Jalan Telaga Air, Butterworth', 'correct' => 1],
            ['text' => 'Permatang Pauh, Penang', 'correct' => 0],
            ['text' => 'Bukit Mertajam, Penang', 'correct' => 0],
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | DEV 8 - SkyWorld (Booth : Q10)
    |--------------------------------------------------------------------------
    */
    private function skyworld()
    {
        $dev = Developer::where('name', 'like', '%SkyWorld%')->orWhere('id', 8)->first();

        $this->createQuestion($dev, 'Where is SkyWorld Cassia located?', [
            ['text' => 'Seberang Jaya, Penang', 'correct' => 0],
            ['text' => 'Batu Kawan, Penang', 'correct' => 1],
            ['text' => 'Bayan Lepas, Penang', 'correct' => 0],
            ['text' => 'Butterworth, Penang', 'correct' => 0],
        ]);

        $this->createQuestion($dev, 'What is the starting price of SkyWorld Cassia?', [
            ['text' => 'From RM188k', 'correct' => 0],
            ['text' => 'From RM238k', 'correct' => 0],
            ['text' => 'From RM288k', 'correct' => 1],
            ['text' => 'From RM338k', 'correct' => 0],
        ]);

        $this->createQuestion($dev, 'Where is SkyWorld Pearlmont located?', [
            ['text' => 'Batu Kawan, Penang', 'correct' => 0],
            ['text' => 'Bayan Lepas, Penang', 'correct' => 0],
            ['text' => 'Seberang Jaya, Penang', 'correct' => 1],
            ['text' => 'Georgetown, Penang', 'correct' => 0],
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | DEV 9 - GSD Group (Booth : Q11)
    |--------------------------------------------------------------------------
    */
    private function gsd()
    {
        $dev = Developer::where('name', 'like', '%GSD%')->orWhere('id', 9)->first();

        $this->createQuestion($dev, "Where is G'Vinton located?", [
            ['text' => 'Jelutong, Penang', 'correct' => 0],
            ['text' => 'Bayan Lepas, Penang', 'correct' => 0],
            ['text' => 'Georgetown, Penang', 'correct' => 1],
            ['text' => 'Butterworth, Penang', 'correct' => 0],
        ]);

        $this->createQuestion($dev, "What is the starting built-up size of D'Tiara Residence?", [
            ['text' => 'From 500 sq. ft.', 'correct' => 0],
            ['text' => 'From 600 sq. ft.', 'correct' => 0],
            ['text' => 'From 700 sq. ft.', 'correct' => 1],
            ['text' => 'From 800 sq. ft.', 'correct' => 0],
        ]);

        $this->createQuestion($dev, "Where is D'Tiara Residence located?", [
            ['text' => 'Bayan Lepas, Penang', 'correct' => 1],
            ['text' => 'Georgetown, Penang', 'correct' => 0],
            ['text' => 'Jelutong, Penang', 'correct' => 0],
            ['text' => 'Butterworth, Penang', 'correct' => 0],
        ]);
    }
}
