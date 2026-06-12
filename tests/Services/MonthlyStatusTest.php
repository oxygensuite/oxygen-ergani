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

        // First employee
        $this->assertInstanceOf(EmployeeStatusResponse::class, $employees[0]);
        $this->assertSame(12345, $employees[0]->employerId);
        $this->assertSame(0, $employees[0]->branchAa);
        $this->assertSame(2025, $employees[0]->year);
        $this->assertSame(1, $employees[0]->month);
        $this->assertSame('Εξαρτημένη', $employees[0]->employeeType);
        $this->assertSame('123456789', $employees[0]->afm);
        $this->assertSame('01011990123456', $employees[0]->amka);
        $this->assertSame('ΠΑΠΑΔΟΠΟΥΛΟΣ', $employees[0]->lastName);
        $this->assertSame('ΙΩΑΝΝΗΣ', $employees[0]->firstName);
        $this->assertSame('ΓΕΩΡΓΙΟΣ', $employees[0]->fatherName);
        $this->assertSame('ΜΑΡΙΑ', $employees[0]->motherName);
        $this->assertSame('Άντρας', $employees[0]->sex);
        $this->assertSame('048-ΕΛΛΑΔΑ', $employees[0]->nationality);
        $this->assertSame('Έγγαμος/η', $employees[0]->maritalStatus);
        $this->assertSame(2, $employees[0]->childrenCount);
        $this->assertNull($employees[0]->ama);
        $this->assertSame('11-ΑΕΙ', $employees[0]->educationLevel);
        $this->assertSame('Υπάλληλος', $employees[0]->characterization);
        $this->assertSame('Αορίστου Χρόνου', $employees[0]->employmentRelation);
        $this->assertSame('Πλήρης', $employees[0]->employmentStatus);
        $this->assertSame('411090-Υπάλληλος Γραφείου', $employees[0]->specialty);
        $this->assertSame('1.200,00', $employees[0]->salary);
        $this->assertSame('40,0', $employees[0]->weeklyHours);
        $this->assertSame('7,50', $employees[0]->hourlyWage);
        $this->assertNotNull($employees[0]->birthDate);
        $this->assertSame('1990-01-01', $employees[0]->birthDate->format('Y-m-d'));
        $this->assertNotNull($employees[0]->hiringDate);
        $this->assertSame('2025-01-01', $employees[0]->hiringDate->format('Y-m-d'));

        // Work days
        $this->assertSame(22, $employees[0]->workDays);
        $this->assertSame(0, $employees[0]->remoteWorkDays);
        $this->assertSame(8, $employees[0]->restDays);
        $this->assertSame(0, $employees[0]->nonWorkingDays);

        // Leave days
        $this->assertSame(0, $employees[0]->annualLeaveDays);
        $this->assertSame(1, $employees[0]->sicknessDays);

        // Work card
        $this->assertSame(22, $employees[0]->workCardDays);

        // Insurance totals
        $this->assertSame(0, $employees[0]->totalInsuredLeaveDays);
        $this->assertSame(1, $employees[0]->totalInsuredSicknessDays);

        // Second employee
        $this->assertInstanceOf(EmployeeStatusResponse::class, $employees[1]);
        $this->assertSame('987654321', $employees[1]->afm);
        $this->assertSame('ΓΕΩΡΓΙΟΥ', $employees[1]->lastName);
        $this->assertSame('ΜΑΡΙΑ', $employees[1]->firstName);
        $this->assertSame('Γυναίκα', $employees[1]->sex);
        $this->assertSame('12345678', $employees[1]->ama);
        $this->assertSame('241209-Λογιστής', $employees[1]->specialty);
        $this->assertSame('1.500,00', $employees[1]->salary);
        $this->assertSame(20, $employees[1]->workDays);
        $this->assertSame(2, $employees[1]->remoteWorkDays);
        $this->assertSame(1, $employees[1]->annualLeaveDays);
        $this->assertSame(1, $employees[1]->totalInsuredLeaveDays);
        $this->assertSame(0, $employees[1]->totalInsuredSicknessDays);
    }

    public function test_monthly_status_empty_response(): void
    {
        $service = new MonthlyStatus('test-access-token');
        $service->getConfig()->setHandler($this->mockResponse(200, 'ex-base-04-empty.json'));

        $employees = $service->handle(2025, 1);

        $this->assertIsArray($employees);
        $this->assertCount(0, $employees);
    }

    public function test_monthly_status_single_employee_response(): void
    {
        // API returns object (not array) when there's only one employee
        $service = new MonthlyStatus('test-access-token');
        $service->getConfig()->setHandler($this->mockResponse(200, 'ex-base-04-single.json'));

        $employees = $service->handle(2025, 1);

        $this->assertIsArray($employees);
        $this->assertCount(1, $employees);

        $this->assertInstanceOf(EmployeeStatusResponse::class, $employees[0]);
        $this->assertSame('123456789', $employees[0]->afm);
        $this->assertSame('ΠΑΠΑΔΟΠΟΥΛΟΣ', $employees[0]->lastName);
        $this->assertSame('ΙΩΑΝΝΗΣ', $employees[0]->firstName);
        $this->assertSame(22, $employees[0]->workDays);
    }
}
