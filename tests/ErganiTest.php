<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace Tests;

use OxygenSuite\OxygenErgani\Ergani;
use OxygenSuite\OxygenErgani\Http\ClientConfig;
use OxygenSuite\OxygenErgani\Http\Services\ParameterLookup;
use OxygenSuite\OxygenErgani\Responses\ParameterCollection;
use OxygenSuite\OxygenErgani\Responses\ParameterResponse;

class ErganiTest extends TestCase
{
    public function test_get_parameters(): void
    {
        $config = (new ClientConfig())->setHandler($this->mockResponse(200, 'ex-base-03.json'));
        $ergani = new Ergani('test-access-token', config: $config);

        $parameters = $ergani->getParameters(ParameterLookup::WORK_TIME_TYPE);

        $this->assertInstanceOf(ParameterCollection::class, $parameters);
        $this->assertCount(42, $parameters);

        // Verify we can access parameters
        $workType = $parameters->find('ΕΡΓ');
        $this->assertInstanceOf(ParameterResponse::class, $workType);
        $this->assertSame('ΕΡΓΑΣΙΑ', $workType->description);
    }

    public function test_get_parameters_for_dropdown(): void
    {
        $config = (new ClientConfig())->setHandler($this->mockResponse(200, 'ex-base-03.json'));
        $ergani = new Ergani('test-access-token', config: $config);

        $dropdown = $ergani->getParameters(ParameterLookup::WORK_TIME_TYPE)->toDropdown();

        $this->assertIsArray($dropdown);
        $this->assertSame('ΕΡΓΑΣΙΑ', $dropdown['ΕΡΓ']);
        $this->assertSame('ΤΗΛΕΡΓΑΣΙΑ', $dropdown['ΤΗΛ']);
    }
}
