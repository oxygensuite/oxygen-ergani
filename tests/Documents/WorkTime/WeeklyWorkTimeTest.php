<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace Tests\Documents\WorkTime;

use OxygenSuite\OxygenErgani\Enums\DayOfWeek;
use OxygenSuite\OxygenErgani\Enums\WorkTimeType;
use OxygenSuite\OxygenErgani\Http\Documents\WorkTime\WeeklyWorkTime;
use OxygenSuite\OxygenErgani\Models\WorkTime\WorkTime;
use OxygenSuite\OxygenErgani\Models\WorkTime\WorkTimeEmployee;
use OxygenSuite\OxygenErgani\Models\WorkTime\WorkTimeEntry;
use Tests\TestCase;

class WeeklyWorkTimeTest extends TestCase
{
    public function test_weekly_work_time_submit(): void
    {
        $workTime = WorkTime::make()
            ->setBranchCode('0')
            ->setComments('Weekly schedule')
            ->setFromDate('21/04/2025')
            ->setToDate('27/04/2025')
            ->addEmployee(
                WorkTimeEmployee::make()
                    ->setTin('888888888')
                    ->setFirstName('John')
                    ->setLastName('Doe')
                    ->setDay(DayOfWeek::MONDAY)
                    ->addAnalytics(
                        WorkTimeEntry::make()
                            ->setType(WorkTimeType::WORK)
                            ->setFromTime('09:00')
                            ->setToTime('17:00'),
                    ),
            );

        $weeklyWorkTime = new WeeklyWorkTime('test-access-token');
        $weeklyWorkTime->getConfig()->setHandler($this->mockResponse(200, 'work-card.json'));
        $weeklyWorkTime->handle($workTime);

        $this->assertTrue($weeklyWorkTime->isSuccessful());
    }

    public function test_weekly_work_time_with_multiple_days(): void
    {
        $workTime = WorkTime::make()
            ->setBranchCode('0')
            ->setComments('Full week schedule')
            ->setFromDate('21/04/2025')
            ->setToDate('27/04/2025')
            ->addEmployee(
                WorkTimeEmployee::make()
                    ->setTin('888888888')
                    ->setFirstName('John')
                    ->setLastName('Doe')
                    ->setDay(DayOfWeek::MONDAY)
                    ->addAnalytics(
                        WorkTimeEntry::make()
                            ->setType(WorkTimeType::WORK)
                            ->setFromTime('09:00')
                            ->setToTime('17:00'),
                    ),
            )
            ->addEmployee(
                WorkTimeEmployee::make()
                    ->setTin('888888888')
                    ->setFirstName('John')
                    ->setLastName('Doe')
                    ->setDay(DayOfWeek::TUESDAY)
                    ->addAnalytics(
                        WorkTimeEntry::make()
                            ->setType(WorkTimeType::WORK)
                            ->setFromTime('09:00')
                            ->setToTime('17:00'),
                    ),
            )
            ->addEmployee(
                WorkTimeEmployee::make()
                    ->setTin('888888888')
                    ->setFirstName('John')
                    ->setLastName('Doe')
                    ->setDay(DayOfWeek::SATURDAY)
                    ->addAnalytics(
                        WorkTimeEntry::make()
                            ->setType(WorkTimeType::DAY_OFF)
                            ->setFromTime('')
                            ->setToTime(''),
                    ),
            );

        $this->assertCount(3, $workTime->getEmployees());
        $this->assertSame('1', $workTime->getEmployee(0)->getDay());
        $this->assertSame('2', $workTime->getEmployee(1)->getDay());
        $this->assertSame('6', $workTime->getEmployee(2)->getDay());
    }

    public function test_day_of_week_enum(): void
    {
        $employee = WorkTimeEmployee::make()
            ->setTin('888888888')
            ->setFirstName('John')
            ->setLastName('Doe')
            ->setDay(DayOfWeek::WEDNESDAY);

        $this->assertSame('3', $employee->getDay());

        // Test with string value
        $employee2 = WorkTimeEmployee::make()
            ->setTin('777777777')
            ->setFirstName('Jane')
            ->setLastName('Doe')
            ->setDay('5');

        $this->assertSame('5', $employee2->getDay());
    }

    public function test_work_time_type_enum(): void
    {
        $analytics = WorkTimeEntry::make()
            ->setType(WorkTimeType::WORK)
            ->setFromTime('09:00')
            ->setToTime('17:00');

        $this->assertSame('ΕΡΓ', $analytics->getType());

        $overtimeAnalytics = WorkTimeEntry::make()
            ->setType(WorkTimeType::OVERTIME)
            ->setFromTime('17:00')
            ->setToTime('19:00');

        $this->assertSame('ΥΠ', $overtimeAnalytics->getType());

        // Test with string value
        $leaveAnalytics = WorkTimeEntry::make()
            ->setType('ΑΔ.ΚΑΝ')
            ->setFromTime('')
            ->setToTime('');

        $this->assertSame('ΑΔ.ΚΑΝ', $leaveAnalytics->getType());
    }

    public function test_weekly_model_to_sorted_array(): void
    {
        $workTime = WorkTime::make()
            ->setBranchCode('0')
            ->setRelatedProtocol('')
            ->setRelatedDate('')
            ->setComments('test')
            ->setFromDate('26/04/2022')
            ->setToDate('26/04/2022')
            ->addEmployee(
                WorkTimeEmployee::make()
                    ->setTin('999999999')
                    ->setFirstName('Test')
                    ->setLastName('User')
                    ->setDay('2')
                    ->addAnalytics(
                        WorkTimeEntry::make()
                            ->setType('ΕΡΓ')
                            ->setFromTime('06:00')
                            ->setToTime('14:00'),
                    ),
            );

        $array = $workTime->toSortedArray();

        $this->assertSame('0', $array['f_aa_pararthmatos']);
        $this->assertSame('26/04/2022', $array['f_from_date']);
        $this->assertSame('26/04/2022', $array['f_to_date']);

        $employee = $array['Ergazomenoi']['ErgazomenoiWTO'][0];
        $this->assertSame('999999999', $employee['f_afm']);
        $this->assertSame('2', $employee['f_day']);

        $analytics = $employee['ErgazomenosAnalytics']['ErgazomenosWTOAnalytics'][0];
        $this->assertSame('ΕΡΓ', $analytics['f_type']);
        $this->assertSame('06:00', $analytics['f_from']);
        $this->assertSame('14:00', $analytics['f_to']);
    }

    public function test_weekly_work_time_schema(): void
    {
        $weeklyWorkTime = new WeeklyWorkTime('test-access-token');
        $weeklyWorkTime->getConfig()->setHandler($this->mockResponse(200, 'wto-schema.json'));
        $weeklyWorkTime->schema();

        $this->assertTrue($weeklyWorkTime->isSuccessful());
    }
}
