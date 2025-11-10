<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Answer;
use App\Models\Station;

class AnswerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Station 1 Answers
        $station1 = Station::where('name', 'Treasure Spot 1')->first();
        $a1 = Answer::create(['station_id' => $station1->id, 'text' => 'Using low-carbon eco-friendly fertilizers.']);  // wrong
        $a2 = Answer::create(['station_id' => $station1->id, 'text' => 'Using fertilizers that cause high carbon emissions.']);   // correct
        $a3 = Answer::create(['station_id' => $station1->id, 'text' => 'Ignoring the environmental impact of  fertilizers']);    // wrong
        $a4 = Answer::create(['station_id' => $station1->id, 'text' => 'Using outdated fertilizer methods without innovation']);  // wrong

        $station1->answer_id = $a1->id; // set correct answer
        $station1->save();

        // Station 2 Answers
        $station2 = Station::where('name', 'Treasure Spot 2')->first();
        $b1 = Answer::create(['station_id' => $station2->id, 'text' => 'Yes']);      // correct
        $b2 = Answer::create(['station_id' => $station2->id, 'text' => 'No']);     // wrong

        $station2->answer_id = $b1->id; // set correct answer
        $station2->save();

        // Station 3 Answers
        $station3 = Station::where('name', 'Treasure Spot 3')->first();
        $c1 = Answer::create(['station_id' => $station3->id, 'text' => 'Palm Counting']);       // wrong
        $c2 = Answer::create(['station_id' => $station3->id, 'text' => 'Stress Diagnostic']);          // wrong
        $c3 = Answer::create(['station_id' => $station3->id, 'text' => 'Yield Prediction']);         // wrong
        $c4 = Answer::create(['station_id' => $station3->id, 'text' => 'All of the above']);   // correct

        $station3->answer_id = $c4->id; // set correct answer
        $station3->save();
    }
}
