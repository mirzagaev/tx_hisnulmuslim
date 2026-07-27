<?php

return [
    'frontend' => [
        'webzadev/hisnulmuslim/api' => [
            'target' => \Webzadev\Hisnulmuslim\Middleware\ApiMiddleware::class,
            'after' => [
                'typo3/cms-frontend/site-resolver',
            ],
            'before' => [
                'typo3/cms-frontend/page-resolver',
            ],
        ],
    ],
];
