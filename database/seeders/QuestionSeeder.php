<?php

namespace Database\Seeders;

use App\Models\Answer;
use App\Models\Developer;
use App\Models\Question;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class QuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $this->berjaya();
        $this->mahSing();
        $this->belleview();
        $this->gsd();
        $this->malton();
        $this->setia();
        $this->pdc();
        $this->uda();
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

    // =========================
    // BERJAYA
    // =========================
    private function berjaya()
    {
        $dev = Developer::where('name', 'Berjaya Property Berhad')->first();

        $this->createQuestion($dev,
            'What is a key feature of Jesselton Courtyard at Jesselton Selatan?',
            [
                ['text'=>'High-density skyscraper living', 'correct'=>0],
                ['text'=>'Industrial warehouse concept', 'correct'=>0],
                ['text'=>'Low-Density, Low-Rise, Gated & Guarded', 'correct'=>1],
                ['text'=>'Open public housing', 'correct'=>0],
            ]
        );

        $this->createQuestion($dev,
            'Which of the following is NOT a feature of OAKA Residences?',
            [
                ['text'=>'4–6 Doorfront Carparks', 'correct'=>1],
                ['text'=>'Freehold Low-Density Residential Development', 'correct'=>0],
                ['text'=>'All Units Come with Balcony/Lanai & Utility Room', 'correct'=>0],
                ['text'=>'Pet-Friendly', 'correct'=>0],
            ]
        );

        $this->createQuestion($dev,
            'Which of the following is a key feature of Times Square 2?',
            [
                ['text'=>'Leasehold landed homes with garden space', 'correct'=>0],
                ['text'=>'Freehold Serviced Residence in Bukit Bintang, Kuala Lumpur', 'correct'=>1],
                ['text'=>'Industrial warehouse units', 'correct'=>0],
                ['text'=>'Agricultural land investment', 'correct'=>0],
            ]
        );
    }

    // =========================
    // MAH SING
    // =========================
    private function mahSing()
    {
        $dev = Developer::where('name', 'Mah Sing Group Berhad')->first();

        $this->createQuestion($dev,
            'What is the price range of M Zenni?',
            [
                ['text'=>'RM8xxK onwards', 'correct'=>0],
                ['text'=>'RM4xxK onwards', 'correct'=>1],
                ['text'=>'RM7xxK onwards', 'correct'=>0],
                ['text'=>'RM2xxK onwards', 'correct'=>0],
            ]
        );

        $this->createQuestion($dev,
            'What is a key advantage of M Zenni?',
            [
                ['text'=>'Located in a remote rural area with limited access', 'correct'=>0],
                ['text'=>'Designed mainly for agricultural use', 'correct'=>0],
                ['text'=>'No nearby highways or commercial hubs', 'correct'=>0],
                ['text'=>'Prime location near Tun Dr Lim Chong Eu Highway, Queensbay Mall, Penang Silicon Island, Bayan Lepas FIZ & Penang Second Bridge', 'correct'=>1],
            ]
        );

        $this->createQuestion($dev,
            'What type of development is M Zenni?',
            [
                ['text'=>'Shopping mall', 'correct'=>0],
                ['text'=>'Serviced Residence', 'correct'=>1],
                ['text'=>'Office tower', 'correct'=>0],
                ['text'=>'Hotel', 'correct'=>0],
            ]
        );
    }

    // =========================
    // BELLEVIEW
    // =========================
    private function belleview()
    {
        $dev = Developer::where('name', 'Belleview Group')->first();

        $this->createQuestion($dev,
            'Below are the projects featured by Belleview Group Berhad, except?',
            [
                ['text'=>'GEM Residences', 'correct'=>0],
                ['text'=>'M Aspira', 'correct'=>1],
                ['text'=>'Moulmein Rise', 'correct'=>0],
                ['text'=>'Amansuri Residences', 'correct'=>0],
            ]
        );

        $this->createQuestion($dev,
            'Which of the following is a key feature of Gem Residences?',
            [
                ['text'=>'Leasehold industrial warehouse project', 'correct'=>0],
                ['text'=>'Agricultural land development in rural area', 'correct'=>0],
                ['text'=>'Office-only commercial building with no residential units', 'correct'=>0],
                ['text'=>'Freehold mixed development with studio to 3-bedroom units suitable for family living', 'correct'=>1],
            ]
        );

        $this->createQuestion($dev,
            'What type of development is Gem Residences?',
            [
                ['text'=>'Serviced Residence / Condominium', 'correct'=>1],
                ['text'=>'Office', 'correct'=>0],
                ['text'=>'Landed', 'correct'=>0],
                ['text'=>'Hotel', 'correct'=>0],
            ]
        );
    }

    // =========================
    // GSD
    // =========================
    private function gsd()
    {
        $dev = Developer::where('name', 'GSD Group')->first();

        $this->createQuestion($dev,
            'What makes G\'Vinton unique as a development?',
            [
                ['text'=>'It is a fully industrial warehouse hub', 'correct'=>0],
                ['text'=>'It offers guest-ready furnished units and is a landmark at Millionaire Row', 'correct'=>1],
                ['text'=>'It consists only of landed terrace houses', 'correct'=>0],
                ['text'=>'It is a government office complex', 'correct'=>0],
            ]
        );

        $this->createQuestion($dev,
            'What are the key features of D\'Hazelton?',
            [
                ['text'=>'Affordable living with 67 facilities and built-up size from 900 sqft', 'correct'=>1],
                ['text'=>'Industrial warehouse development with no facilities', 'correct'=>0],
                ['text'=>'Luxury bungalow estate starting from 5,000 sqft', 'correct'=>0],
                ['text'=>'Office tower with retail mall only', 'correct'=>0],
            ]
        );

        $this->createQuestion($dev,
            'What is the tenure of D\'Tiara?',
            [
                ['text'=>'Leasehold', 'correct'=>0],
                ['text'=>'Freehold', 'correct'=>1],
                ['text'=>'Government leasehold', 'correct'=>0],
                ['text'=>'30-year lease', 'correct'=>0],
            ]
        );
    }

    // =========================
    // MALTON
    // =========================
    private function malton()
    {
        $dev = Developer::where('name', 'Malton Berhad')->first();

        $this->createQuestion($dev,
            'What is a key nearby transportation access point for River Park Bangsar South?',
            [
                ['text'=>'MRT Kajang Station', 'correct'=>0],
                ['text'=>'KLIA Express Station', 'correct'=>0],
                ['text'=>'Angkasapuri KTM Station', 'correct'=>1],
                ['text'=>'Wangsa Maju LRT Station', 'correct'=>0],
            ]
        );

        $this->createQuestion($dev,
            'What convenience feature is offered at Park Green Pavilion Bukit Jalil?',
            [
                ['text'=>'Free ferry service to Penang Island', 'correct'=>0],
                ['text'=>'Private helicopter service to KLCC', 'correct'=>0],
                ['text'=>'Direct monorail connection within the development', 'correct'=>0],
                ['text'=>'Free shuttle service to Awan Besar MRT Station', 'correct'=>1],
            ]
        );

        $this->createQuestion($dev,
            'What is the target completion year for River Park Bangsar South?',
            [
                ['text'=>'2024', 'correct'=>0],
                ['text'=>'2025', 'correct'=>0],
                ['text'=>'2026', 'correct'=>1],
                ['text'=>'2027', 'correct'=>0],
            ]
        );
    }

    // =========================
    // SETIA
    // =========================
    private function setia()
    {
        $dev = Developer::where('name', 'S P Setia Berhad')->first();

        $this->createQuestion($dev,
            'What is a key feature of Setia SV2?',
            [
                ['text'=>'Fully landed development with no high-rise units', 'correct'=>0],
                ['text'=>'Industrial warehouse project with no residential component', 'correct'=>0],
                ['text'=>'Located in rural Kedah with agricultural zoning', 'correct'=>0],
                ['text'=>'Located in the heart of Georgetown with flexible layouts from 1,087 sqft to 1,647 sqft', 'correct'=>1],
            ]
        );

        $this->createQuestion($dev,
            'What is the expected completion year of Setia SV2?',
            [
                ['text'=>'2029', 'correct'=>1],
                ['text'=>'2028', 'correct'=>0],
                ['text'=>'2027', 'correct'=>0],
                ['text'=>'2026', 'correct'=>0],
            ]
        );

        $this->createQuestion($dev,
            'What is the tenure of Setia SV2?',
            [
                ['text'=>'Leasehold', 'correct'=>0],
                ['text'=>'Freehold', 'correct'=>1],
                ['text'=>'Government leasehold', 'correct'=>0],
                ['text'=>'30-year lease', 'correct'=>0],
            ]
        );
    }

    // =========================
    // PDC
    // =========================
    private function pdc()
    {
        $dev = Developer::where('name', 'PDC Properties Sdn Bhd')->first();

        $this->createQuestion($dev,
            'What is the tenure of Cassia Cempaka Phase 2?',
            [
                ['text'=>'Leasehold residence', 'correct'=>0],
                ['text'=>'Freehold residence', 'correct'=>1],
                ['text'=>'Industrial leasehold development', 'correct'=>0],
                ['text'=>'Short-term rental property only', 'correct'=>0],
            ]
        );

        $this->createQuestion($dev,
            'What is the project type of Cassia Cempaka Phase 2?',
            [
                ['text'=>'Commercial Office', 'correct'=>0],
                ['text'=>'Industrial Warehouse', 'correct'=>0],
                ['text'=>'Residential', 'correct'=>1],
                ['text'=>'Retail Mall', 'correct'=>0],
            ]
        );

        $this->createQuestion($dev,
            'Where is Damai Lestari located?',
            [
                ['text'=>'In the heart of Kuala Lumpur city center', 'correct'=>0],
                ['text'=>'In Penang Island beachfront area', 'correct'=>0],
                ['text'=>'In Johor industrial zone', 'correct'=>0],
                ['text'=>'In the highly strategic heart of Bertam town', 'correct'=>1],
            ]
        );
    }

    // =========================
    // UDA
    // =========================
    private function uda()
    {
        $dev = Developer::where('name', 'UDA Land (North) Sdn Bhd')->first();

        $this->createQuestion($dev,
            'What is the starting price of Eight & Eight Condominium?',
            [
                ['text'=>'From RM6xxK onwards', 'correct'=>1],
                ['text'=>'From RM2xxK onwards', 'correct'=>0],
                ['text'=>'From RM1 Million onwards', 'correct'=>0],
                ['text'=>'From RM2 Million onwards', 'correct'=>0],
            ]
        );

        $this->createQuestion($dev,
            'What is the expected completion year of Eight & Eight Condominium?',
            [
                ['text'=>'2026', 'correct'=>0],
                ['text'=>'2027', 'correct'=>0],
                ['text'=>'2028', 'correct'=>0],
                ['text'=>'2029', 'correct'=>1],
            ]
        );

        $this->createQuestion($dev,
            'What type of development is Eight & Eight?',
            [
                ['text'=>'Shopping mall', 'correct'=>0],
                ['text'=>'Condominium', 'correct'=>1],
                ['text'=>'Office tower', 'correct'=>0],
                ['text'=>'Hotel', 'correct'=>0],
            ]
        );
    }
}
