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
        'sortby' => 'sorting',
        'iconfile' => 'EXT:hisnulmuslim/Resources/Public/Icons/tx_hisnulmuslim_chapter.svg',
    ],
    'types' => [
        '0' => [
            'showitem' => '
                sys_language_uid, l10n_parent, hidden,
                chapter_id, title, title_ar, duas
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
        'title_ar' => [
            'exclude' => false,
            'label' => 'LLL:EXT:hisnulmuslim/Resources/Private/Language/locallang_db.xlf:tx_hisnulmuslim_chapter.title_ar',
            'config' => [
                'type' => 'text',
                'enableRichtext' => true,
            ],
        ],
        'duas' => [
            'exclude' => true,
            'label' => 'LLL:EXT:hisnulmuslim/Resources/Private/Language/locallang_db.xlf:tx_hisnulmuslim_chapter.duas',
            'config' => [
                'type' => 'inline',
                'foreign_table' => 'tx_hisnulmuslim_dua',
                'foreign_field' => 'chapter',
                'appearance' => [
                    'collapseAll' => 1,
                    'newRecordLinkAddTitle' => true,
                    'useSortable' => true,
                ],
            ],
        ],
    ],
];