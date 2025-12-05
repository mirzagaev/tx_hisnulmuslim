<?php
return [
    'ctrl' => [
        'title' => 'LLL:EXT:hisnulmuslim/Resources/Private/Language/locallang_db.xlf:tx_hisnulmuslim_chapter',
        'label' => 'title',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'languageField' => 'sys_language_uid',
        'transOrigPointerField' => 'l10n_parent',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
        ],
        'searchFields' => 'title,title_ar',
        'sortby' => 'chapter_id',
        'iconfile' => 'EXT:hisnulmuslim/Resources/Public/Icons/Extension.png',
    ],
    'types' => [
        '0' => [
            'showitem' => '
                --div--;Allgemein,
                    --palette--;;language, --palette--;;visibility, chapter_id, title, slug, title_ar, hidden,
                --div--;Bittgebete,
                    dua,
                --div--;Kategorie,
                    categories
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
        'chapter_id' => [
            'exclude' => false,
            'label' => 'LLL:EXT:hisnulmuslim/Resources/Private/Language/locallang_db.xlf:tx_hisnulmuslim_chapter.id',
            'config' => [
                'type' => 'input',
                'eval' => 'int,required',
                'size' => 10,
            ],
        ],
        'title' => [
            'exclude' => false,
            'label' => 'LLL:EXT:hisnulmuslim/Resources/Private/Language/locallang_db.xlf:tx_hisnulmuslim_chapter.title',
            'config' => [
                'type' => 'input',
                'eval' => 'trim,required',
                'size' => 50,
            ],
        ],
        'slug' => [
            'exclude' => true,
            'label' => 'URL Segment (Slug)',
            'config' => [
                'type' => 'slug',
                'size' => 50,
                'generatorOptions' => [
                    'fields' => ['title'],
                    'replacements' => [
                        '/' => '-',
                    ],
                ],
                'fallbackCharacter' => '-',
                'eval' => 'uniqueInSite',
            ],
        ],
        'title_ar' => [
            'exclude' => false,
            'label' => 'LLL:EXT:hisnulmuslim/Resources/Private/Language/locallang_db.xlf:tx_hisnulmuslim_chapter.title_ar',
            'config' => [
                'type' => 'text',
                'enableRichtext' => true,
            ],
        ],
        'dua' => [
            'exclude' => true,
            'label' => 'LLL:EXT:hisnulmuslim/Resources/Private/Language/locallang_db.xlf:tx_hisnulmuslim_chapter.dua',
            'config' => [
                'type' => 'inline',
                'foreign_table' => 'tx_hisnulmuslim_domain_model_dua',
                'foreign_field' => 'chapter',
                'appearance' => [
                    'collapseAll' => 1,
                    'newRecordLinkAddTitle' => true,
                    'useSortable' => true,
                ],
            ],
        ],
        'categories' => [
            'label' => 'Kategorien',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'foreign_table' => 'sys_category',
                'MM' => 'tx_hisnulmuslim_domain_model_chapter_category_mm',
                'size' => 5,
                'autoSizeMax' => 20,
                'multiple' => 0,
                'maxitems' => 999,
                'wizards' => [
                '_PADDING' => 1,
                'suggest' => [
                    'type' => 'suggest',
                ],
                ],
            ],
        ]
    ],
];