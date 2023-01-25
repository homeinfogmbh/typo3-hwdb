<?php
defined('TYPO3_MODE') || die();

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'hwdb',
    'hwdb',
    [
        \Homeinfo\hwdb\Controller\DebugController::class => 'listDeployments,listSystems',
    ],
);