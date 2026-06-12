<?php

namespace Tests\Factories\WorkTime;

use OxygenSuite\OxygenErgani\Enums\DayOfWeek;
use OxygenSuite\OxygenErgani\Factories\Factory;
use OxygenSuite\OxygenErgani\Factories\WorkTime\WorkTimeEmployeeFactory;
use OxygenSuite\OxygenErgani\Factories\WorkTime\WorkTimeEntryFactory;
use OxygenSuite\OxygenErgani\Models\WorkTime\WorkTimeEmployee;
use OxygenSuite\OxygenErgani\Models\WorkTime\WorkTimeEntry;
use PHPUnit\Framework\TestCase;

class WorkTimeEmployeeFactoryTest extends TestCase
{
    protected function setUp(): void
    {
        Factory::resetFaker();
    }

    public function testCreatesWorkTimeEmployeeInstance(): void
    {
        $employee = WorkTimeEmployeeFactory::new()->make();

        $this->assertInstanceOf(WorkTimeEmployee::class, $employee);
    }

    public function testModelHasFactoryMethod(): void
    {
        $factory = WorkTimeEmployee::factory();

        $this->assertInstanceOf(WorkTimeEmployeeFactory::class, $factory);
    }

    public function testModelFactoryWithCount(): void
    {
        $employees = WorkTimeEmployee::factory(3)->make();

        $this->assertIsArray($employees);
        $this->assertCount(3, $employees);
        foreach ($employees as $employee) {
            $this->assertInstanceOf(WorkTimeEmployee::class, $employee);
        }
    }

    public function testDefinitionGeneratesRequiredFields(): void
    {
        $employee = WorkTimeEmployeeFactory::new()->make();

        $this->assertNotNull($employee->getTin());
        $this->assertNotNull($employee->getFirstName());
        $this->assertNotNull($employee->getLastName());
        $this->assertNotNull($employee->getDate());
        $this->assertNotEmpty($employee->getAnalytics());
    }

    public function testTinIsValidFormat(): void
    {
        $employee = WorkTimeEmployeeFactory::new()->make();
        $tin = $employee->getTin();

        $this->assertMatchesRegularExpression('/^\d{9}$/', $tin);
    }

    public function testDefaultIncludesOneEntry(): void
    {
        $employee = WorkTimeEmployeeFactory::new()->make();
        $entries = $employee->getAnalytics();

        $this->assertCount(1, $entries);
        $this->assertInstanceOf(WorkTimeEntry::class, $entries[0]);
    }

    public function testWithTinState(): void
    {
        $employee = WorkTimeEmployeeFactory::new()
            ->withTin('123456789')
            ->make();

        $this->assertEquals('123456789', $employee->getTin());
    }

    public function testWithNameState(): void
    {
        $employee = WorkTimeEmployeeFactory::new()
            ->withName('ΙΩΑΝΝΗΣ', 'ΠΑΠΑΔΟΠΟΥΛΟΣ')
            ->make();

        $this->assertEquals('ΙΩΑΝΝΗΣ', $employee->getFirstName());
        $this->assertEquals('ΠΑΠΑΔΟΠΟΥΛΟΣ', $employee->getLastName());
    }

    public function testForDateState(): void
    {
        $employee = WorkTimeEmployeeFactory::new()
            ->forDate('15/01/2025')
            ->make();

        $this->assertEquals('15/01/2025', $employee->getDate());
        $this->assertEquals('', $employee->getDay());
    }

    public function testForDayStateWithEnum(): void
    {
        $employee = WorkTimeEmployeeFactory::new()
            ->forDay(DayOfWeek::MONDAY)
            ->make();

        $this->assertEquals('1', $employee->getDay());
        $this->assertEquals('', $employee->getDate());
    }

    public function testForDayStateWithString(): void
    {
        $employee = WorkTimeEmployeeFactory::new()
            ->forDay('3')
            ->make();

        $this->assertEquals('3', $employee->getDay());
    }

