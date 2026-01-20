<?php

namespace Tests\Factories\WorkTime;

use OxygenSuite\OxygenErgani\Enums\WorkTimeType;
use OxygenSuite\OxygenErgani\Factories\Factory;
use OxygenSuite\OxygenErgani\Factories\WorkTime\WorkTimeEntryFactory;
use OxygenSuite\OxygenErgani\Models\WorkTime\WorkTimeEntry;
use PHPUnit\Framework\TestCase;

class WorkTimeEntryFactoryTest extends TestCase
{
    protected function setUp(): void
    {
        Factory::resetFaker();
    }

    public function testCreatesWorkTimeEntryInstance(): void
    {
        $entry = WorkTimeEntryFactory::new()->make();

        $this->assertInstanceOf(WorkTimeEntry::class, $entry);
    }

    public function testModelHasFactoryMethod(): void
    {
        $factory = WorkTimeEntry::factory();

        $this->assertInstanceOf(WorkTimeEntryFactory::class, $factory);
    }

    public function testModelFactoryWithCount(): void
    {
        $entries = WorkTimeEntry::factory(3)->make();

        $this->assertIsArray($entries);
        $this->assertCount(3, $entries);
        foreach ($entries as $entry) {
            $this->assertInstanceOf(WorkTimeEntry::class, $entry);
        }
    }

    public function testDefinitionGeneratesDefaultWorkType(): void
    {
        $entry = WorkTimeEntryFactory::new()->make();

        $this->assertEquals(WorkTimeType::WORK->value, $entry->getType());
        $this->assertEquals('09:00', $entry->getFromTime());
        $this->assertEquals('17:00', $entry->getToTime());
    }

    public function testWorkState(): void
    {
        $entry = WorkTimeEntryFactory::new()
            ->work('08:00', '16:00')
            ->make();

        $this->assertEquals(WorkTimeType::WORK->value, $entry->getType());
        $this->assertEquals('08:00', $entry->getFromTime());
        $this->assertEquals('16:00', $entry->getToTime());
    }

    public function testWorkStateWithDefaultTimes(): void
    {
        $entry = WorkTimeEntryFactory::new()
            ->work()
            ->make();

        $this->assertEquals(WorkTimeType::WORK->value, $entry->getType());
        $this->assertEquals('09:00', $entry->getFromTime());
        $this->assertEquals('17:00', $entry->getToTime());
    }

    public function testOvertimeState(): void
    {
        $entry = WorkTimeEntryFactory::new()
            ->overtime('17:00', '20:00')
            ->make();

        $this->assertEquals(WorkTimeType::OVERTIME->value, $entry->getType());
        $this->assertEquals('17:00', $entry->getFromTime());
        $this->assertEquals('20:00', $entry->getToTime());
    }

    public function testOvertimeStateWithDefaultTimes(): void
    {
        $entry = WorkTimeEntryFactory::new()
            ->overtime()
            ->make();

        $this->assertEquals(WorkTimeType::OVERTIME->value, $entry->getType());
        $this->assertEquals('17:00', $entry->getFromTime());
        $this->assertEquals('19:00', $entry->getToTime());
    }

    public function testDayOffState(): void
    {
        $entry = WorkTimeEntryFactory::new()
            ->dayOff()
            ->make();

        $this->assertEquals(WorkTimeType::REST->value, $entry->getType());
        $this->assertEquals('', $entry->getFromTime());
        $this->assertEquals('', $entry->getToTime());
    }

    public function testLeaveRegularState(): void
    {
        $entry = WorkTimeEntryFactory::new()
            ->leaveRegular('2025', '015')
            ->make();

        $this->assertEquals(WorkTimeType::LEAVE_REGULAR->value, $entry->getType());
        $this->assertEquals('', $entry->getFromTime());
        $this->assertEquals('', $entry->getToTime());
        $this->assertEquals('2025', $entry->getYear());
        $this->assertEquals('015', $entry->getRequestedDays());
    }

    public function testWithTypeStateWithEnum(): void
    {
        $entry = WorkTimeEntryFactory::new()
            ->withType(WorkTimeType::OVERTIME)
            ->make();

        $this->assertEquals(WorkTimeType::OVERTIME->value, $entry->getType());
    }

    public function testWithTypeStateWithString(): void
    {
        $entry = WorkTimeEntryFactory::new()
            ->withType('ΑΔ.ΚΑΝ')
            ->make();

        $this->assertEquals('ΑΔ.ΚΑΝ', $entry->getType());
    }

    public function testWithTimeRangeState(): void
    {
        $entry = WorkTimeEntryFactory::new()
            ->withTimeRange('06:00', '14:00')
            ->make();

        $this->assertEquals('06:00', $entry->getFromTime());
        $this->assertEquals('14:00', $entry->getToTime());
    }

    public function testForLeaveState(): void
    {
        $entry = WorkTimeEntryFactory::new()
            ->forLeave('2025', '020')
            ->make();

        $this->assertEquals('', $entry->getFromTime());
        $this->assertEquals('', $entry->getToTime());
        $this->assertEquals('2025', $entry->getYear());
        $this->assertEquals('020', $entry->getRequestedDays());
    }

    public function testMorningShiftState(): void
    {
        $entry = WorkTimeEntryFactory::new()
            ->morningShift()
            ->make();

        $this->assertEquals(WorkTimeType::WORK->value, $entry->getType());
        $this->assertEquals('06:00', $entry->getFromTime());
        $this->assertEquals('14:00', $entry->getToTime());
    }

    public function testAfternoonShiftState(): void
    {
        $entry = WorkTimeEntryFactory::new()
            ->afternoonShift()
            ->make();

        $this->assertEquals(WorkTimeType::WORK->value, $entry->getType());
        $this->assertEquals('14:00', $entry->getFromTime());
        $this->assertEquals('22:00', $entry->getToTime());
    }

    public function testNightShiftState(): void
    {
        $entry = WorkTimeEntryFactory::new()
            ->nightShift()
            ->make();

        $this->assertEquals(WorkTimeType::WORK->value, $entry->getType());
        $this->assertEquals('22:00', $entry->getFromTime());
        $this->assertEquals('06:00', $entry->getToTime());
    }

    public function testChainingMultipleStates(): void
    {
        $entry = WorkTimeEntryFactory::new()
            ->withType(WorkTimeType::LEAVE_REGULAR)
            ->forLeave('2025', '010')
            ->make();

        $this->assertEquals(WorkTimeType::LEAVE_REGULAR->value, $entry->getType());
        $this->assertEquals('2025', $entry->getYear());
        $this->assertEquals('010', $entry->getRequestedDays());
    }

    public function testCountCreatesMultipleInstances(): void
    {
        $entries = WorkTimeEntryFactory::new()
            ->count(5)
            ->make();

        $this->assertIsArray($entries);
        $this->assertCount(5, $entries);
        foreach ($entries as $entry) {
            $this->assertInstanceOf(WorkTimeEntry::class, $entry);
        }
    }

    public function testStateCanOverrideDefinition(): void
    {
        $entry = WorkTimeEntryFactory::new()
            ->state(['f_type' => 'CUSTOM'])
            ->make();

        $this->assertEquals('CUSTOM', $entry->getType());
    }

    public function testMakeCanOverrideState(): void
    {
        $entry = WorkTimeEntryFactory::new()
            ->state(['f_from' => '08:00'])
            ->make(['f_from' => '07:00']);

        $this->assertEquals('07:00', $entry->getFromTime());
    }
}
