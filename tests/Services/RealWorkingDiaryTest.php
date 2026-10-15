<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace Tests\Services;

use DateTime;
use OxygenSuite\OxygenErgani\Http\Services\RealWorkingDiary;
use OxygenSuite\OxygenErgani\Responses\RealWorkingResponse;
use Tests\TestCase;

class RealWorkingDiaryTest extends TestCase
{
    public function test_real_working_diary(): void
    {
        $service = new RealWorkingDiary('test-access-token');
        $service->getConfig()->setHandler($this->mockResponse(200, 'ex-base-07.json'));

        $response = $service->handle(0, '15/05/2026');

        $this->assertIsArray($response);
        $this->assertTrue($service->isSuccessful());
    }

    public function test_real_working_diary_response(): void
    {
        $service = new RealWorkingDiary('test-access-token');
        $service->getConfig()->setHandler($this->mockResponse(200, 'ex-base-07.json'));

        $entries = $service->handle(0, new DateTime('2026-05-15'));

        $this->assertCount(2, $entries);

        $this->assertInstanceOf(RealWorkingResponse::class, $entries[0]);
        $this->assertSame(0, $entries[0]->branchAa);
        $this->assertSame('123456789', $entries[0]->afm);
        $this->assertNotNull($entries[0]->date);
        $this->assertSame('2026-05-15', $entries[0]->date->format('Y-m-d'));
        $this->assertSame('08:00', $entries[0]->hourFrom);
        $this->assertSame('16:00', $entries[0]->hourTo);
        $this->assertFalse($entries[0]->endsOnNextDay);

        $this->assertInstanceOf(RealWorkingResponse::class, $entries[1]);
        $this->assertSame('987654321', $entries[1]->afm);
        $this->assertSame('22:00', $entries[1]->hourFrom);
        $this->assertSame('06:00', $entries[1]->hourTo);
        $this->assertTrue($entries[1]->endsOnNextDay);
    }

    public function test_real_working_diary_single_entry_response(): void
    {
        $service = new RealWorkingDiary('test-access-token');
        $service->getConfig()->setHandler($this->mockResponse(200, 'ex-base-07-single.json'));

        $entries = $service->handle(0, '15/05/2026');

        $this->assertCount(1, $entries);
        $this->assertInstanceOf(RealWorkingResponse::class, $entries[0]);
        $this->assertSame('123456789', $entries[0]->afm);
    }

    public function test_real_working_diary_empty_response(): void
    {
        $service = new RealWorkingDiary('test-access-token');
        $service->getConfig()->setHandler($this->mockResponse(200, 'ex-base-07-empty.json'));

        $entries = $service->handle(0, '15/05/2026');

        $this->assertIsArray($entries);
        $this->assertCount(0, $entries);
    }

    public function test_real_working_diary_null_response(): void
    {
        $service = new RealWorkingDiary('test-access-token');
        $service->getConfig()->setHandler($this->mockResponse(200, 'null.json'));

        $entries = $service->handle(0, '15/05/2026');

        $this->assertIsArray($entries);
        $this->assertCount(0, $entries);
    }
}