    public function testOnMondayState(): void
    {
        $employee = WorkTimeEmployeeFactory::new()->onMonday()->make();
        $this->assertEquals('1', $employee->getDay());
    }

    public function testOnTuesdayState(): void
    {
        $employee = WorkTimeEmployeeFactory::new()->onTuesday()->make();
        $this->assertEquals('2', $employee->getDay());
    }

    public function testOnWednesdayState(): void
    {
        $employee = WorkTimeEmployeeFactory::new()->onWednesday()->make();
        $this->assertEquals('3', $employee->getDay());
    }

    public function testOnThursdayState(): void
    {
        $employee = WorkTimeEmployeeFactory::new()->onThursday()->make();
        $this->assertEquals('4', $employee->getDay());
    }

    public function testOnFridayState(): void
    {
        $employee = WorkTimeEmployeeFactory::new()->onFriday()->make();
        $this->assertEquals('5', $employee->getDay());
    }

    public function testOnSaturdayState(): void
    {
        $employee = WorkTimeEmployeeFactory::new()->onSaturday()->make();
        $this->assertEquals('6', $employee->getDay());
    }

    public function testOnSundayState(): void
    {
        $employee = WorkTimeEmployeeFactory::new()->onSunday()->make();
        $this->assertEquals('7', $employee->getDay());
    }

    public function testWithEntriesState(): void
    {
        $employee = WorkTimeEmployeeFactory::new()
            ->withEntries(3)
            ->make();

        $entries = $employee->getAnalytics();
        $this->assertCount(3, $entries);
        foreach ($entries as $entry) {
            $this->assertInstanceOf(WorkTimeEntry::class, $entry);
        }
    }

    public function testWithoutEntriesState(): void
    {
        $employee = WorkTimeEmployeeFactory::new()
            ->withoutEntries()
            ->make();

        $this->assertEmpty($employee->getAnalytics());
    }

    public function testWithSpecificEntriesState(): void
    {
        $customEntries = [
            WorkTimeEntryFactory::new()->work('08:00', '12:00')->make(),
            WorkTimeEntryFactory::new()->work('13:00', '17:00')->make(),
        ];

        $employee = WorkTimeEmployeeFactory::new()
            ->withSpecificEntries($customEntries)
            ->make();

        $entries = $employee->getAnalytics();
        $this->assertCount(2, $entries);
        $this->assertEquals('08:00', $entries[0]->getFromTime());
        $this->assertEquals('13:00', $entries[1]->getFromTime());
    }

    public function testChainingMultipleStates(): void
    {
        $employee = WorkTimeEmployeeFactory::new()
            ->withTin('999999999')
            ->withName('ΜΑΡΙΑ', 'ΚΩΝΣΤΑΝΤΙΝΟΥ')
            ->onMonday()
            ->withEntries(2)
            ->make();

        $this->assertEquals('999999999', $employee->getTin());
        $this->assertEquals('ΜΑΡΙΑ', $employee->getFirstName());
        $this->assertEquals('ΚΩΝΣΤΑΝΤΙΝΟΥ', $employee->getLastName());
        $this->assertEquals('1', $employee->getDay());
        $this->assertCount(2, $employee->getAnalytics());
    }

    public function testCountCreatesMultipleInstances(): void
    {
        $employees = WorkTimeEmployeeFactory::new()
            ->count(5)
            ->make();

        $this->assertIsArray($employees);
        $this->assertCount(5, $employees);
        foreach ($employees as $employee) {
            $this->assertInstanceOf(WorkTimeEmployee::class, $employee);
        }
    }

    public function testEachEmployeeHasIndependentEntries(): void
    {
        $employees = WorkTimeEmployeeFactory::new()
            ->withEntries(2)
            ->count(3)
            ->make();

        foreach ($employees as $employee) {
            $this->assertCount(2, $employee->getAnalytics());
        }
    }
}
