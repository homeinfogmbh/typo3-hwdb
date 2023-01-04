<?php
defined('TYPO3_MODE') || die();


\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
    'hwdb',
    'hwdb',
    '',
    '',
    [
        \Homeinfo\hwdb\Controller\DebugController::class => 'index',
    ],
    [
        'access' => 'user',
        'labels' => 'LLL:EXT:hwdb/Resources/Private/Language/locallang_be.xlf:backend.deployments.label',
        'inheritNavigationComponentFromMainModule' => false,
        'standalone' => true,
    ]
);