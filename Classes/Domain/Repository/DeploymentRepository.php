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
    )
    {
        $this->addressRepository = new AddressRepository($connectionPool);
    }

    public function findById(int $id): array
    {
        $addressMap = $this->addressRepository->getMap();

        return Deployment::fromArrayAndAddressMap(
            ($queryBuilder = $this->select())
            ->where(
                $queryBuilder->expr()->eq(
                    'id',
                    $queryBuilder->createNamedParameter($id, Connection::PARAM_INT)
                )
            )
            ->executeQuery()
            ->fetch(),
            $addressMap
        );
    }

    public function findByCustomerId(int $customerId): Generator
    {
        $addressMap = $this->addressRepository->getMap();

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
            yield Deployment::fromArrayAndAddressMap($record, $addressMap);
    }

    public function list(): Generator
    {
        $addressMap = $this->addressRepository->getMap();

        foreach ($this->select()->executeQuery()->fetchAll() as &$record)
            yield Deployment::fromArrayAndAddressMap($record, $addressMap);
    }

    private function select(): QueryBuilder
    {
        return ($queryBuilder = $this->connectionPool->getQueryBuilderForTable('deployment'))
            ->select(
                'deployment.*',
                // Address
                'address.street as address_street',
                'address.house_number as address_house_number',
                'address.zip_code as address_zip_code',
                'address.city as address_city',
                'address.district as address_district',
                // LPT address
                'lpt_address.street as lpt_address_street',
                'lpt_address.house_number as lpt_address_house_number',
                'lpt_address.zip_code as lpt_address_zip_code',
                'lpt_address.city as lpt_address_city',
                'lpt_address.district as lpt_address_district',
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
