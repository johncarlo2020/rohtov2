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
        $this->ijm();
        $this->orientalKedahRealty();
        $this->easternAndOriental();
        $this->spb();
        $this->ideal();
        $this->pdc();
        $this->jayamas();
        $this->uda();
        $this->paramount();
        $this->scientex();
        // $this->jrk();
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

    private function ijm()
    {
        $dev = Developer::where('name', 'IJM Land Berhad')->first();

        $this->createQuestion($dev,'What is a key feature of Merione Residences?',[
            ['text'=>'Private yacht marina for every unit','correct'=>0],
            ['text'=>'Direct access to a private beach club','correct'=>0],
            ['text'=>'Smart facial recognition security access','correct'=>1],
            ['text'=>'Helicopter landing pad for residents','correct'=>0],
        ]);

        $this->createQuestion($dev,'Which of the following is NOT a feature of Terraces Condominium?',[
            ['text'=>'Private beach access and marina facilities','correct'=>1],
            ['text'=>'Walking distance to Bukit Jambul Hiking Trail','correct'=>0],
            ['text'=>'Full condominium facilities','correct'=>0],
            ['text'=>'Prime address close to the Millionaire Row of Bukit Jambul','correct'=>0],
        ]);

        $this->createQuestion($dev,'How many units are available at Ayra Terraces?',[
            ['text'=>'90 units only','correct'=>0],
            ['text'=>'100 units only','correct'=>1],
            ['text'=>'200 units only','correct'=>0],
            ['text'=>'300 units only','correct'=>0],
        ]);
    }

    private function orientalKedahRealty()
    {
        $dev = Developer::where('name', 'Oriental Kedah Realty')->first();

        $this->createQuestion($dev,'Where is Taman Kerian Putra located?',[
            ['text'=>'Batu Ferringhi','correct'=>0],
            ['text'=>'Bayan Lepas','correct'=>0],
            ['text'=>'Parit Buntar','correct'=>1],
            ['text'=>'Georgetown','correct'=>0],
        ]);

        $this->createQuestion($dev,'What is the tenure of Taman Sinar Putra?',[
            ['text'=>'Freehold','correct'=>1],
            ['text'=>'Commercial Title','correct'=>0],
            ['text'=>'Temporary Occupation License','correct'=>0],
            ['text'=>'Leasehold','correct'=>0],
        ]);

        $this->createQuestion($dev,'What is the starting price of Taman Seri Aman?',[
            ['text'=>'From RM2xxK*','correct'=>0],
            ['text'=>'From RM4xxK*','correct'=>1],
            ['text'=>'From RM7xxK*','correct'=>0],
            ['text'=>'From RM1 million*','correct'=>0],
        ]);
    }

    private function easternAndOriental()
    {
        $dev = Developer::where('name', 'Eastern & Oriental Berhad')->first();

        $this->createQuestion($dev,'What is the price range of The Lume?',[
            ['text'=>'RM9xxK onwards','correct'=>0],
            ['text'=>'RM2 Million onwards','correct'=>1],
            ['text'=>'RM5xxK onwards','correct'=>0],
            ['text'=>'RM8xxK onwards','correct'=>0],
        ]);

        $this->createQuestion($dev,'What is a key feature of Maris?',[
            ['text'=>'Industrial warehouse units with office spaces','correct'=>0],
            ['text'=>'Landed homes with private farms','correct'=>0],
            ['text'=>'Commercial shop offices only','correct'=>0],
            ['text'=>'Fully furnished homes in a vibrant waterfront township','correct'=>1],
        ]);

        $this->createQuestion($dev,'What type of development is Avéa?',[
            ['text'=>'Low-density landed housing development','correct'=>0],
            ['text'=>'High-rise waterfront serviced apartment development','correct'=>1],
            ['text'=>'Industrial commercial hub','correct'=>0],
            ['text'=>'Boutique hotel and retail mall','correct'=>0],
        ]);
    }

    private function spb()
    {
        $dev = Developer::where('name', 'SPB Property')->first();

        $this->createQuestion($dev,'Which of the following projects is NOT featured by SPB Property?',[
            ['text'=>'La Casa Ara','correct'=>0],
            ['text'=>'M Aspira','correct'=>1],
            ['text'=>'La Casa Lunas','correct'=>0],
            ['text'=>'Adiwarna','correct'=>0],
        ]);

        $this->createQuestion($dev,'What is a key location advantage of La Casa Ara?',[
            ['text'=>'Located next to Penang International Airport','correct'=>0],
            ['text'=>'Direct access to a private beach','correct'=>0],
            ['text'=>'Walking distance to Komtar','correct'=>0],
            ['text'=>'Near KTM Batu Kawan & Bukit Mertajam stations','correct'=>1],
        ]);

        $this->createQuestion($dev,'What is a unique lifestyle feature of La Casa Lunas?',[
            ['text'=>'Central lake park and jogging track','correct'=>1],
            ['text'=>'Private golf course','correct'=>0],
            ['text'=>'Yacht marina','correct'=>0],
            ['text'=>'Rooftop infinity pool','correct'=>0],
        ]);
    }

    private function ideal()
    {
        $dev = Developer::where('name', 'Ideal Property Group')->first();

        $this->createQuestion($dev,'What type of views can residents enjoy at Queens Residences Q3?',[
            ['text'=>'Mountain views only','correct'=>0],
            ['text'=>'Stunning sea views','correct'=>1],
            ['text'=>'Golf course views only','correct'=>0],
            ['text'=>'Industrial park views','correct'=>0],
        ]);

        $this->createQuestion($dev,'Where is Queens Residences Q3 located?',[
            ['text'=>'Queens Waterfront, Bayan Lepas','correct'=>1],
            ['text'=>'Batu Ferringhi','correct'=>0],
            ['text'=>'Bukit Mertajam','correct'=>0],
            ['text'=>'Batu Kawan','correct'=>0],
        ]);

        $this->createQuestion($dev,'What is the tenure of Queens Residences Q3?',[
            ['text'=>'Leasehold','correct'=>0],
            ['text'=>'Freehold','correct'=>1],
            ['text'=>'Government leasehold','correct'=>0],
            ['text'=>'30-year lease','correct'=>0],
        ]);
    }

    private function pdc()
    {
        $dev = Developer::where('name', 'PDC Properties')->first();

        $this->createQuestion($dev,'What is the tenure of Cassia Cempaka Phase 2?',[
            ['text'=>'Leasehold residence','correct'=>0],
            ['text'=>'Freehold residence','correct'=>1],
            ['text'=>'Industrial leasehold development','correct'=>0],
            ['text'=>'Short-term rental property only','correct'=>0],
        ]);

        $this->createQuestion($dev,'What is the project type of Cassia Cempaka Phase 2?',[
            ['text'=>'Commercial Office','correct'=>0],
            ['text'=>'Industrial Warehouse','correct'=>0],
            ['text'=>'Residential','correct'=>1],
            ['text'=>'Retail Mall','correct'=>0],
        ]);

        $this->createQuestion($dev,'Where is Damai Lestari located?',[
            ['text'=>'In the heart of Kuala Lumpur city center','correct'=>0],
            ['text'=>'In Penang Island beachfront area','correct'=>0],
            ['text'=>'In Johor industrial zone','correct'=>0],
            ['text'=>'In the highly strategic heart of Bertam town','correct'=>1],
        ]);
    }

    private function jayamas()
    {
        $dev = Developer::where('name', 'Jayamas Property')->first();

        $this->createQuestion($dev,'What type of homes are offered at Urban Rize?',[
            ['text'=>'High-rise serviced apartments','correct'=>0],
            ['text'=>'Luxury beachfront villas','correct'=>0],
            ['text'=>'Modern 2-Storey Superlink Terrace Homes','correct'=>1],
            ['text'=>'Single-storey terrace houses','correct'=>0],
        ]);

        $this->createQuestion($dev,'What type of homes are featured at Tri Brighton?',[
            ['text'=>'Single-storey semi-detached homes','correct'=>0],
            ['text'=>'High-rise serviced apartments','correct'=>0],
            ['text'=>'Industrial shop offices','correct'=>0],
            ['text'=>'Contemporary 3-Storey Triplex Terraced Houses','correct'=>1],
        ]);

        $this->createQuestion($dev,'Where is Tri Brighton strategically located?',[
            ['text'=>'Georgetown','correct'=>0],
            ['text'=>'Batu Ferringhi','correct'=>0],
            ['text'=>'Seberang Jaya','correct'=>1],
            ['text'=>'Balik Pulau','correct'=>0],
        ]);
    }

    private function uda()
    {
        $dev = Developer::where('name', 'UDA Land (North) Sdn Bhd')->first();

        $this->createQuestion($dev,'What is the starting price of Eight & Eight Condominium?',[
            ['text'=>'From RM6xxK onwards','correct'=>1],
            ['text'=>'From RM2xxK onwards','correct'=>0],
            ['text'=>'From RM1 Million onwards','correct'=>0],
            ['text'=>'From RM2 Million onwards','correct'=>0],
        ]);

        $this->createQuestion($dev,'What is the expected completion year of Eight & Eight Condominium?',[
            ['text'=>'2026','correct'=>0],
            ['text'=>'2027','correct'=>0],
            ['text'=>'2028','correct'=>0],
            ['text'=>'2029','correct'=>1],
        ]);

        $this->createQuestion($dev,'What type of development is Eight & Eight?',[
            ['text'=>'Shopping mall','correct'=>0],
            ['text'=>'Condominium','correct'=>1],
            ['text'=>'Office tower','correct'=>0],
            ['text'=>'Hotel','correct'=>0],
        ]);
    }

    private function paramount()
    {
        $dev = Developer::where('name', 'Paramount Property')->first();

        $this->createQuestion($dev,'What makes Seiras Residences unique in Penang?',[
            ['text'=>'It is the first floating residential project in Malaysia','correct'=>0],
            ['text'=>'It only offers studio apartments with no parking','correct'=>0],
            ['text'=>'It introduces Penang’s first triple-key serviced residence units','correct'=>1],
            ['text'=>'It is a landed gated community development','correct'=>0],
        ]);

        $this->createQuestion($dev,'Where is Embun Hills located?',[
            ['text'=>'Bayan Lepas, Penang','correct'=>0],
            ['text'=>'Georgetown, Penang','correct'=>0],
            ['text'=>'Batu Ferringhi, Penang','correct'=>0],
            ['text'=>'Bukit Mertajam, Penang','correct'=>1],
        ]);

        $this->createQuestion($dev,'What is the tenure of Seiras Residences?',[
            ['text'=>'Leasehold','correct'=>0],
            ['text'=>'Freehold','correct'=>1],
            ['text'=>'Commercial Title','correct'=>0],
            ['text'=>'Temporary Occupation License','correct'=>0],
        ]);
    }

    private function scientex()
    {
        $dev = Developer::where('name', 'Scientex Berhad')->first();

        $this->createQuestion($dev,'What is the starting price of Scientex Sungai Dua – Tulip?',[
            ['text'=>'From RM3xxK onwards','correct'=>1],
            ['text'=>'From RM8xxK onwards','correct'=>0],
            ['text'=>'From RM9xxK onwards','correct'=>0],
            ['text'=>'From RM1 Million onwards','correct'=>0],
        ]);

        $this->createQuestion($dev,'How far is Scientex SP Astana – Maple from the North-South Expressway?',[
            ['text'=>'4km','correct'=>0],
            ['text'=>'5km','correct'=>0],
            ['text'=>'6km','correct'=>0],
            ['text'=>'7km','correct'=>1],
        ]);

        $this->createQuestion($dev,'What type of property is Scientex SP Astana – Maple?',[
            ['text'=>'Serviced Apartment','correct'=>0],
            ['text'=>'2-Storey Terrace House','correct'=>1],
            ['text'=>'Semi-Detached Factory','correct'=>0],
            ['text'=>'Shop Office','correct'=>0],
        ]);
    }

    // private function jrk()
    // {
    //     $dev = Developer::where('name', 'JRK Group')->first();

    //     $this->createQuestion($dev,'Which of the following best describes JRK Celestia in Puchong?',[
    //         ['text'=>'Industrial warehouse development','correct'=>0],
    //         ['text'=>'Agricultural land project','correct'=>0],
    //         ['text'=>'High-rise condominium for modern living','correct'=>1],
    //         ['text'=>'Landed gated housing','correct'=>0],
    //     ]);

    //     $this->createQuestion($dev,'Which of the following is a key feature of JRK Celestia?',[
    //         ['text'=>'Beachfront sea view living','correct'=>0],
    //         ['text'=>'Gated & guarded with security surveillance','correct'=>1],
    //         ['text'=>'Factory production facilities','correct'=>0],
    //         ['text'=>'No lifestyle amenities','correct'=>0],
    //     ]);

    //     $this->createQuestion($dev,'Which award did JRK Group receive at the PropertyGuru Asia Awards Malaysia 2025?',[
    //         ['text'=>'Best Luxury Condo Development','correct'=>0],
    //         ['text'=>'Best Township Development','correct'=>0],
    //         ['text'=>'Best Commercial Project','correct'=>0],
    //         ['text'=>'Rising Star Award','correct'=>1],
    //     ]);
    // }

}
