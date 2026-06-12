<?php

namespace Tests\Factories\WorkTime;

use OxygenSuite\OxygenErgani\Factories\Factory;
use OxygenSuite\OxygenErgani\Factories\WorkTime\WorkTimeEmployeeFactory;
use OxygenSuite\OxygenErgani\Factories\WorkTime\WorkTimeFactory;
use OxygenSuite\OxygenErgani\Models\WorkTime\WorkTime;
use OxygenSuite\OxygenErgani\Models\WorkTime\WorkTimeEmployee;
use PHPUnit\Framework\TestCase;

class WorkTimeFactoryTest extends TestCase
{
    protected function setUp(): void
    {
        Factory::resetFaker();
    }

    public function testCreatesWorkTimeInstance(): void
    {
        $workTime = WorkTimeFactory::new()->make();

        $this->assertInstanceOf(WorkTime::class, $workTime);
    }

    public function testModelHasFactoryMethod(): void
    {
        $factory = WorkTime::factory();

        $this->assertInstanceOf(WorkTimeFactory::class, $factory);
    }

    public function testModelFactoryWithCount(): void
    {
        $workTimes = WorkTime::factory(3)->make();

        $this->assertIsArray($workTimes);
        $this->assertCount(3, $workTimes);
        foreach ($workTimes as $workTime) {
            $this->assertInstanceOf(WorkTime::class, $workTime);
        }
    }

    public function testDefinitionGeneratesRequiredFields(): void
    {
        $workTime = WorkTimeFactory::new()->make();

        $this->assertNotNull($workTime->getBranchCode());
        $this->assertNotNull($workTime->getFromDate());
        $this->assertNotNull($workTime->getToDate());
        $this->assertNotEmpty($workTime->getEmployees());
    }

    public function testBranchCodeIsString(): void
    {
        $workTime = WorkTimeFactory::new()->make();
        $branchCode = $workTime->getBranchCode();

        $this->assertIsString($branchCode);
    }

    public function testDefaultIncludesOneEmployee(): void
    {
        $workTime = WorkTimeFactory::new()->make();
        $employees = $workTime->getEmployees();

        $this->assertCount(1, $employees);
        $this->assertInstanceOf(WorkTimeEmployee::class, $employees[0]);
    }

    public function testForBranchState(): void
    {
        $workTime = WorkTimeFactory::new()
            ->forBranch(5)
            ->make();

        $this->assertEquals('5', $workTime->getBranchCode());
    }

    public function testMainBranchState(): void
    {
        $workTime = WorkTimeFactory::new()
            ->mainBranch()
            ->make();

        $this->assertEquals('0', $workTime->getBranchCode());
    }

    public function testWithCommentsState(): void
    {
        $workTime = WorkTimeFactory::new()
            ->withComments('Test comments')
            ->make();

        $this->assertEquals('Test comments', $workTime->getComments());
    }

    public function testForDateRangeState(): void
    {
        $workTime = WorkTimeFactory::new()
            ->forDateRange('01/01/2025', '07/01/2025')
            ->make();

        $this->assertEquals('01/01/2025', $workTime->getFromDate());
        $this->assertEquals('07/01/2025', $workTime->getToDate());
    }

    public function testForDateState(): void
    {
        $workTime = WorkTimeFactory::new()
            ->forDate('15/01/2025')
            ->make();

        $this->assertEquals('15/01/2025', $workTime->getFromDate());
        $this->assertEquals('15/01/2025', $workTime->getToDate());
    }

    public function testAsCorrectionState(): void
    {
        $workTime = WorkTimeFactory::new()
            ->asCorrection('ΕΣΠ123', '10/01/2025')
            ->make();

        $this->assertEquals('ΕΣΠ123', $workTime->getRelatedProtocol());
        $this->assertEquals('10/01/2025', $workTime->getRelatedDate());
    }

    public function testWithEmployeesState(): void
    {
        $workTime = WorkTimeFactory::new()
            ->withEmployees(3)
            ->make();

        $employees = $workTime->getEmployees();
        $this->assertCount(3, $employees);
        foreach ($employees as $employee) {
            $this->assertInstanceOf(WorkTimeEmployee::class, $employee);
        }
    }

    public function testWithoutEmployeesState(): void
    {
        $workTime = WorkTimeFactory::new()
            ->withoutEmployees()
            ->make();

        $this->assertEmpty($workTime->getEmployees());
    }

    public function testWithSpecificEmployeesState(): void
    {
        $customEmployees = [
            WorkTimeEmployeeFactory::new()->withTin('111111111')->make(),
            WorkTimeEmployeeFactory::new()->withTin('222222222')->make(),
        ];

        $workTime = WorkTimeFactory::new()
            ->withSpecificEmployees($customEmployees)
            ->make();

        $employees = $workTime->getEmployees();
        $this->assertCount(2, $employees);
        $this->assertEquals('111111111', $employees[0]->getTin());
        $this->assertEquals('222222222', $employees[1]->getTin());
    }

    public function testChainingMultipleStates(): void
    {
        $workTime = WorkTimeFactory::new()
            ->mainBranch()
            ->withComments('Weekly schedule')
            ->forDateRange('01/01/2025', '07/01/2025')
            ->withEmployees(2)
            ->make();

        $this->assertEquals('0', $workTime->getBranchCode());
        $this->assertEquals('Weekly schedule', $workTime->getComments());
        $this->assertEquals('01/01/2025', $workTime->getFromDate());
        $this->assertEquals('07/01/2025', $workTime->getToDate());
        $this->assertCount(2, $workTime->getEmployees());
    }

    public function testCountCreatesMultipleInstances(): void
    {
        $workTimes = WorkTimeFactory::new()
            ->count(5)
            ->make();

        $this->assertIsArray($workTimes);
        $this->assertCount(5, $workTimes);
        foreach ($workTimes as $workTime) {
            $this->assertInstanceOf(WorkTime::class, $workTime);
        }
    }

    public function testStateCanOverrideDefinition(): void
    {
        $workTime = WorkTimeFactory::new()
            ->state(['f_aa_pararthmatos' => '99'])
            ->make();

        $this->assertEquals('99', $workTime->getBranchCode());
    }

    public function testMakeCanOverrideState(): void
    {
        $workTime = WorkTimeFactory::new()
            ->state(['f_aa_pararthmatos' => '10'])
            ->make(['f_aa_pararthmatos' => '20']);

        $this->assertEquals('20', $workTime->getBranchCode());
    }

    public function testEachWorkTimeHasIndependentEmployees(): void
    {
        $workTimes = WorkTimeFactory::new()
            ->withEmployees(2)
            ->count(3)
            ->make();

        foreach ($workTimes as $workTime) {
            $this->assertCount(2, $workTime->getEmployees());
        }
    }
}
