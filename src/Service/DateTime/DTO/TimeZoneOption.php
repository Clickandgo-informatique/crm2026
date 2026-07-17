<?php

namespace App\Service\DateTime\DTO;

class TimeZoneOption
{
    public function __construct(
        public readonly string $identifier,
        public readonly string $continent,
        public readonly string $city,
        public readonly string $label,
        public readonly int $offset
    ) {}
}
