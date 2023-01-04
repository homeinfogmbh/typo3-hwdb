<?php

namespace Homeinfo\SysMon2\Domain\Repository;

use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;

use Homeinfo\SysMon2\Domain\Model\CheckResults;

final class CheckResultsRepository
{    
    public function __construct(
        private readonly ConnectionPool $connectionPool
    ) {
    }

    public function findBySystem(int $system) {
        $queryBuilder = $this->connectionPool->getQueryBuilderForTable('checkresults');
        $result = $queryBuilder
            ->select('*')
            ->from('checkresults')
            ->where(
                $queryBuilder->expr()->eq(
                    'system',
                    $queryBuilder->createNamedParameter($system, Connection::PARAM_INT)
                )
            )
            ->executeQuery();

        foreach ($result->fetchAll() as &$record)
        {
            yield CheckResults::fromArray($record);
        }
    }
}
