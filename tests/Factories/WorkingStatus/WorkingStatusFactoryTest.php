<?php

namespace Tests\Factories\WorkingStatus;

use OxygenSuite\OxygenErgani\Factories\Factory;
use OxygenSuite\OxygenErgani\Factories\WorkingStatus\WorkingStatusFactory;
use OxygenSuite\OxygenErgani\Models\WorkingStatus\WorkingStatus;
use PHPUnit\Framework\TestCase;

class WorkingStatusFactoryTest extends TestCase
{
    protected function setUp(): void
    {
        Factory::resetFaker();
    }

    public function testCreatesWorkingStatusInstance(): void
    {
        $workingStatus = WorkingStatusFactory::new()->make();

        $this->assertInstanceOf(WorkingStatus::class, $workingStatus);
    }

    public function testModelHasFactoryMethod(): void
    {
        $factory = WorkingStatus::factory();

        $this->assertInstanceOf(WorkingStatusFactory::class, $factory);
    }

    public function testDefinitionGeneratesRequiredFields(): void
    {
        $workingStatus = WorkingStatusFactory::new()->make();

        $this->assertNotNull($workingStatus->getBranchCode());
    }

    public function testForBranchState(): void
    {
        $workingStatus = WorkingStatusFactory::new()
            ->forBranch(5)
            ->make();

        $this->assertEquals('5', $workingStatus->getBranchCode());
    }

    public function testMainBranchState(): void
    {
        $workingStatus = WorkingStatusFactory::new()
            ->mainBranch()
            ->make();

        $this->assertEquals('0', $workingStatus->getBranchCode());
    }

    public function testWithCommentsState(): void
    {
        $workingStatus = WorkingStatusFactory::new()
            ->withComments('Test comments')
            ->make();

        $this->assertEquals('Test comments', $workingStatus->getComments());
    }

    public function testAsCorrectionState(): void
    {
        $workingStatus = WorkingStatusFactory::new()
            ->asCorrection('WKC12345', '15/01/2025')
            ->make();

        $this->assertEquals('WKC12345', $workingStatus->getRelatedProtocol());
        $this->assertEquals('15/01/2025', $workingStatus->getRelatedDate());
    }

    public function testWithEmployeesState(): void
    {
        $workingStatus = WorkingStatusFactory::new()
            ->withEmployees(3)
            ->make();

        $employees = $workingStatus->getEmployees();
        $this->assertIsArray($employees);
        $this->assertCount(3, $employees);
    }

    public function testWithoutEmployeesState(): void
    {
        $workingStatus = WorkingStatusFactory::new()
            ->withoutEmployees()
            ->make();

        $employees = $workingStatus->getEmployees();
        $this->assertIsArray($employees);
        $this->assertCount(0, $employees);
    }

    public function testChainingMultipleStates(): void
    {
        $workingStatus = WorkingStatusFactory::new()
            ->mainBranch()
            ->withComments('Important change')
            ->withEmployees(2)
            ->make();

        $this->assertEquals('0', $workingStatus->getBranchCode());
        $this->assertEquals('Important change', $workingStatus->getComments());
        $this->assertCount(2, $workingStatus->getEmployees());
    }

    public function testCountCreatesMultipleInstances(): void
    {
        $workingStatuses = WorkingStatusFactory::new()
            ->count(3)
            ->make();

        $this->assertIsArray($workingStatuses);
        $this->assertCount(3, $workingStatuses);
        foreach ($workingStatuses as $workingStatus) {
            $this->assertInstanceOf(WorkingStatus::class, $workingStatus);
        }
    }
}
