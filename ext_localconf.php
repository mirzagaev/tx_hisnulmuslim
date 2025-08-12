<?php

defined('TYPO3') or die('Access denied.');

use Webzadev\Hisnulmuslim\Controller\ChapterController;
use Webzadev\Hisnulmuslim\Controller\ApiController;
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

// ExtensionUtility::configurePlugin(
//     'Hisnulmuslim', // <== Dein extKey mit Vendor in composer.json abgleichen
//     'Api',
//     [ApiController::class => 'list'],
//     [ApiController::class => 'list'] // non-cacheable
// );