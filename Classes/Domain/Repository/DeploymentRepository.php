<?php

namespace Homeinfo\hwdb\Domain\Repository;

use Generator;

use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;

use Homeinfo\mdb\Domain\Repository\AddressRepository;

use Homeinfo\hwdb\Domain\Model\Deployment;

class DeploymentRepository
{
    private AddressRepository $addressRepository;

    public function __construct(
        private readonly ConnectionPool $connectionPool
    ) {
        $this->addressRepository = new AddressRepository($connectionPool);
    }

    public function findById(int $id): array {
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

    public function list(): Generator {
        foreach ($this->select()->executeQuery()->fetchAll() as &$record)
        {
            yield Deployment::fromArray(
                $record,
                $this->addressRepository->get($record['address']),
                (($lpt_address = $record['lpt_address']) === null) ? null : $this->addressRepository->get($lpt_address)
            );
        }
    }

    private function select(): QueryBuilder {
        return ($queryBuilder = $this->connectionPool->getQueryBuilderForTable('deployment'))
            ->select('*')
            ->from('deployment');
    }
}
