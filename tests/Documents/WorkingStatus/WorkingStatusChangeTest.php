<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace Tests\Documents\WorkingStatus;

use OxygenSuite\OxygenErgani\Http\Documents\WorkingStatus\WorkingStatusChange;
use OxygenSuite\OxygenErgani\Models\WorkingStatus\WorkingStatus;
use OxygenSuite\OxygenErgani\Models\WorkingStatus\WorkingStatusEmployee;
use Tests\TestCase;

class WorkingStatusChangeTest extends TestCase
{
    public function test_working_status_change_submit(): void
    {
        $workingStatus = WorkingStatus::make()
            ->setBranchCode(0)
            ->setRelatedProtocol('')
            ->setRelatedDate('')
            ->setComments('test-comments')
            ->addEmployee(
                WorkingStatusEmployee::make()
                    ->setAfm('999999999')
                    ->setLastName('LASTNAME')
                    ->setFirstName('FIRSTNAME')
                    ->setDate('16/05/2022')
                    ->setWorkingTimeDigitalOrganization(true)
                    ->setFullEmploymentHours(40.0)
                    ->setWeekDays(5)
                    ->setFlexibleArrivalMinutes(40)
                    ->setWorkingCard(true)
                    ->setBreakMinutes(30)
                    ->setBreakWithinSchedule(true),
            );

        $document = new WorkingStatusChange('test-access-token');
        $document->getConfig()->setHandler($this->mockResponse(200, 'working-status-change.json'));
        $response = $document->handle($workingStatus);

        $this->assertIsArray($response);
        $this->assertSame('150', $response[0]->id);
        $this->assertSame('ΜΕΤ150', $response[0]->protocol);
        $this->assertSame('16/05/2022 10:30', $response[0]->submissionDate->format('d/m/Y H:i'));
    }

    public function test_working_status_model(): void
    {
        $employee1 = WorkingStatusEmployee::make()
            ->setAfm('999999999')
            ->setLastName('KAPOIOS')
            ->setFirstName('ALLOS')
            ->setDate('16/05/2022')
            ->setWorkingTimeDigitalOrganization('1')
            ->setFullEmploymentHours(40.0)
            ->setWeekDays('5')
            ->setFlexibleArrivalMinutes(40)
            ->setWorkingCard('1')
            ->setBreakMinutes(30)
            ->setBreakWithinSchedule('1');

        $employee2 = WorkingStatusEmployee::make()
            ->setAfm('888888888')
            ->setLastName('KAPOIA')
            ->setFirstName('ALLH')
            ->setDate('16/05/2022')
            ->setWorkingTimeDigitalOrganization(false)
            ->setFullEmploymentHours(20.0)
            ->setWeekDays(6)
            ->setFlexibleArrivalMinutes(0)
            ->setWorkingCard(false)
            ->setBreakMinutes(15)
            ->setBreakWithinSchedule(false);

        $workingStatus = WorkingStatus::make()
            ->setBranchCode(0)
            ->setRelatedProtocol('ΠΡΩ123')
            ->setRelatedDate('15/05/2022')
            ->setComments('Test comments')
            ->setEmployees([$employee1, $employee2]);

        $this->assertSame(0, $workingStatus->getBranchCode());
        $this->assertSame('ΠΡΩ123', $workingStatus->getRelatedProtocol());
        $this->assertSame('15/05/2022', $workingStatus->getRelatedDate());
        $this->assertSame('Test comments', $workingStatus->getComments());
        $this->assertCount(2, $workingStatus->getEmployees());

        // Test first employee
        $emp1 = $workingStatus->getEmployee(0);
        $this->assertSame('999999999', $emp1->getAfm());
        $this->assertSame('KAPOIOS', $emp1->getLastName());
        $this->assertSame('ALLOS', $emp1->getFirstName());
        $this->assertSame('16/05/2022', $emp1->getDate());
        $this->assertSame('1', $emp1->getWorkingTimeDigitalOrganization());
        $this->assertSame(40.0, $emp1->getFullEmploymentHours());
        $this->assertSame('5', $emp1->getWeekDays());
        $this->assertSame(40, $emp1->getFlexibleArrivalMinutes());
        $this->assertSame('1', $emp1->getWorkingCard());
        $this->assertSame(30, $emp1->getBreakMinutes());
        $this->assertSame('1', $emp1->getBreakWithinSchedule());

        // Test second employee
        $emp2 = $workingStatus->getEmployee(1);
        $this->assertSame('888888888', $emp2->getAfm());
        $this->assertSame('KAPOIA', $emp2->getLastName());
        $this->assertSame('ALLH', $emp2->getFirstName());
        $this->assertSame('0', $emp2->getWorkingTimeDigitalOrganization());
        $this->assertSame(20.0, $emp2->getFullEmploymentHours());
        $this->assertSame('6', $emp2->getWeekDays());
        $this->assertSame(0, $emp2->getFlexibleArrivalMinutes());
        $this->assertSame('0', $emp2->getWorkingCard());
        $this->assertSame(15, $emp2->getBreakMinutes());
        $this->assertSame('0', $emp2->getBreakWithinSchedule());
    }

    public function test_working_status_to_sorted_array(): void
    {
        $workingStatus = WorkingStatus::make()
            ->setBranchCode(0)
            ->setRelatedProtocol('')
            ->setRelatedDate('')
            ->setComments('')
            ->addEmployee(
                WorkingStatusEmployee::make()
                    ->setAfm('999999999')
                    ->setLastName('LASTNAME')
                    ->setFirstName('FIRSTNAME')
                    ->setDate('16/05/2022')
                    ->setWorkingTimeDigitalOrganization('1')
                    ->setFullEmploymentHours(40.0)
                    ->setWeekDays('5')
                    ->setFlexibleArrivalMinutes(40)
                    ->setWorkingCard('1')
                    ->setBreakMinutes(30)
                    ->setBreakWithinSchedule('1'),
            );

        $array = $workingStatus->toSortedArray();

        $this->assertArrayHasKey('f_aa_pararthmatos', $array);
        $this->assertArrayHasKey('f_rel_protocol', $array);
        $this->assertArrayHasKey('f_rel_date', $array);
        $this->assertArrayHasKey('f_comments', $array);
        $this->assertArrayHasKey('Ergazomenoi', $array);
        $this->assertArrayHasKey('Ergazomenos', $array['Ergazomenoi']);

        $employee = $array['Ergazomenoi']['Ergazomenos'][0];
        $this->assertArrayHasKey('f_afm', $employee);
        $this->assertArrayHasKey('f_eponymo', $employee);
        $this->assertArrayHasKey('f_onoma', $employee);
        $this->assertArrayHasKey('f_date', $employee);
        $this->assertArrayHasKey('f_working_time_digital_organization', $employee);
        $this->assertArrayHasKey('f_full_employment_hours', $employee);
        $this->assertArrayHasKey('f_week_days', $employee);
        $this->assertArrayHasKey('f_euelikto_wrario_minutes', $employee);
        $this->assertArrayHasKey('f_working_card', $employee);
        $this->assertArrayHasKey('f_dialeimma_minutes', $employee);
        $this->assertArrayHasKey('f_dialeimma_entos_wrariou', $employee);
    }
}
