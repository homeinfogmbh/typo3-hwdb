<?php

namespace Homeinfo\hwdb\Domain\Repository;

use Generator;

use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;

use Homeinfo\hwdb\Domain\Model\Deployment;
use Homeinfo\hwdb\Domain\Model\System;

class SystemRepository
{
    public function __construct(
        private readonly ConnectionPool $connectionPool
    )
    {
    }

    public function findById(int $id): System
    {
        return System.fromArray(
            ($queryBuilder = $this->select())
                ->where(
                    $queryBuilder->expr()->eq(
                        'id',
                        $queryBuilder->createNamedParameter($id, Connection::PARAM_INT)
                    )
                )
                ->executeQuery()
                ->fetch()
        );
    }

    public function findByDeploymentId(int $deploymentId): Generator
    {
        foreach (
            ($queryBuilder = $this->select())
                ->where(
                    $queryBuilder->expr()->eq(
                        'deployment',
                        $queryBuilder->createNamedParameter($deploymentId, Connection::PARAM_INT)
                    )
                )
                ->executeQuery()
                ->fetchAll()
            as &$record
        )
        {
            yield System::fromArray($record);
        }
    }

    public function findByDeploymentIds(array $deploymentIds): Generator
    {
        foreach (
            ($queryBuilder = $this->select())
                ->where(
                    $queryBuilder->expr()->in(
                        'deployment',
                        $queryBuilder->createNamedParameter($deploymentIds, Connection::PARAM_INT_ARRAY)
                    )
                )
                ->executeQuery()
                ->fetchAll()
            as &$record
        )
        {
            yield System::fromArray($record);
        }
    }

    public function list(): Generator
    {
        foreach ($this->select()->executeQuery()->fetchAll() as &$record)
        {
            yield System::fromArray($record);
        }
    }

    private function select(): QueryBuilder
    {
        return ($queryBuilder = $this->connectionPool->getQueryBuilderForTable('system'))
            ->select(
                'system.*',
                ...Deployment::aliasedFields('deployment'),
                ...Deployment::aliasedFields('dataset')
            )
            ->from('system')
            ->leftJoin(
                'system',
                'deployment',
                'deployment',
                $queryBuilder->expr()->eq('deployment.id', $queryBuilder->quoteIdentifier('system.deployment'))
            )
            ->leftJoin(
                'deployment',
                'mdb.address',
                'deployment_address',
                $queryBuilder->expr()->eq('deployment_address.id', $queryBuilder->quoteIdentifier('deployment.address'))
            )
            ->leftJoin(
                'deployment',
                'mdb.address',
                'deployment_lpt_address',
                $queryBuilder->expr()->eq('deployment_lpt_address.id', $queryBuilder->quoteIdentifier('deployment.lpt_address'))
            )
            ->leftJoin(
                'system',
                'deployment',
                'dataset',
                $queryBuilder->expr()->eq('dataset.id', $queryBuilder->quoteIdentifier('system.dataset'))
            )
            ->leftJoin(
                'dataset',
                'mdb.address',
                'dataset_address',
                $queryBuilder->expr()->eq('dataset_address.id', $queryBuilder->quoteIdentifier('dataset.address'))
            )
            ->leftJoin(
                'dataset',
                'mdb.address',
                'dataset_lpt_address',
                $queryBuilder->expr()->eq('dataset_lpt_address.id', $queryBuilder->quoteIdentifier('dataset.lpt_address'))
            );
    }
}
