<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace Tests\Services;

use OxygenSuite\OxygenErgani\Http\Services\ParameterLookup;
use OxygenSuite\OxygenErgani\Responses\ParameterCollection;
use OxygenSuite\OxygenErgani\Responses\ParameterResponse;
use Tests\TestCase;

class ParameterLookupTest extends TestCase
{
    public function test_parameter_lookup(): void
    {
        $service = new ParameterLookup('test-access-token');
        $service->getConfig()->setHandler($this->mockResponse(200, 'ex-base-03.json'));

        $response = $service->handle(ParameterLookup::WORK_TIME_TYPE);

        $this->assertInstanceOf(ParameterCollection::class, $response);
        $this->assertTrue($service->isSuccessful());
    }

    public function test_parameter_lookup_response(): void
    {
        $service = new ParameterLookup('test-access-token');
        $service->getConfig()->setHandler($this->mockResponse(200, 'ex-base-03.json'));

        $parameters = $service->handle(ParameterLookup::WORK_TIME_TYPE);

        $this->assertCount(42, $parameters);

        // Work type - using find() by code
        $workType = $parameters->find('ΕΡΓ');
        $this->assertInstanceOf(ParameterResponse::class, $workType);
        $this->assertSame('ΕΡΓ', $workType->code);
        $this->assertSame('ΕΡΓΑΣΙΑ', $workType->description);
        $this->assertSame('ΕΡΓΑΣΙΑ-ΕΡΓΑΣΙΑ', $workType->extra);

        // Remote work - using array access
        $remoteWork = $parameters['ΤΗΛ'];
        $this->assertInstanceOf(ParameterResponse::class, $remoteWork);
        $this->assertSame('ΤΗΛ', $remoteWork->code);
        $this->assertSame('ΤΗΛΕΡΓΑΣΙΑ', $remoteWork->description);
        $this->assertSame('ΕΡΓΑΣΙΑ-ΕΡΓΑΣΙΑ', $remoteWork->extra);

        // Rest/Day off
        $rest = $parameters->find('ΑΝ');
        $this->assertInstanceOf(ParameterResponse::class, $rest);
        $this->assertSame('ΑΝ', $rest->code);
        $this->assertSame('ΑΝΑΠΑΥΣΗ/ΡΕΠΟ', $rest->description);
        $this->assertSame('ΕΡΓΑΣΙΑ-ΧΩΡΙΣ ΕΡΓΑΣΙΑ ΑΝΑΠΑΥΣΗ ΡΕΠΟ', $rest->extra);

        // Regular leave
        $leave = $parameters->find('ΑΔΚΑΝ');
        $this->assertInstanceOf(ParameterResponse::class, $leave);
        $this->assertSame('ΑΔΚΑΝ', $leave->code);
        $this->assertSame('Κανονική άδεια', $leave->description);
        $this->assertSame('ΑΔΕΙΑ-ΑΔΕΙΑ', $leave->extra);

        // Overtime
        $overtime = $parameters->find('ΥΠ');
        $this->assertSame('ΥΠ', $overtime->code);
        $this->assertSame('ΥΠΕΡΩΡΙΑ', $overtime->description);
        $this->assertSame('ΥΠΕΡΩΡΙΑ-ΕΡΓΑΣΙΑ', $overtime->extra);
    }

    public function test_parameter_lookup_collection_features(): void
    {
        $service = new ParameterLookup('test-access-token');
        $service->getConfig()->setHandler($this->mockResponse(200, 'ex-base-03.json'));

        $parameters = $service->handle(ParameterLookup::WORK_TIME_TYPE);

        // Test has()
        $this->assertTrue($parameters->has('ΕΡΓ'));
        $this->assertFalse($parameters->has('NONEXISTENT'));

        // Test search()
        $results = $parameters->search('ΕΡΓΑΣΙΑ');
        $this->assertInstanceOf(ParameterCollection::class, $results);
        $this->assertGreaterThan(0, count($results));
        $this->assertTrue($results->has('ΕΡΓ')); // ΕΡΓΑΣΙΑ
        $this->assertTrue($results->has('ΤΗΛ')); // ΤΗΛΕΡΓΑΣΙΑ

        // Test toDropdown()
        $dropdown = $parameters->toDropdown();
        $this->assertIsArray($dropdown);
        $this->assertSame('ΕΡΓΑΣΙΑ', $dropdown['ΕΡΓ']);
        $this->assertSame('ΤΗΛΕΡΓΑΣΙΑ', $dropdown['ΤΗΛ']);

        // Test first() and last()
        $first = $parameters->first();
        $this->assertInstanceOf(ParameterResponse::class, $first);

        $last = $parameters->last();
        $this->assertInstanceOf(ParameterResponse::class, $last);

        // Test iteration
        $count = 0;
        foreach ($parameters as $code => $param) {
            $this->assertIsString($code);
            $this->assertInstanceOf(ParameterResponse::class, $param);
            $count++;
        }
        $this->assertSame(42, $count);
    }

    public function test_parameter_lookup_constants(): void
    {
        $this->assertSame('WorkTimeType', ParameterLookup::WORK_TIME_TYPE);
        $this->assertSame('Sepe', ParameterLookup::SEPE);
        $this->assertSame('Oaed', ParameterLookup::OAED);
        $this->assertSame('Nationality', ParameterLookup::NATIONALITY);
        $this->assertSame('Bank', ParameterLookup::BANK);
        $this->assertSame('WorkCardDelayReason', ParameterLookup::WORK_CARD_DELAY_REASON);
    }
}
