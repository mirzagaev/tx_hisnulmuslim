<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'HisnulMuslim',
    'description' => '',
    'category' => 'templates',
    'constraints' => [
        'depends' => [
            'typo3' => '13.4.0-13.4.99',
            'fluid_styled_content' => '13.4.0-13.4.99',
            'rte_ckeditor' => '13.4.0-13.4.99',
        ],
        'conflicts' => [
        ],
    ],
    'autoload' => [
        'psr-4' => [
            'Webzadev\\Hisnulmuslim\\' => 'Classes',
        ],
    ],
    'state' => 'stable',
    'uploadfolder' => 0,
    'createDirs' => '',
    'clearCacheOnLoad' => 1,
    'author' => 'Aydin Mirzagayev',
    'author_email' => 'aydin@mirzagayev.de',
    'author_company' => 'webza.dev',
    'version' => '1.0.0',
];
