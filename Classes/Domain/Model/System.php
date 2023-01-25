<?php

namespace Homeinfo\hwdb\Domain\Model;

use DateTime;

final class System
{
    function __construct(
        public readonly int $id,
        public readonly ?int $group,
        public readonly ?int $deployment,
        public readonly ?int $dataset,
        public readonly ?int $openvpn,
        public readonly ?string $ipv6address,
        public readonly ?string $pubkey,
        public readonly DateTime $created,
        public readonly ?DateTime $configured,
        public readonly bool $fitted,
        public readonly string $operating_system,
        public readonly ?bool $monitor,
        public readonly ?string $serial_number,
        public readonly ?string $model,
        public readonly ?DateTime $last_sync,
        public readonly bool $updating,
    )
    {
    }

    public static function fromArray(array $array): Self
    {
        return new self(
            $array['id'],
            $array['group'],
            $array['deployment'],
            $array['dataset'],
            $array['openvpn'],
            inet_ntop($array['ipv6address']),
            $array['pubkey'],
            new DateTime($array['created']),
            (($configured = $array['configured']) === null) ? null : new DateTime($configured),
            $array['fitted'],
            $array['operating_system'],
            $array['monitor'],
            $array['serial_number'],
            $array['model'],
            (($last_sync = $array['last_sync']) === null) ? null : new DateTime($last_sync),
            $array['updating'],
        );
    }
}