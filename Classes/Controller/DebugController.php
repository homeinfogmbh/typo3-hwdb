<?php

namespace Homeinfo\hwdb\Controller;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

use TYPO3\CMS\Core\Database\ConnectionPool;
use Homeinfo\hwdb\Domain\Repository\DeploymentRepository;
use Homeinfo\hwdb\Domain\Repository\SystemRepository;

class DebugController extends ActionController
{
    public function listDeploymentsAction()
    {
        
        $repository = GeneralUtility::makeInstance(ObjectManager::class)
            ->get(DeploymentRepository::class);
        $deployments = $repository->list();
        //DebuggerUtility::var_dump($deployments, "Deployments: ");
        $this->view->assign('deployments', $deployments);
    }

    public function listSystemsAction()
    {
        
        $repository = GeneralUtility::makeInstance(ObjectManager::class)
            ->get(SystemRepository::class);
        $systems = $repository->list();
        DebuggerUtility::var_dump($systems, "Systems: ");
        $this->view->assign('systems', $systems);
    }
}
