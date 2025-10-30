<?php

defined('TYPO3') or die('Access denied.');

use Webzadev\Hisnulmuslim\Controller\ChapterController;
use Webzadev\Hisnulmuslim\Controller\CategoryController;
use Webzadev\Hisnulmuslim\Controller\ApiController;
use Webzadev\Hisnulmuslim\Controller\AppController;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

// Add default RTE configuration
$GLOBALS['TYPO3_CONF_VARS']['RTE']['Presets']['hisnulmuslim'] = 'EXT:hisnulmuslim/Configuration/RTE/Default.yaml';

// Registrierung der Plugins oder Module (bei Bedarf)
ExtensionUtility::configurePlugin(
    'Hisnulmuslim',
    'Overview',
    [
        ChapterController::class => 'list, show'
    ],
    [
        // ChapterController::class => ''
    ]
);

// Registrierung der Plugins oder Module (bei Bedarf)
ExtensionUtility::configurePlugin(
    'Hisnulmuslim',
    'OverviewCategory',
    [
        ChapterController::class => 'bycategory, show'
    ],
    [
        // ChapterController::class => ''
    ]
);

ExtensionUtility::configurePlugin(
    'Hisnulmuslim', // <== Dein extKey mit Vendor in composer.json abgleichen
    'Api',
    [
        ApiController::class => 'list'
    ],
    [
        ApiController::class => 'list'
    ]
);

ExtensionUtility::configurePlugin(
    'Hisnulmuslim', // <== Dein extKey mit Vendor in composer.json abgleichen
    'App',
    [
        AppController::class => 'index, list, show, search'
    ],
    [
        AppController::class => 'index, list, show, search'
    ]
);