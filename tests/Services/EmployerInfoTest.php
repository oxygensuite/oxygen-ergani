<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace Tests\Services;

use OxygenSuite\OxygenErgani\Http\Services\EmployerInfo;
use Tests\TestCase;

class EmployerInfoTest extends TestCase
{
    public function test_employer_info(): void
    {
        $service = new EmployerInfo('test-access-token');
        $service->getConfig()->setHandler($this->mockResponse(200, 'ex-base-01.json'));
        $service->handle();

        $this->assertTrue($service->isSuccessful());
    }

    public function test_employer_info_response(): void
    {
        $service = new EmployerInfo('test-access-token');
        $service->getConfig()->setHandler($this->mockResponse(200, 'ex-base-01.json'));

        $employer = $service->handle();

        $this->assertSame('12345', $employer->id);
        $this->assertSame('12345678', $employer->tin);
        $this->assertSame('ERGANI A.E', $employer->name);
        $this->assertSame('12345678', $employer->ame);
        $this->assertFalse($employer->isInCardSector);
    }
}
