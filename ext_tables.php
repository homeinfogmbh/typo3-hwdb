<?php
defined('TYPO3_MODE') || die();


\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
    'hwdb',
    'hwdb',
    '',
    '',
    [
        \Homeinfo\SysMon2\Controller\DebugController::class => 'index',
    ],
    [
        'access' => 'user',
        'labels' => 'LLL:EXT:hwdb/Resources/Private/Language/locallang_be.xlf:backend.checkresults.label',
        'inheritNavigationComponentFromMainModule' => false,
        'standalone' => true,
    ]
);