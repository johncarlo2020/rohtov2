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

        $this->lg();
        $this->paramount();
        $this->gamudaLand();
        $this->tropicanaAlam();
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
    | DEV 1 - L&G
    |--------------------------------------------------------------------------
    */
    private function lg()
    {
        $dev = Developer::where('name', 'L&G')->orWhere('id', 1)->first();

        $this->createQuestion($dev, 'Where is The WYN Residences located?', [
            ['text' => 'Puchong Jaya', 'correct' => 1],
            ['text' => 'Petaling Jaya', 'correct' => 0],
            ['text' => 'Cyberjaya', 'correct' => 0],
            ['text' => 'Bandar Klang', 'correct' => 0],
        ]);

        $this->createQuestion($dev, 'Which LRT station is associated with The WYN Residences?', [
            ['text' => 'Asia Jaya LRT', 'correct' => 0],
            ['text' => 'Kelana Jaya LRT', 'correct' => 0],
            ['text' => 'Puchong Jaya LRT', 'correct' => 1],
            ['text' => 'Subang Jaya LRT', 'correct' => 0],
        ]);

        $this->createQuestion($dev, 'How many storeys of residence units does each block of The Wyn Residences consists of?', [
            ['text' => '22', 'correct' => 0],
            ['text' => '28', 'correct' => 0],
            ['text' => '35', 'correct' => 0],
            ['text' => '44', 'correct' => 1],
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | DEV 2 - Paramount
    |--------------------------------------------------------------------------
    */
    private function paramount()
    {
        $dev = Developer::where('name', 'Paramount')->orWhere('id', 2)->first();

        $this->createQuestion($dev, 'What type of property is Uptown Residences 2?', [
            ['text' => 'Commercial shop lot', 'correct' => 0],
            ['text' => 'Serviced apartment', 'correct' => 1],
            ['text' => 'Semi-detached house', 'correct' => 0],
            ['text' => 'Condominium', 'correct' => 0],
        ]);

        $this->createQuestion($dev, 'Where is Sejati Residences located?', [
            ['text' => 'Cyberjaya', 'correct' => 1],
            ['text' => 'Putrajaya', 'correct' => 0],
            ['text' => 'Shah Alam', 'correct' => 0],
            ['text' => 'Bandar Klang', 'correct' => 0],
        ]);

        $this->createQuestion($dev, 'How many lifestyle facilities does The Atera offer?', [
            ['text' => '41', 'correct' => 0],
            ['text' => '51', 'correct' => 0],
            ['text' => '61', 'correct' => 1],
            ['text' => '71', 'correct' => 0],
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | DEV 3 - Gamuda Land
    |--------------------------------------------------------------------------
    */
    private function gamudaLand()
    {
        $dev = Developer::where('name', 'Gamuda Land')->orWhere('id', 3)->first();

        $this->createQuestion($dev, 'Which highway provides connectivity to Gamuda Cove?', [
            ['text' => 'NKVE', 'correct' => 0],
            ['text' => 'ELITE Highway', 'correct' => 1],
            ['text' => 'MEX Highway', 'correct' => 0],
            ['text' => 'LDP', 'correct' => 0],
        ]);

        $this->createQuestion($dev, 'What type of property is Quayside Plazas?', [
            ['text' => 'Semi-D homes', 'correct' => 0],
            ['text' => 'Link homes', 'correct' => 0],
            ['text' => 'Serviced apartments', 'correct' => 1],
            ['text' => 'Cluster homes', 'correct' => 0],
        ]);

        $this->createQuestion($dev, 'What type of homes are offered at Luxura Designer Courtyard & Link Villas?', [
            ['text' => '2-storey terrace homes', 'correct' => 0],
            ['text' => '2-storey superlink homes', 'correct' => 1],
            ['text' => '3-storey cluster homes', 'correct' => 0],
            ['text' => '2-storey semi-D homes', 'correct' => 0],
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | DEV 4 - Tropicana Alam
    |--------------------------------------------------------------------------
    */
    private function tropicanaAlam()
    {
        $dev = Developer::where('name', 'Tropicana Alam')->orWhere('id', 4)->first();

        $this->createQuestion($dev, 'What is the land size of Avisa Residences – Premium Green Terraces?', [
            ['text' => "From 18' x 65'", 'correct' => 0],
            ['text' => "From 20' x 70'", 'correct' => 1],
            ['text' => "From 22' x 75'", 'correct' => 0],
            ['text' => "From 25' x 80'", 'correct' => 0],
        ]);

        $this->createQuestion($dev, 'How many bedrooms and bathrooms are available in the Avisa Residences Premium Green Terraces?', [
            ['text' => '3 bedrooms & 3 bathrooms', 'correct' => 0],
            ['text' => '4 bedrooms & 3 bathrooms', 'correct' => 0],
            ['text' => '4 bedrooms & 4 bathrooms', 'correct' => 1],
            ['text' => '5 bedrooms & 4 bathrooms', 'correct' => 0],
        ]);

        $this->createQuestion($dev, 'Which of the following is one of the major highways accessible from Avisa Residences?', [
            ['text' => 'ELITE', 'correct' => 0],
            ['text' => 'LATAR', 'correct' => 1],
            ['text' => 'KESAS', 'correct' => 0],
            ['text' => 'MRR2', 'correct' => 0],
        ]);
    }
}
