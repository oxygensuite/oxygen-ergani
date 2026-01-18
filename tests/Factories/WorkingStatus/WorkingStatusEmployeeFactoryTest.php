<?php

namespace Tests\Factories\WorkingStatus;

use OxygenSuite\OxygenErgani\Factories\Factory;
use OxygenSuite\OxygenErgani\Factories\WorkingStatus\WorkingStatusEmployeeFactory;
use OxygenSuite\OxygenErgani\Models\WorkingStatus\WorkingStatusEmployee;
use PHPUnit\Framework\TestCase;

class WorkingStatusEmployeeFactoryTest extends TestCase
{
    protected function setUp(): void
    {
        Factory::resetFaker();
    }

    public function testCreatesEmployeeInstance(): void
    {
        $employee = WorkingStatusEmployeeFactory::new()->make();

        $this->assertInstanceOf(WorkingStatusEmployee::class, $employee);
    }

    public function testModelHasFactoryMethod(): void
    {
        $factory = WorkingStatusEmployee::factory();

        $this->assertInstanceOf(WorkingStatusEmployeeFactory::class, $factory);
    }

    public function testDefinitionGeneratesRequiredFields(): void
    {
        $employee = WorkingStatusEmployeeFactory::new()->make();

        $this->assertNotNull($employee->getAfm());
        $this->assertNotNull($employee->getFirstName());
        $this->assertNotNull($employee->getLastName());
        $this->assertNotNull($employee->getDate());
    }

    public function testAfmIsValid(): void
    {
        $employee = WorkingStatusEmployeeFactory::new()->make();
        $afm = $employee->getAfm();

        $this->assertMatchesRegularExpression('/^\d{9}$/', $afm);
    }

    public function testWithTinState(): void
    {
        $employee = WorkingStatusEmployeeFactory::new()
            ->withTin('123456789')
            ->make();

        $this->assertEquals('123456789', $employee->getAfm());
    }

    public function testWithNameState(): void
    {
        $employee = WorkingStatusEmployeeFactory::new()
            ->withName('ΙΩΑΝΝΗΣ', 'ΠΑΠΑΔΟΠΟΥΛΟΣ')
            ->make();

        $this->assertEquals('ΙΩΑΝΝΗΣ', $employee->getFirstName());
        $this->assertEquals('ΠΑΠΑΔΟΠΟΥΛΟΣ', $employee->getLastName());
    }

    public function testForDateState(): void
    {
        $employee = WorkingStatusEmployeeFactory::new()
            ->forDate('15/01/2025')
            ->make();

        $this->assertEquals('15/01/2025', $employee->getDate());
    }

    public function testFiveDayWeekState(): void
    {
        $employee = WorkingStatusEmployeeFactory::new()
            ->fiveDayWeek()
            ->make();

        $this->assertEquals('5', $employee->getWeekDays());
    }

    public function testSixDayWeekState(): void
    {
        $employee = WorkingStatusEmployeeFactory::new()
            ->sixDayWeek()
            ->make();

        $this->assertEquals('6', $employee->getWeekDays());
    }

    public function testWithDigitalOrganizationState(): void
    {
        $employee = WorkingStatusEmployeeFactory::new()
            ->withDigitalOrganization(true)
            ->make();

        $this->assertEquals('1', $employee->getWorkingTimeDigitalOrganization());

        $employee2 = WorkingStatusEmployeeFactory::new()
            ->withDigitalOrganization(false)
            ->make();

        $this->assertEquals('0', $employee2->getWorkingTimeDigitalOrganization());
    }

    public function testWithWorkingCardState(): void
    {
        $employee = WorkingStatusEmployeeFactory::new()
            ->withWorkingCard(true)
            ->make();

        $this->assertEquals('1', $employee->getWorkingCard());

        $employee2 = WorkingStatusEmployeeFactory::new()
            ->withWorkingCard(false)
            ->make();

        $this->assertEquals('0', $employee2->getWorkingCard());
    }

    public function testWithFullEmploymentHoursState(): void
    {
        $employee = WorkingStatusEmployeeFactory::new()
            ->withFullEmploymentHours(38.0)
            ->make();

        $this->assertEquals(38.0, $employee->getFullEmploymentHours());
    }

    public function testWithFlexibleArrivalState(): void
    {
        $employee = WorkingStatusEmployeeFactory::new()
            ->withFlexibleArrival(15)
            ->make();

        $this->assertEquals('15', $employee->getFlexibleArrivalMinutes());
    }

    public function testWithBreakState(): void
    {
        $employee = WorkingStatusEmployeeFactory::new()
            ->withBreak(45, true)
            ->make();

        $this->assertEquals('45', $employee->getBreakMinutes());
        $this->assertEquals('1', $employee->getBreakWithinSchedule());

        $employee2 = WorkingStatusEmployeeFactory::new()
            ->withBreak(60, false)
            ->make();

        $this->assertEquals('60', $employee2->getBreakMinutes());
        $this->assertEquals('0', $employee2->getBreakWithinSchedule());
    }

    public function testPartTimeState(): void
    {
        $employee = WorkingStatusEmployeeFactory::new()
            ->partTime(25.0)
            ->make();

        $this->assertEquals(25.0, $employee->getFullEmploymentHours());
    }

    public function testChainingMultipleStates(): void
    {
        $employee = WorkingStatusEmployeeFactory::new()
            ->withTin('999999999')
            ->withName('ΜΑΡΙΑ', 'ΓΕΩΡΓΙΟΥ')
            ->forDate('20/01/2025')
            ->partTime(20.0)
            ->sixDayWeek()
            ->make();

        $this->assertEquals('999999999', $employee->getAfm());
        $this->assertEquals('ΜΑΡΙΑ', $employee->getFirstName());
        $this->assertEquals('ΓΕΩΡΓΙΟΥ', $employee->getLastName());
        $this->assertEquals('20/01/2025', $employee->getDate());
        $this->assertEquals(20.0, $employee->getFullEmploymentHours());
        $this->assertEquals('6', $employee->getWeekDays());
    }

    public function testCountCreatesMultipleInstances(): void
    {
        $employees = WorkingStatusEmployeeFactory::new()
            ->count(3)
            ->make();

        $this->assertIsArray($employees);
        $this->assertCount(3, $employees);
        foreach ($employees as $employee) {
            $this->assertInstanceOf(WorkingStatusEmployee::class, $employee);
        }
    }
}
