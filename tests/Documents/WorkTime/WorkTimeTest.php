<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace Tests\Documents\WorkTime;

use OxygenSuite\OxygenErgani\Http\Documents\WorkTime\DailyWorkTime;
use OxygenSuite\OxygenErgani\Models\WorkTime\WorkTime;
use OxygenSuite\OxygenErgani\Models\WorkTime\WorkTimeEmployee;
use OxygenSuite\OxygenErgani\Models\WorkTime\WorkTimeEntry;
use Tests\TestCase;

class WorkTimeTest extends TestCase
{
    public function test_work_card_submit(): void
    {
        $entry = WorkTimeEntry::factory()->make([
            'f_type' => 'ΕΡΓ',
            'f_from' => '09:00',
            'f_to' => '17:00',
        ]);

        $employee = WorkTimeEmployee::factory()
            ->forDate('21/02/2025')
            ->withSpecificEntries([$entry])
            ->make();

        $card = WorkTime::factory()
            ->forBranch('01')
            ->asCorrection('ΕΣΠ27', '21/02/2025')
            ->forDate('21/02/2025')
            ->withComments('test-comments')
            ->withSpecificEmployees([$employee])
            ->make();

        $wto = new DailyWorkTime('test-access-token');
        $wto->getConfig()->setHandler($this->mockResponse(200, 'work-card.json'));
        $wto->handle($card);

        $this->assertTrue($wto->isSuccessful());
    }

    public function test_work_card_schema(): void
    {
        $workCard = new DailyWorkTime('test-access-token');
        $workCard->getConfig()->setHandler($this->mockResponse(200, 'wto-schema.json'));
        $workCard->schema();

        $this->assertTrue($workCard->isSuccessful());
    }

    public function test_work_card_pdf(): void
    {
        $workCard = new DailyWorkTime('test-access-token');
        $workCard->getConfig()->setHandler($this->mockResponse(200, 'pdf.txt'));
        $workCard->pdf('ΕΥΣ92', 19800410);

        $this->assertTrue($workCard->isSuccessful());
    }

    public function test_card_model(): void
    {
        $card = WorkTime::make()
            ->setBranchCode('01')
            ->setRelatedProtocol('ΕΣΠ27')
            ->setRelatedDate('21/02/2025')
            ->setComments('test-comments')
            ->setFromDate('21/02/2025')
            ->setToDate('21/02/2025')
            ->addEmployee(
                WorkTimeEmployee::make()
                    ->setTin('888888888')
                    ->setFirstName('John')
                    ->setLastName('Doe')
                    ->setDate('21/02/2025')
                    ->addAnalytics(
                        WorkTimeEntry::make()
                            ->setType('type')
                            ->setFromTime('09:00')
                            ->setToTime('14:00'),
                    )
                    ->addAnalytics(
                        WorkTimeEntry::make()
                            ->setType('type')
                            ->setFromTime('18:00')
                            ->setToTime('21:00'),
                    ),
            )
            ->addEmployee(
                WorkTimeEmployee::make()
                    ->setTin('777777777')
                    ->setFirstName('Jane')
                    ->setLastName('Doe')
                    ->setDate('21/02/2025')
                    ->addAnalytics(
                        WorkTimeEntry::make()
                            ->setType('type')
                            ->setFromTime('09:00')
                            ->setToTime('17:00'),
                    ),
            );

        $this->assertSame('01', $card->getBranchCode());
        $this->assertSame('ΕΣΠ27', $card->getRelatedProtocol());
        $this->assertSame('21/02/2025', $card->getRelatedDate());
        $this->assertSame('test-comments', $card->getComments());
        $this->assertSame('21/02/2025', $card->getFromDate());
        $this->assertSame('21/02/2025', $card->getToDate());
        $this->assertIsArray($card->getEmployees());
        $this->assertCount(2, $card->getEmployees());

        $this->assertSame('888888888', $card->getEmployee(0)->getTin());
        $this->assertSame('John', $card->getEmployee(0)->getFirstName());
        $this->assertSame('Doe', $card->getEmployee(0)->getLastName());
        $this->assertSame('21/02/2025', $card->getEmployee(0)->getDate());
        $this->assertIsArray($card->getEmployee(0)->getAnalytics());
        $this->assertCount(2, $card->getEmployee(0)->getAnalytics());
        $this->assertSame('type', $card->getEmployee(0)->getAnalytic(0)->getType());
        $this->assertSame('09:00', $card->getEmployee(0)->getAnalytic(0)->getFromTime());
        $this->assertSame('14:00', $card->getEmployee(0)->getAnalytic(0)->getToTime());
        $this->assertSame('type', $card->getEmployee(0)->getAnalytic(1)->getType());
        $this->assertSame('18:00', $card->getEmployee(0)->getAnalytic(1)->getFromTime());
        $this->assertSame('21:00', $card->getEmployee(0)->getAnalytic(1)->getToTime());

        $this->assertSame('777777777', $card->getEmployee(1)->getTin());
        $this->assertSame('Jane', $card->getEmployee(1)->getFirstName());
        $this->assertSame('Doe', $card->getEmployee(1)->getLastName());
        $this->assertSame('21/02/2025', $card->getEmployee(1)->getDate());
        $this->assertIsArray($card->getEmployee(1)->getAnalytics());
        $this->assertCount(1, $card->getEmployee(1)->getAnalytics());
        $this->assertSame('type', $card->getEmployee(1)->getAnalytic(0)->getType());
        $this->assertSame('09:00', $card->getEmployee(1)->getAnalytic(0)->getFromTime());
        $this->assertSame('17:00', $card->getEmployee(1)->getAnalytic(0)->getToTime());
    }

    public function test_work_time_entry_leave_fields(): void
    {
        $analytics = WorkTimeEntry::make()
            ->setType('ΑΔ.ΚΑΝ')
            ->setFromTime('')
            ->setToTime('')
            ->setYear('2025')
            ->setRequestedDays('016');

        $this->assertSame('ΑΔ.ΚΑΝ', $analytics->getType());
        $this->assertSame('', $analytics->getFromTime());
        $this->assertSame('', $analytics->getToTime());
        $this->assertSame('2025', $analytics->getYear());
        $this->assertSame('016', $analytics->getRequestedDays());

        $array = $analytics->toSortedArray();
        $this->assertSame(['f_type', 'f_from', 'f_to', 'f_year', 'f_req_days'], array_keys($array));
    }

    public function test_work_time_with_leave_declaration(): void
    {
        $workTime = WorkTime::make()
            ->setBranchCode('0')
            ->setComments('Annual leave declaration')
            ->addEmployee(
                WorkTimeEmployee::make()
                    ->setTin('999999999')
                    ->setFirstName('John')
                    ->setLastName('Doe')
                    ->setDate('15/07/2025')
                    ->addAnalytics(
                        WorkTimeEntry::make()
                            ->setType('ΑΔ.ΚΑΝ')
                            ->setFromTime('')
                            ->setToTime('')
                            ->setYear('2025')
                            ->setRequestedDays('020'),
                    ),
            );

        $employee = $workTime->getEmployee(0);
        $leaveAnalytics = $employee->getAnalytic(0);

        $this->assertSame('ΑΔ.ΚΑΝ', $leaveAnalytics->getType());
        $this->assertSame('2025', $leaveAnalytics->getYear());
        $this->assertSame('020', $leaveAnalytics->getRequestedDays());
    }
}
