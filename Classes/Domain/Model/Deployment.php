<?php

namespace Homeinfo\SysMon2\Domain\Model;

use DateTime;

use Homeinfo\mdb\Domain\Model\Address;

final class CheckResults
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

    public static function fromJoinedRecord(array $record): Self {
        return Self::fromArray(
            $record,
            Address::fromArray([
                'id' => $record['address'],
                'street' => $record['address_street'],
                'house_number' => $record['address_house_number'],
                'zip_code' => $record['address_zip_code'],
                'city' => $record['address_city'],
                'district' => $record['address_district'],
            ]),
            (($lpt_address_id = $record['lpt_address']) === null) ? null : Address::fromArray([
                'id' => $lpt_address_id,
                'street' => $record['lpt_address_street'],
                'house_number' => $record['lpt_address_house_number'],
                'zip_code' => $record['lpt_address_zip_code'],
                'city' => $record['lpt_address_city'],
                'district' => $record['lpt_address_district'],
            ]),
        );
    }

    public static function fromArray(array $array, Address $address, ?Address $lpt_address): Self {
        return new self(
            $array['id'],
            $array['customer'],
            $array['type'],
            $array['connection'],
            $address,
            $lpt_address,
            $array['annotation'],
            $array['testing'],
            (($created = $array['created']) === null) ? null : new DateTime($created),
            (($construction_site_preparation_feedback = $array['construction_site_preparation_feedback']) === null) ? null : new DateTime($construction_site_preparation_feedback),
            (($internet_connection = $array['internet_connection']) === null) ? null : new DateTime($internet_connection),
            $array['technician_annotation'],
        );
    }
}