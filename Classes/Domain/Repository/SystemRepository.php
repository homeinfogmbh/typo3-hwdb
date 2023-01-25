<?php

namespace Homeinfo\hwdb\Domain\Repository;

use Generator;

use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;

use Homeinfo\hwdb\Domain\Model\System;

class SystemRepository
{
    public function __construct(
        private readonly ConnectionPool $connectionPool
    )
    {
    }

    public function findById(int $id): array
    {
        return ($queryBuilder = $this->select())
            ->where(
                $queryBuilder->expr()->eq(
                    'id',
                    $queryBuilder->createNamedParameter($id, Connection::PARAM_INT)
                )
            )
            ->executeQuery()
            ->fetch();
    }

    public function findByDeploymentId(int $deploymentId): array
    {
        return ($queryBuilder = $this->select())
            ->where(
                $queryBuilder->expr()->eq(
                    'deployment',
                    $queryBuilder->createNamedParameter($deploymentId, Connection::PARAM_INT)
                )
            )
            ->executeQuery()
            ->fetchAll();
    }

    public function findByDeploymentIds(array $deploymentIds): array
    {
        return ($queryBuilder = $this->select())
            ->where(
                $queryBuilder->expr()->in(
                    'deployment',
                    $queryBuilder->createNamedParameter($deploymentIds, Connection::PARAM_INT_ARRAY)
                )
            )
            ->executeQuery()
            ->fetchAll();
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
        return ($queryBuilder = $this->connectionPool->getQueryBuilderForTable('deployment'))
            ->select('*')
            ->from('deployment');
    }
}
