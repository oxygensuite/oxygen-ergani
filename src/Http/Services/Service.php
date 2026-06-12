<?php

namespace OxygenSuite\OxygenErgani\Http\Services;

use OxygenSuite\OxygenErgani\Exceptions\ErganiException;
use OxygenSuite\OxygenErgani\Http\Client;

abstract class Service extends Client
{
    private const URI = 'WebServices/ExecuteService';

    /**
     * @param array<string, mixed> $parameters
     *
     * @throws ErganiException
     */
    protected function execute(array $parameters = []): static
    {
        $body = [
            'ServiceCode' => $this->serviceCode(),
            'Parameters' => $this->buildParameters($parameters),
        ];

        return $this->post(self::URI, $body);
    }

    /**
     * @param array<string, mixed> $parameters
     *
     * @return array<int, array<string, mixed>>
     */
    protected function buildParameters(array $parameters): array
    {
        $result = [];

        foreach ($parameters as $name => $value) {
            $result[] = [
                'ParameterName' => $name,
                'ParameterValue' => $value,
            ];
        }

        return $result;
    }

    abstract protected function serviceCode(): string;
}
