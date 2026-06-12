<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace Tests\Services;

use OxygenSuite\OxygenErgani\Http\Services\WorkforceStatus;
use OxygenSuite\OxygenErgani\Responses\WorkforceStatusResponse;
use Tests\TestCase;

class WorkforceStatusTest extends TestCase
{
    public function test_workforce_status(): void
    {
        $service = new WorkforceStatus('test-access-token');
        $service->getConfig()->setHandler($this->mockResponse(200, 'ex-base-05.json'));

        $response = $service->handle();

        $this->assertIsArray($response);
        $this->assertTrue($service->isSuccessful());
    }

    public function test_workforce_status_response(): void
    {
        $service = new WorkforceStatus('test-access-token');
        $service->getConfig()->setHandler($this->mockResponse(200, 'ex-base-05.json'));

        $employees = $service->handle();

        $this->assertCount(2, $employees);

        // First employee
        $this->assertInstanceOf(WorkforceStatusResponse::class, $employees[0]);
        $this->assertSame('123456789', $employees[0]->afm);
        $this->assertSame('ΠΑΠΑΔΟΠΟΥΛΟΣ', $employees[0]->lastName);
        $this->assertSame('ΙΩΑΝΝΗΣ', $employees[0]->firstName);
        $this->assertSame('ΓΕΩΡΓΙΟΣ', $employees[0]->fatherName);
        $this->assertSame('ΜΑΡΙΑ', $employees[0]->motherName);
        $this->assertNotNull($employees[0]->birthDate);
        $this->assertSame('1990-01-01', $employees[0]->birthDate->format('Y-m-d'));
        $this->assertSame('ΑΝΔΡΑΣ (0)', $employees[0]->sex);
        $this->assertSame('048-ΕΛΛΑΔΑ', $employees[0]->nationality);
        $this->assertSame('ΕΓΓΑΜΟΣ/Η (1)', $employees[0]->maritalStatus);
        $this->assertSame(2, $employees[0]->childrenCount);
        $this->assertSame('8110-ΗΡΑΚΛΕΙΟΥ', $employees[0]->doy);
        $this->assertNull($employees[0]->unemploymentCode);
        $this->assertNull($employees[0]->amIka);
        $this->assertSame('01011990123456', $employees[0]->amka);
        $this->assertNull($employees[0]->minorBookNumber);

        // Identity document
        $this->assertSame('ΔAT-ΔΕΛΤΙΟ ΑΣΤΥΝΟΜΙΚΗΣ ΤΑΥΤΟΤΗΤΑΣ', $employees[0]->idType);
        $this->assertSame('ΑΒ123456', $employees[0]->idNumber);
        $this->assertSame('Α.Τ. ΑΘΗΝΩΝ', $employees[0]->idIssuingAuthority);
        $this->assertNotNull($employees[0]->idIssueDate);
        $this->assertSame('2015-03-15', $employees[0]->idIssueDate->format('Y-m-d'));
        $this->assertNotNull($employees[0]->idExpiryDate);

        // Residence permits
        $this->assertNull($employees[0]->residencePermitInstNumber);
        $this->assertNull($employees[0]->residencePermitApNumber);
        $this->assertNull($employees[0]->residencePermitVisaNumber);

        // Employment details
        $this->assertSame(0, $employees[0]->branchAa);
        $this->assertNotNull($employees[0]->effectiveDate);
        $this->assertSame('2025-01-01', $employees[0]->effectiveDate->format('Y-m-d'));
        $this->assertNotNull($employees[0]->hiringDate);
        $this->assertSame('2025-01-01', $employees[0]->hiringDate->format('Y-m-d'));
        $this->assertSame('ΥΠΑΛΛΗΛΟΣ ΓΡΑΦΕΙΟΥ', $employees[0]->specialtyDescription);
        $this->assertSame('ΥΠΑΛΛΗΛΟΣ (1)', $employees[0]->characterization);
        $this->assertSame('411090-ΥΠΑΛΛΗΛΟΣ ΓΡΑΦΕΙΟΥ', $employees[0]->step);
        $this->assertSame('5-ΗΜΕΡΗ (5)', $employees[0]->weekDays);
        $this->assertSame(5, $employees[0]->experienceYears);
        $this->assertSame('ΑΟΡΙΣΤΟΥ ΧΡΟΝΟΥ (0)', $employees[0]->employmentRelation);
        $this->assertSame('ΠΛΗΡΗΣ (0)', $employees[0]->employmentStatus);
        $this->assertSame('40.0', $employees[0]->weeklyHours);
        $this->assertSame('1200.00', $employees[0]->grossSalary);
        $this->assertSame('7.50', $employees[0]->hourlyWage);
        $this->assertSame('ΟΧΙ (0)', $employees[0]->trialPeriod);
        $this->assertSame('11-ΑΕΙ', $employees[0]->educationLevel);

        // Digital work time organization
        $this->assertSame('ΝΑΙ (1)', $employees[0]->digitalWorkTimeOrganization);
        $this->assertSame('40.0', $employees[0]->fullEmploymentHours);
        $this->assertSame(30, $employees[0]->breakMinutes);
        $this->assertSame('ΝΑΙ (1)', $employees[0]->breakWithinSchedule);
        $this->assertSame('ΝΑΙ (1)', $employees[0]->workingCard);
        $this->assertSame(15, $employees[0]->flexibleArrivalMinutes);

        // Last modification
        $this->assertNotNull($employees[0]->lastModifiedDate);
        $this->assertSame('2025-01-01', $employees[0]->lastModifiedDate->format('Y-m-d'));

        // Second employee
        $this->assertInstanceOf(WorkforceStatusResponse::class, $employees[1]);
        $this->assertSame('987654321', $employees[1]->afm);
        $this->assertSame('ΓΕΩΡΓΙΟΥ', $employees[1]->lastName);
        $this->assertSame('ΜΑΡΙΑ', $employees[1]->firstName);
        $this->assertSame('12345678', $employees[1]->amIka);
        $this->assertSame('241209-ΛΟΓΙΣΤΗΣ', $employees[1]->step);
        $this->assertSame('1500.00', $employees[1]->grossSalary);
        $this->assertSame(10, $employees[1]->experienceYears);
        $this->assertSame(0, $employees[1]->flexibleArrivalMinutes);
    }

    public function test_workforce_status_empty_response(): void
    {
        $service = new WorkforceStatus('test-access-token');
        $service->getConfig()->setHandler($this->mockResponse(200, 'ex-base-05-empty.json'));

        $employees = $service->handle();

        $this->assertIsArray($employees);
        $this->assertCount(0, $employees);
    }

    public function test_workforce_status_single_employee_response(): void
    {
        $service = new WorkforceStatus('test-access-token');
        $service->getConfig()->setHandler($this->mockResponse(200, 'ex-base-05-single.json'));

        $employees = $service->handle();

        $this->assertIsArray($employees);
        $this->assertCount(1, $employees);

        $this->assertInstanceOf(WorkforceStatusResponse::class, $employees[0]);
        $this->assertSame('123456789', $employees[0]->afm);
        $this->assertSame('ΠΑΠΑΔΟΠΟΥΛΟΣ', $employees[0]->lastName);
        $this->assertSame('ΙΩΑΝΝΗΣ', $employees[0]->firstName);
    }
}
