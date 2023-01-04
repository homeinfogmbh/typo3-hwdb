<?php

namespace Homeinfo\hwdb\Domain\Repository;

use Generator;

use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;

use Homeinfo\mdb\Domain\Model\Deployment;

class DeploymentRepository
{
    public function __construct(
        private readonly ConnectionPool $connectionPool
    ) {
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
            yield Deployment::fromJoinedRecord($record);
        }
    }

    private function select(): QueryBuilder {
        return ($queryBuilder = $this->connectionPool->getQueryBuilderForTable('deployment'))
            ->select(
                'deployment.*',
                'address.street AS address_street',
                'address.house_number AS address_house_number',
                'address.zip_code AS address_zip_code',
                'address.city AS address_city',
                'address.district AS address_district',
                'lpt_address.street AS address_street',
                'lpt_address.house_number AS lpt_address_house_number',
                'lpt_address.zip_code AS lpt_address_zip_code',
                'lpt_address.city AS lpt_address_city',
                'lpt_address.district AS lpt_address_district',
            )
            ->from('deployment')
            ->join(
                'deployment',
                'address',
                'address',
                $queryBuilder->expr()->eq('address.id', $queryBuilder->quoteIdentifier('deplyoment.address'))
            )
            ->leftJoin(
                'deployment',
                'address',
                'lpt_address',
                $queryBuilder->expr()->eq('lpt_address.id', $queryBuilder->quoteIdentifier('deployment.lpt_address'))
            );
    }
}
