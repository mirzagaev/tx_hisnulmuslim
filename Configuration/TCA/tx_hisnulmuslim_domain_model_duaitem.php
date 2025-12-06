<?php
return [
    'ctrl' => [
        'title' => 'LLL:EXT:hisnulmuslim/Resources/Private/Language/locallang_db.xlf:tx_hisnulmuslim_dua_item',
        'label' => 'type',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'languageField' => 'sys_language_uid',
        'transOrigPointerField' => 'l10n_parent',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
        ],
        'sortby' => 'sorting',
        'iconfile' => 'EXT:hisnulmuslim/Resources/Public/Icons/hm-logo-grau.png',
    ],
    'types' => [
        '0' => [
            'showitem' => '
                sys_language_uid, l10n_parent, hidden,
                type, content
            '
        ],
    ],
    'columns' => [
        'sys_language_uid' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.language',
            'config' => [
                'type' => 'language',
            ],
        ],
        'l10n_parent' => [
            'displayCond' => 'FIELD:sys_language_uid:>:0',
            'config' => [
                'type' => 'passthrough',
            ],
        ],
        'hidden' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.hidden',
            'config' => [
                'type' => 'check',
            ],
        ],
        'sorting' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
        'dua' => [
            'exclude' => true,
            'label' => 'Bittgebet',
            'config' => [
                'type' => 'passthrough',
            ],
        ],
        'type' => [
            'exclude' => false,
            'label' => 'Typ',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['Dua Übersetzung', 'dua_translation'],
                    ['Dua', 'dua'],
                    ['Dua Umschrift', 'dua_umschrift'],
                    ['Quran', 'quran'],
                    ['Quran Übersetzung', 'quran_translation'],
                    ['Quran Umschrift', 'quran_umschrift'],
                    ['Hadith', 'hadith'],
                    ['Hadith Übersetzung', 'hadith_translation'],
                    ['Hadith Umschrift', 'hadith_umschrift'],
                    ['Arabisch', 'ar'],
                    ['Arabisch Übersetzung', 'ar_translation'],
                    ['Arabisch Umschrift', 'ar_umschrift'],
                    ['Hinweis', 'hinweis'],
                    ['Quelle', 'quelle'],
                ],
            ],
        ],
        'content' => [
            'exclude' => false,
            'label' => 'Inhalt',
            'config' => [
                'type' => 'text',
                'enableRichtext' => true,
            ],
        ],
    ],
];
