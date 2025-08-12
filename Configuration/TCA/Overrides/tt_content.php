<?php

use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

defined('TYPO3') or die();

(static function (): void {
    $pluginKey = ExtensionUtility::registerPlugin(
        // extension name, matching the PHP namespaces (but without the vendor)
        'Hisnulmuslim',
        // arbitrary, but unique plugin name (not visible in the backend)
        'Overview',
        // plugin title, as visible in the drop-down in the backend, use "LLL:" for localization
        'Gesamtübersicht',
        // plugin icon, use an icon identifier from the icon registry
        'EXT:hisnulmuslim/Resources/Public/Icons/Extension.png',
        // plugin group, to define where the new plugin will be located in
        'Hisnul Muslim',
        // plugin description, as visible in the new content element wizard
        '',
    );
})();

// ExtensionUtility::registerPlugin(
//     'Hisnulmuslim', // Extension-Key (ohne tx_)
//     'Api',          // Plugin-Name (muss identisch mit dem in ext_localconf.php sein)
//     'API Ausgabe',  // Titel, wie er im Backend angezeigt wird
//     'EXT:hisnulmuslim/Resources/Public/Icons/Extension.png',
//     'Hisnul Muslim',
// );