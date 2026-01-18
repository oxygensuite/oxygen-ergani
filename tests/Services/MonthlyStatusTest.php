<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace Tests\Services;

use OxygenSuite\OxygenErgani\Http\Services\MonthlyStatus;
use OxygenSuite\OxygenErgani\Responses\EmployeeStatusResponse;
use Tests\TestCase;

class MonthlyStatusTest extends TestCase
{
    public function test_monthly_status(): void
    {
        $service = new MonthlyStatus('test-access-token');
        $service->getConfig()->setHandler($this->mockResponse(200, 'ex-base-04.json'));

        $response = $service->handle(2025, 1);

        $this->assertIsArray($response);
        $this->assertTrue($service->isSuccessful());
    }

    public function test_monthly_status_response(): void
    {
        $service = new MonthlyStatus('test-access-token');
        $service->getConfig()->setHandler($this->mockResponse(200, 'ex-base-04.json'));

        $employees = $service->handle(2025, 1);

        $this->assertCount(2, $employees);

        $this->assertInstanceOf(EmployeeStatusResponse::class, $employees[0]);
        $this->assertSame('123456789', $employees[0]->afm);
        $this->assertSame('01011990123456', $employees[0]->amka);
        $this->assertSame('ΠΑΠΑΔΟΠΟΥΛΟΣ', $employees[0]->lastName);
        $this->assertSame('ΙΩΑΝΝΗΣ', $employees[0]->firstName);
        $this->assertSame('01/01/2025', $employees[0]->fromDate);
        $this->assertNull($employees[0]->toDate);
        $this->assertSame('Υπάλληλος Γραφείου', $employees[0]->specialty);
        $this->assertSame('1200,00', $employees[0]->salary);

        $this->assertInstanceOf(EmployeeStatusResponse::class, $employees[1]);
        $this->assertSame('987654321', $employees[1]->afm);
        $this->assertSame('ΓΕΩΡΓΙΟΥ', $employees[1]->lastName);
        $this->assertSame('ΜΑΡΙΑ', $employees[1]->firstName);
        $this->assertSame('Λογιστής', $employees[1]->specialty);
        $this->assertSame('1500,00', $employees[1]->salary);
    }
}
