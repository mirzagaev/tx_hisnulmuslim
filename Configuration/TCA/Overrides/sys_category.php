<?php
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

defined('TYPO3') or die;

/**
 * Add extra fields to the sys_category record
 */
$newSysCategoryColumns = [
    'icon' => [
        'exclude' => true,
        'label' => 'Icon',
        'config' => [
            'type' => 'file',
            'appearance' => [
                'createNewRelationLinkTitle' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:images.addFileReference',
                'showPossibleLocalizationRecords' => true,
                'showAllLocalizationLink' => true,
                'showSynchronizationLink' => true,
            ],
            'behaviour' => [
                'allowLanguageSynchronization' => true,
            ],
            'allowed' => 'common-image-types',
        ],
    ],
    'color' => [
        'exclude' => 0,
        'label' => 'Kategoriefarbe',
        'config' => [
            'type' => 'input',
            'size' => 10,
            'eval' => 'trim',
            'renderType' => 'colorpicker',
        ],
    ],
];


ExtensionManagementUtility::addTCAcolumns('sys_category', $newSysCategoryColumns);

ExtensionManagementUtility::addToAllTCAtypes(
    'sys_category',
    'icon, color',
    '',
    'after:title'
);