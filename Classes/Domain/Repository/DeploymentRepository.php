<?php

namespace Homeinfo\hwdb\Domain\Repository;

use Generator;

use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;

use Homeinfo\hwdb\Domain\Model\Deployment;
use Homeinfo\mdb\Domain\Model\Address;

class DeploymentRepository
{
    public function __construct(
        private readonly ConnectionPool $connectionPool
    )
    {}

    public function findById(int $id): array
    {
        return Deployment::fromArray(
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

    public function findByCustomerId(int $customerId): Generator
    {
        foreach (
            ($queryBuilder = $this->select())
                ->where(
                    $queryBuilder->expr()->eq(
                        'customer',
                        $queryBuilder->createNamedParameter($customerId, Connection::PARAM_INT)
                    )
                )
                ->executeQuery()
                ->fetchAll() as $record
        )
            yield Deployment::fromArray($record);
    }

    public function list(): Generator
    {
        foreach ($this->select()->executeQuery()->fetchAll() as &$record)
            yield Deployment::fromArray($record);
    }

    private function select(): QueryBuilder
    {
        return ($queryBuilder = $this->connectionPool->getQueryBuilderForTable('deployment'))
            ->select(
                'deployment.*',
                ...Address::aliasedFields('address'),
                ...Address::aliasedFields('lpt_address')
            )
            ->from('deployment')
            ->leftJoin(
                'deployment',
                'mdb.address',
                'address',
                $queryBuilder->expr()->eq('address.id', $queryBuilder->quoteIdentifier('deployment.address'))
            )
            ->leftJoin(
                'deployment',
                'mdb.address',
                'lpt_address',
                $queryBuilder->expr()->eq('lpt_address.id', $queryBuilder->quoteIdentifier('deployment.lpt_address'))
            );
    }
}
