<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace Tests\Documents\Overtime;

use OxygenSuite\OxygenErgani\Enums\WorkTimeType;
use OxygenSuite\OxygenErgani\Http\Documents\Overtime\OvertimeDocument;
use OxygenSuite\OxygenErgani\Http\Documents\Overtime\WorkTimeOvertime;
use OxygenSuite\OxygenErgani\Http\Documents\Overtime\WorkTimeOvertimeDrivers;
use OxygenSuite\OxygenErgani\Http\Documents\Overtime\WorkTimeOvertimeRetrospective;
use OxygenSuite\OxygenErgani\Models\Overtime\Overtime;
use OxygenSuite\OxygenErgani\Models\Overtime\OvertimeEmployee;
use OxygenSuite\OxygenErgani\Models\WorkTime\WorkTime;
use OxygenSuite\OxygenErgani\Models\WorkTime\WorkTimeEmployee;
use OxygenSuite\OxygenErgani\Models\WorkTime\WorkTimeEntry;
use Tests\TestCase;

class OvertimeTest extends TestCase
{
    private function createSampleOvertime(): WorkTime
    {
        return WorkTime::make()
            ->setBranchCode('0')
            ->setRelatedProtocol('')
            ->setRelatedDate('')
            ->setComments('f_comments')
            ->setFromDate('07/04/2025')
            ->setToDate('08/04/2025')
            ->addEmployee(
                WorkTimeEmployee::make()
                    ->setTin('151619217')
                    ->setLastName('f_eponymo')
                    ->setFirstName('f_onoma')
                    ->setDate('07/04/2025')
                    ->addAnalytics(
                        WorkTimeEntry::make()
                            ->setType(WorkTimeType::OVERTIME)
                            ->setFromTime('08:00')
                            ->setToTime('10:00'),
                    ),
            );
    }

    /**
     * ERGANI II overtime uses the WTO structure (ERGANI's WTOOv.json sample).
     */
    public function test_overtime_payload_matches_ergani_sample(): void
    {
        $document = new WorkTimeOvertime('test-access-token');
        $mock = $this->mockResponse(200, 'work-card.json');
        $document->getConfig()->setHandler($mock);
        $document->handle($this->createSampleOvertime());

        $this->assertStringEndsWith('Documents/WTOOv', $mock->getLastRequest()->getUri()->getPath());
        $body = json_decode((string) $mock->getLastRequest()->getBody(), true);

        $this->assertSame(['WTOS'], array_keys($body));
        $wto = $body['WTOS']['WTO'][0];
        $this->assertSame(['f_aa_pararthmatos', 'f_rel_protocol', 'f_rel_date', 'f_comments', 'f_from_date', 'f_to_date', 'Ergazomenoi'], array_keys($wto));
        $employee = $wto['Ergazomenoi']['ErgazomenoiWTO'][0];
        $this->assertSame(['f_afm', 'f_eponymo', 'f_onoma', 'f_date', 'ErgazomenosAnalytics'], array_keys($employee));
        $this->assertSame(
            [['f_type' => 'ΥΠ', 'f_from' => '08:00', 'f_to' => '10:00']],
            $employee['ErgazomenosAnalytics']['ErgazomenosWTOAnalytics'],
        );
    }

    public function test_overtime_submit(): void
    {
        $document = new WorkTimeOvertime('test-access-token');
        $mock = $this->mockResponse(200, 'work-card.json');
        $document->getConfig()->setHandler($mock);
        $document->handle($this->createSampleOvertime());

        $this->assertTrue($document->isSuccessful());
        $this->assertStringEndsWith('Documents/WTOOv', $mock->getLastRequest()->getUri()->getPath());
    }

    public function test_overtime_retrospective_submit(): void
    {
        $document = new WorkTimeOvertimeRetrospective('test-access-token');
        $mock = $this->mockResponse(200, 'work-card.json');
        $document->getConfig()->setHandler($mock);
        $document->handle($this->createSampleOvertime());

        $this->assertTrue($document->isSuccessful());
        $this->assertStringEndsWith('Documents/WTOOvA', $mock->getLastRequest()->getUri()->getPath());
    }

    public function test_overtime_drivers_submit(): void
    {
        $document = new WorkTimeOvertimeDrivers('test-access-token');
        $mock = $this->mockResponse(200, 'work-card.json');
        $document->getConfig()->setHandler($mock);
        $document->handle($this->createSampleOvertime());

        $this->assertTrue($document->isSuccessful());
        $this->assertStringEndsWith('Documents/WTOOvD', $mock->getLastRequest()->getUri()->getPath());
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
