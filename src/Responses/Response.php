<?php

namespace OxygenSuite\OxygenErgani\Responses;

use OxygenSuite\OxygenErgani\Traits\HasAttributes;

abstract class Response
{
    use HasAttributes;

    public function __construct(mixed $attributes = [])
    {
        if (!is_array($attributes) || empty($attributes)) {
            return;
        }

        $this->attributes = $attributes;
        $this->processData();
    }

    abstract protected function processData(): void;

    /**
     * Get the response as an array of public properties.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $result = [];
        $reflection = new \ReflectionClass($this);

        foreach ($reflection->getProperties(\ReflectionProperty::IS_PUBLIC) as $property) {
            $result[$property->getName()] = $property->getValue($this);
        }

        return $result;
    }
}
