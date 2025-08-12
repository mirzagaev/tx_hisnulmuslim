<?php
return [
    'ctrl' => [
        'title' => 'LLL:EXT:hisnulmuslim/Resources/Private/Language/locallang_db.xlf:tx_hisnulmuslim_dua',
        'label' => 'dua_id',
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
                dua_id, chapter, items
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
        'dua_id' => [
            'exclude' => false,
            'label' => 'LLL:EXT:hisnulmuslim/Resources/Private/Language/locallang_db.xlf:tx_hisnulmuslim_dua.dua_id',
            'config' => [
                'type' => 'input',
                'eval' => 'int,required',
                'size' => 10,
            ],
        ],
        'chapter' => [
            'exclude' => true,
            'label' => 'Kapitel',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_hisnulmuslim_domain_model_chapter',
                'maxitems' => 1,
                'default' => 0,
            ],
        ],
        'items' => [
            'exclude' => true,
            'label' => 'Elemente',
            'config' => [
                'type' => 'inline',
                'foreign_table' => 'tx_hisnulmuslim_domain_model_dua_item',
                'foreign_field' => 'dua',
                'appearance' => [
                    'collapseAll' => 1,
                    'newRecordLinkAddTitle' => true,
                    'useSortable' => true,
                ],
            ],
        ],
    ],
];
