<?php

return [
    'campaign' => [
        'name' => 'Porsche Cayenne – Interactive Journey Quiz',
        'porsche_logo' => 'porsche/images/PORSCHE_logo_2x.webp',
        'landing_header' => 'porsche/images/landing page_header_2x.webp',
        'landing_bg' => 'porsche/images/01 PORSCHE_landing_2x.webp',
        'form_bg' => 'porsche/images/02 PORSCHE_form_2x.webp',
        'instruction_bg' => 'porsche/images/03 PORSCHE_instruction_2x.webp',
        'quiz_bg' => 'porsche/images/04 PORSCHE_question_2x.webp',
        'privacy_policy_url' => env('PORSCHE_PRIVACY_POLICY_URL', 'https://dealer.porsche.com/my/malaysia/en-GB/Privacy-Policy'),
        'consent_statement' => 'I consent to the collection, use and/or disclosure of my personal data by Sime Darby Auto Performance Sdn Bhd, its related corporations and affiliates, and their authorised service providers and relevant third parties in accordance with the Privacy Policy.',
        'accent_color' => '#d5001c', // Porsche Red
    ],

    'questions' => [
        1 => [
            'id' => 1,
            'number' => 1,
            'question' => 'What makes your journey truly worthwhile?',
            'options' => [
                'A' => 'The excitement of new discoveries',
                'B' => 'Experiencing the drive as much as the destination',
                'C' => 'Creating new memories and seeing smiles in every seat',
            ],
        ],
        2 => [
            'id' => 2,
            'number' => 2,
            'question' => 'Great weather, and a free day ahead. What is your ideal weekend drive?',
            'options' => [
                'A' => 'A few unexpected turns and unpaved trails',
                'B' => 'Roads that make every kilometre worth driving',
                'C' => 'Time to connect with the people around me',
            ],
        ],
        3 => [
            'id' => 3,
            'number' => 3,
            'question' => "What matters most when you're faced with unpredictable Malaysian roads?",
            'options' => [
                'A' => 'Unshakable grip and body stability, no matter the road',
                'B' => 'Immediate shift and throttle response for that perfect drive',
                'C' => 'The perfect balance of driving performance and uncompromising comfort for everyone',
            ],
        ],
        4 => [
            'id' => 4,
            'number' => 4,
            'question' => 'What are you most likely to remember long after a journey ends?',
            'options' => [
                'A' => 'The unexpected discoveries along the way',
                'B' => 'The inspiring experiences that gave a new perspective',
                'C' => 'The moments shared along the way',
            ],
        ],
    ],

    'results' => [
        'A' => [
            'id' => 'A',
            'headline' => 'YOUR NEXT JOURNEY IS:',
            'title' => 'The one with endless possibilities',
            'subtitle' => 'You are driven by the roads less travelled.',
            'description' => 'Hidden discoveries and unexpected tours are what make the journey worthwhile.',
            'background_image' => 'porsche/images/05-1 PORSCHE_result 1_2x.webp',
            'shield_image' => 'porsche/images/Result (1)_2x.webp',
            'button_text' => 'DONE',
        ],
        'B' => [
            'id' => 'B',
            'headline' => 'YOUR NEXT JOURNEY IS:',
            'title' => 'The one that is an unforgettable experience',
            'subtitle' => 'From remarkable places to memorable drives, you appreciate experiences that leave a lasting impression.',
            'description' => 'Because some journeys stay with you long after the drive ends.',
            'background_image' => 'porsche/images/05-1 PORSCHE_result 2_2x.webp',
            'shield_image' => 'porsche/images/Result (2)_2x.webp',
            'button_text' => 'DONE',
        ],
        'C' => [
            'id' => 'C',
            'headline' => 'YOUR NEXT JOURNEY IS:',
            'title' => 'The one that becomes a memory worth keeping',
            'subtitle' => 'The moments, the conversations and connections made along the way is what matters most to you.',
            'description' => 'Because the greatest memories are those experienced together.',
            'background_image' => 'porsche/images/05-3 PORSCHE_result 3_2x.webp',
            'shield_image' => 'porsche/images/Result (3)_2x.webp',
            'button_text' => 'DONE',
        ],
    ],
];
