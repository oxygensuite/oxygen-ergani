<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace Tests\Documents\Overtime;

use OxygenSuite\OxygenErgani\Http\Documents\Overtime\OvertimeDocument;
use OxygenSuite\OxygenErgani\Http\Documents\Overtime\WorkTimeOvertime;
use OxygenSuite\OxygenErgani\Http\Documents\Overtime\WorkTimeOvertimeDrivers;
use OxygenSuite\OxygenErgani\Http\Documents\Overtime\WorkTimeOvertimeRetrospective;
use OxygenSuite\OxygenErgani\Models\Overtime\Overtime;
use OxygenSuite\OxygenErgani\Models\Overtime\OvertimeEmployee;
use Tests\TestCase;

class OvertimeTest extends TestCase
{
    private function createSampleOvertime(): Overtime
    {
        $employee = OvertimeEmployee::factory()->make([
            'f_afm' => '999999999',
            'f_amka' => '01010101010',
            'f_eponymo' => 'Doe',
            'f_onoma' => 'John',
            'f_date' => '06/06/2025',
            'f_from' => '17:00',
            'f_to' => '19:00',
            'f_cancellation' => '0',
            'f_step' => '11',
            'f_reason' => '002',
            'f_weekdates' => '5',
            'f_asee' => '',
        ]);

        return Overtime::factory()
            ->forBranch(0)
            ->withSepeService('21040')
            ->withEmployerOrganization('TEST')
            ->withPrimaryKad('0001')
            ->withComments('test')
            ->withRepresentative('111111111')
            ->withEmployees([$employee])
            ->make();
    }

    public function test_overtime_submit(): void
    {
        $document = new WorkTimeOvertime('test-access-token');
        $document->getConfig()->setHandler($this->mockResponse(200, 'work-card.json'));
        $document->handle($this->createSampleOvertime());

        $this->assertTrue($document->isSuccessful());
    }

    public function test_overtime_retrospective_submit(): void
    {
        $document = new WorkTimeOvertimeRetrospective('test-access-token');
        $document->getConfig()->setHandler($this->mockResponse(200, 'work-card.json'));
        $document->handle($this->createSampleOvertime());

        $this->assertTrue($document->isSuccessful());
    }

    public function test_overtime_drivers_submit(): void
    {
        $document = new WorkTimeOvertimeDrivers('test-access-token');
        $document->getConfig()->setHandler($this->mockResponse(200, 'work-card.json'));
        $document->handle($this->createSampleOvertime());

        $this->assertTrue($document->isSuccessful());
    }

    public function test_overtime_model(): void
    {
        $overtime = Overtime::make()
            ->setBranchCode('0')
            ->setRelatedProtocol('ΕΣΠ123')
            ->setRelatedDate('01/06/2025')
            ->setSepeService('21040')
            ->setEmployerOrganization('My Company')
            ->setPrimaryKad('0001')
            ->setBranchKad('0001')
            ->setKallikratisCode('90010101')
            ->setComments('Overtime declaration')
            ->setRepresentativeTin('123456789');

        $this->assertSame('0', $overtime->getBranchCode());
        $this->assertSame('ΕΣΠ123', $overtime->getRelatedProtocol());
        $this->assertSame('01/06/2025', $overtime->getRelatedDate());
        $this->assertSame('21040', $overtime->getSepeService());
        $this->assertSame('My Company', $overtime->getEmployerOrganization());
        $this->assertSame('0001', $overtime->getPrimaryKad());
        $this->assertSame('0001', $overtime->getBranchKad());
        $this->assertSame('90010101', $overtime->getKallikratisCode());
        $this->assertSame('Overtime declaration', $overtime->getComments());
        $this->assertSame('123456789', $overtime->getRepresentativeTin());
    }

    public function test_overtime_secondary_kads(): void
    {
        $overtime = Overtime::make()
            ->setSecondaryKad1('1001')
            ->setSecondaryKad2('1002')
            ->setSecondaryKad3('1003')
            ->setSecondaryKad4('1004');

        $this->assertSame('1001', $overtime->getSecondaryKad1());
        $this->assertSame('1002', $overtime->getSecondaryKad2());
        $this->assertSame('1003', $overtime->getSecondaryKad3());
        $this->assertSame('1004', $overtime->getSecondaryKad4());
    }

    public function test_overtime_employee_model(): void
    {
        $employee = OvertimeEmployee::make()
            ->setTin('999999999')
            ->setAmka('01010101010')
            ->setLastName('Doe')
            ->setFirstName('John')
            ->setDate('06/06/2025')
            ->setFromTime('17:00')
            ->setToTime('19:00')
            ->setCancellation('0')
            ->setStep('11')
            ->setReason('002')
            ->setWeekDates('5')
            ->setAsee('12345');

        $this->assertSame('999999999', $employee->getTin());
        $this->assertSame('01010101010', $employee->getAmka());
        $this->assertSame('Doe', $employee->getLastName());
        $this->assertSame('John', $employee->getFirstName());
        $this->assertSame('06/06/2025', $employee->getDate());
        $this->assertSame('17:00', $employee->getFromTime());
        $this->assertSame('19:00', $employee->getToTime());
        $this->assertSame('0', $employee->getCancellation());
        $this->assertSame('11', $employee->getStep());
        $this->assertSame('002', $employee->getReason());
        $this->assertSame('5', $employee->getWeekDates());
        $this->assertSame('12345', $employee->getAsee());
    }

    public function test_overtime_to_sorted_array(): void
    {
        $overtime = $this->createSampleOvertime();
        $array = $overtime->toSortedArray();

        $this->assertArrayHasKey('f_aa_pararthmatos', $array);
        $this->assertArrayHasKey('f_ypiresia_sepe', $array);
        $this->assertArrayHasKey('f_ergodotikh_organwsh', $array);
        $this->assertArrayHasKey('f_kad_kyria', $array);
        $this->assertArrayHasKey('f_kallikratis_pararthmatos', $array);
        $this->assertArrayHasKey('Ergazomenoi', $array);

        $employees = $array['Ergazomenoi']['OvertimeErgazomenosDate'];
        $this->assertCount(1, $employees);
        $this->assertSame('999999999', $employees[0]['f_afm']);
        $this->assertSame('01010101010', $employees[0]['f_amka']);
    }

    public function test_all_overtime_variants_extend_overtime_document(): void
    {
        $this->assertInstanceOf(
            OvertimeDocument::class,
            new WorkTimeOvertime('token'),
        );
        $this->assertInstanceOf(
            OvertimeDocument::class,
            new WorkTimeOvertimeRetrospective('token'),
        );
        $this->assertInstanceOf(
            OvertimeDocument::class,
            new WorkTimeOvertimeDrivers('token'),
        );
    }
}
