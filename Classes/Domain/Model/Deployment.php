<?php

namespace Homeinfo\hwdb\Domain\Model;

use DateTime;

use Homeinfo\mdb\Domain\Model\Address;

final class Deployment
{
    function __construct(
        public readonly int $id,
        public readonly int $customer,
        public readonly string $type,
        public readonly string $connection,
        public readonly Address $address,
        public readonly ?Address $lpt_address,
        public readonly ?string $annotation,
        public readonly bool $testing,
        public readonly ?DateTime $created,
        // Checklist
        public readonly ?DateTime $construction_site_preparation_feedback,
        public readonly ?DateTime $internet_connection,
        public readonly ?string $technician_annotation,
    )
    {
    }

    public static function fromArray(array $array, string $addressPrefix = 'address_', string $lptAddressPrefix = 'lpt_address_'): Self
    {
        return new self(
            $array['id'],
            $array['customer'],
            $array['type'],
            $array['connection'],
            Address::fromPrefixedFields($array, $addressPrefix),
            Address::fromPrefixedFields($array, $lptAddressPrefix),
            $array['annotation'],
            $array['testing'],
            (($created = $array['created']) === null) ? null : new DateTime($created),
            (($construction_site_preparation_feedback = $array['construction_site_preparation_feedback']) === null) ? null : new DateTime($construction_site_preparation_feedback),
            (($internet_connection = $array['internet_connection']) === null) ? null : new DateTime($internet_connection),
            $array['technician_annotation'],
        );
    }
}