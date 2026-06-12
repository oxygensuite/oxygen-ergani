<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace Tests\Documents\WorkTime;

use OxygenSuite\OxygenErgani\Enums\WorkTimeType;
use OxygenSuite\OxygenErgani\Http\Documents\WorkTime\DailyWorkTime;
use OxygenSuite\OxygenErgani\Http\Documents\WorkTime\DailyWorkTimeDrivers;
use OxygenSuite\OxygenErgani\Http\Documents\WorkTime\DailyWorkTimeRetrospective;
use OxygenSuite\OxygenErgani\Http\Documents\WorkTime\WorkTimeDocument;
use OxygenSuite\OxygenErgani\Http\Documents\WorkTime\WorkTimeLeave;
use OxygenSuite\OxygenErgani\Http\Documents\WorkTime\WorkTimeLeaveCorrection;
use OxygenSuite\OxygenErgani\Models\WorkTime\WorkTime;
use OxygenSuite\OxygenErgani\Models\WorkTime\WorkTimeEmployee;
use OxygenSuite\OxygenErgani\Models\WorkTime\WorkTimeEntry;
use Tests\TestCase;

class WorkTimeVariantsTest extends TestCase
{
    private function createSampleWorkTime(): WorkTime
    {
        return WorkTime::make()
            ->setBranchCode('0')
            ->setComments('Test submission')
            ->addEmployee(
                WorkTimeEmployee::make()
                    ->setTin('888888888')
                    ->setFirstName('John')
                    ->setLastName('Doe')
                    ->setDate('21/02/2025')
                    ->addAnalytics(
                        WorkTimeEntry::make()
                            ->setType(WorkTimeType::WORK)
                            ->setFromTime('09:00')
                            ->setToTime('17:00'),
                    ),
            );
    }

    public function test_daily_work_time_action(): void
    {
        $document = new DailyWorkTime('test-access-token');
        $document->getConfig()->setHandler($this->mockResponse(200, 'work-card.json'));
        $document->handle($this->createSampleWorkTime());

        $this->assertTrue($document->isSuccessful());
    }

    public function test_daily_work_time_retrospective_action(): void
    {
        $document = new DailyWorkTimeRetrospective('test-access-token');
        $document->getConfig()->setHandler($this->mockResponse(200, 'work-card.json'));
        $document->handle($this->createSampleWorkTime());

        $this->assertTrue($document->isSuccessful());
    }

    public function test_daily_work_time_drivers_action(): void
    {
        $document = new DailyWorkTimeDrivers('test-access-token');
        $document->getConfig()->setHandler($this->mockResponse(200, 'work-card.json'));
        $document->handle($this->createSampleWorkTime());

        $this->assertTrue($document->isSuccessful());
    }

    public function test_work_time_leave_action(): void
    {
        $workTime = WorkTime::make()
            ->setBranchCode('0')
            ->setComments('Leave declaration')
            ->addEmployee(
                WorkTimeEmployee::make()
                    ->setTin('888888888')
                    ->setFirstName('John')
                    ->setLastName('Doe')
                    ->setDate('24/04/2025')
                    ->addAnalytics(
                        WorkTimeEntry::make()
                            ->setType(WorkTimeType::LEAVE_REGULAR)
                            ->setFromTime('')
                            ->setToTime('')
                            ->setYear('2025')
                            ->setRequestedDays('016'),
                    ),
            );

        $document = new WorkTimeLeave('test-access-token');
        $document->getConfig()->setHandler($this->mockResponse(200, 'work-card.json'));
        $document->handle($workTime);

        $this->assertTrue($document->isSuccessful());
    }

    public function test_work_time_leave_correction_action(): void
    {
        $workTime = WorkTime::make()
            ->setBranchCode('0')
            ->setRelatedProtocol('ΕΣΠ123')
            ->setRelatedDate('20/04/2025')
            ->setComments('Leave correction')
            ->addEmployee(
                WorkTimeEmployee::make()
                    ->setTin('888888888')
                    ->setFirstName('John')
                    ->setLastName('Doe')
                    ->setDate('24/04/2025')
                    ->addAnalytics(
                        WorkTimeEntry::make()
                            ->setType(WorkTimeType::LEAVE_REGULAR)
                            ->setFromTime('')
                            ->setToTime('')
                            ->setYear('2025')
                            ->setRequestedDays('020'),
                    ),
            );

        $document = new WorkTimeLeaveCorrection('test-access-token');
        $document->getConfig()->setHandler($this->mockResponse(200, 'work-card.json'));
        $document->handle($workTime);

        $this->assertTrue($document->isSuccessful());
    }

    public function test_all_work_time_variants_extend_work_time_document(): void
    {
        $this->assertInstanceOf(
            WorkTimeDocument::class,
            new DailyWorkTime('token'),
        );
        $this->assertInstanceOf(
            WorkTimeDocument::class,
            new DailyWorkTimeRetrospective('token'),
        );
        $this->assertInstanceOf(
            WorkTimeDocument::class,
            new DailyWorkTimeDrivers('token'),
        );
        $this->assertInstanceOf(
            WorkTimeDocument::class,
            new WorkTimeLeave('token'),
        );
        $this->assertInstanceOf(
            WorkTimeDocument::class,
            new WorkTimeLeaveCorrection('token'),
        );
    }
}
