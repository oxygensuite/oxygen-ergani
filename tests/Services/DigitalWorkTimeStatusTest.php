<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace Tests\Services;

use DateTime;
use OxygenSuite\OxygenErgani\Http\Services\DigitalWorkTimeStatus;
use OxygenSuite\OxygenErgani\Responses\DigitalWorkTimeResponse;
use Tests\TestCase;

class DigitalWorkTimeStatusTest extends TestCase
{
    public function test_digital_work_time_status(): void
    {
        $service = new DigitalWorkTimeStatus('test-access-token');
        $service->getConfig()->setHandler($this->mockResponse(200, 'ex-base-08.json'));

        $response = $service->handle(0, '15/05/2026');

        $this->assertIsArray($response);
        $this->assertTrue($service->isSuccessful());
    }

    public function test_digital_work_time_status_response(): void
    {
        $service = new DigitalWorkTimeStatus('test-access-token');
        $service->getConfig()->setHandler($this->mockResponse(200, 'ex-base-08.json'));

        $entries = $service->handle(0, new DateTime('2026-05-15'));

        $this->assertCount(2, $entries);

        // Work entry
        $this->assertInstanceOf(DigitalWorkTimeResponse::class, $entries[0]);
        $this->assertSame(0, $entries[0]->branchAa);
        $this->assertSame('123456789', $entries[0]->afm);
        $this->assertNotNull($entries[0]->date);
        $this->assertSame('2026-05-15', $entries[0]->date->format('Y-m-d'));
        $this->assertSame('ΕΡΓ', $entries[0]->type);
        $this->assertSame('08:00', $entries[0]->hourFrom);
        $this->assertSame('16:00', $entries[0]->hourTo);
        $this->assertNull($entries[0]->extra);
        $this->assertSame(30, $entries[0]->breakMinutes);
        $this->assertTrue($entries[0]->breakInWork);

        // Regular leave entry
        $this->assertInstanceOf(DigitalWorkTimeResponse::class, $entries[1]);
        $this->assertSame('987654321', $entries[1]->afm);
        $this->assertSame('ΑΔΚΑΝ', $entries[1]->type);
        $this->assertNull($entries[1]->hourFrom);
        $this->assertNull($entries[1]->hourTo);
        $this->assertSame('2026 - 4', $entries[1]->extra);
        $this->assertNull($entries[1]->breakMinutes);
        $this->assertNull($entries[1]->breakInWork);
    }

    public function test_digital_work_time_status_single_entry_response(): void
    {
        $service = new DigitalWorkTimeStatus('test-access-token');
        $service->getConfig()->setHandler($this->mockResponse(200, 'ex-base-08-single.json'));

        $entries = $service->handle(0, '15/05/2026');

        $this->assertCount(1, $entries);
        $this->assertInstanceOf(DigitalWorkTimeResponse::class, $entries[0]);
        $this->assertSame('123456789', $entries[0]->afm);
    }

    public function test_digital_work_time_status_empty_response(): void
    {
        $service = new DigitalWorkTimeStatus('test-access-token');
        $service->getConfig()->setHandler($this->mockResponse(200, 'ex-base-08-empty.json'));

        $entries = $service->handle(0, '15/05/2026');

        $this->assertIsArray($entries);
        $this->assertCount(0, $entries);
    }

    public function test_digital_work_time_status_null_response(): void
    {
        $service = new DigitalWorkTimeStatus('test-access-token');
        $service->getConfig()->setHandler($this->mockResponse(200, 'null.json'));

        $entries = $service->handle(0, '15/05/2026');

        $this->assertIsArray($entries);
        $this->assertCount(0, $entries);
    }
}
