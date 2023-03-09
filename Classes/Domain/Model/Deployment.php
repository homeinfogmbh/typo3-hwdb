<?php

namespace Homeinfo\hwdb\Domain\Model;

use DateTime;
use Generator;

use Homeinfo\mdb\Domain\Model\Address;

final class Deployment
{
    private const FIELDS = [
        'id',
        'customer',
        'type',
        'connection',
        'address',
        'lpt_address',
        'annotation',
        'testing',
        'created',
        // Checklist
        'construction_site_preparation_feedback',
        'internet_connection',
        'technician_annotation'
    ];

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

    public static function fromPrefixedFields(array $array, string $prefix): ?Self
    {
        $deploymentFields = [];

        foreach ($array as $key => $value)
            if (str_starts_with($key, $prefix))
                $deploymentFields[substr($key, strlen($prefix))] = $value;

        return Self::fromArray($deploymentFields, $prefix . 'address_', $prefix . 'lpt_address_');
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

    public static function aliasedFields(string $alias): Generator
    {
        foreach (Self::FIELDS as $field)
            yield $alias . '.' . $field . ' as ' . $alias . '_' . $field;
    }
}