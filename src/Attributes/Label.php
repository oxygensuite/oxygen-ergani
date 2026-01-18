<?php

namespace OxygenSuite\OxygenErgani\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS_CONSTANT)]
readonly class Label
{
    public function __construct(
        public string $english,
        public string $greek,
    ) {}
}
