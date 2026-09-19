<?php

use Knuckles\Scribe\Config\AuthIn;
use Knuckles\Scribe\Config\Defaults;
use Knuckles\Scribe\Extracting\Strategies;

use function Knuckles\Scribe\Config\configureStrategy;
use function Knuckles\Scribe\Config\removeStrategies;

// Only the most common configs are shown. See the https://scribe.knuckles.wtf/laravel/reference/config for all.



return [
    'info' => [
        'title' => 'Telemedicine Platform API',
        'version' => '1.0.0',
        'description' => 'Telemedicine Platform API',
    ],
    'base_url' => 'http://localhost:8000/api',
    
    'routes' => [
        [
            'match' => [
                'prefixes' => ['api/'],
            ],
            // ✅ أضف الـ endpoints التي تريد توثيقها هنا
            'include' => [
                'api/Rating-create',
                // 'api/faq',
                // 'api/doctors',
            ],
        ],
    ],
];