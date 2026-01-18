<?php

namespace Tests\Factories\Dismissal;

use OxygenSuite\OxygenErgani\Enums\EmploymentStatus;
use OxygenSuite\OxygenErgani\Enums\WorkerType;
use OxygenSuite\OxygenErgani\Factories\Dismissal\DismissalWithoutNoticeDeclarationFactory;
use OxygenSuite\OxygenErgani\Factories\Factory;
use OxygenSuite\OxygenErgani\Models\Dismissal\DismissalWithoutNoticeDeclaration;
use PHPUnit\Framework\TestCase;

class DismissalWithoutNoticeDeclarationFactoryTest extends TestCase
{
    protected function setUp(): void
    {
        Factory::resetFaker();
    }

    public function testCreatesDeclarationInstance(): void
    {
        $declaration = DismissalWithoutNoticeDeclarationFactory::new()->make();

        $this->assertInstanceOf(DismissalWithoutNoticeDeclaration::class, $declaration);
    }

    public function testModelHasFactoryMethod(): void
    {
        $factory = DismissalWithoutNoticeDeclaration::factory();

        $this->assertInstanceOf(DismissalWithoutNoticeDeclarationFactory::class, $factory);
    }

    public function testDefinitionGeneratesRequiredFields(): void
    {
        $declaration = DismissalWithoutNoticeDeclarationFactory::new()->make();

        // Personal info
        $this->assertNotNull($declaration->getFirstName());
        $this->assertNotNull($declaration->getLastName());
        $this->assertNotNull($declaration->getAfm());
        $this->assertNotNull($declaration->getAmka());

        // E6NXP specific
        $this->assertNotNull($declaration->getHiringDate());
        $this->assertNotNull($declaration->getDismissalDate());
        $this->assertNotNull($declaration->getGrossSalary());
        $this->assertNotNull($declaration->getCompensationAmount());
    }

    public function testAfmIsValid(): void
    {
        $declaration = DismissalWithoutNoticeDeclarationFactory::new()->make();
        $afm = $declaration->getAfm();

        $this->assertMatchesRegularExpression('/^\d{9}$/', $afm);
    }

    public function testPartTimeState(): void
    {
        $declaration = DismissalWithoutNoticeDeclarationFactory::new()
            ->partTime()
            ->make();

        $this->assertEquals((string) EmploymentStatus::PARTIAL->value, $declaration->getEmploymentStatus());
    }

    public function testAsWorkerState(): void
    {
        $declaration = DismissalWithoutNoticeDeclarationFactory::new()
            ->asWorker()
            ->make();

        $this->assertEquals((string) WorkerType::WORKER->value, $declaration->getWorkerType());
    }

    public function testAsCollectiveDismissalState(): void
    {
        $declaration = DismissalWithoutNoticeDeclarationFactory::new()
            ->asCollectiveDismissal('ΑΠ-123456', '01/01/2025')
            ->make();

        $this->assertTrue($declaration->isCollectiveDismissal());
        $this->assertEquals('ΑΠ-123456', $declaration->getCollectiveDismissalNumber());
        $this->assertEquals('01/01/2025', $declaration->getCollectiveDismissalDate());
    }

    public function testWithSalaryState(): void
    {
        $declaration = DismissalWithoutNoticeDeclarationFactory::new()
            ->withSalary(2500.00)
            ->make();

        $this->assertEquals(2500.0, $declaration->getGrossSalary());
    }

    public function testWithSeveranceState(): void
    {
        $declaration = DismissalWithoutNoticeDeclarationFactory::new()
            ->withSeverance(5000.00)
            ->make();

        $this->assertEquals(5000.0, $declaration->getCompensationAmount());
    }

    public function testWithFormFileState(): void
    {
        $declaration = DismissalWithoutNoticeDeclarationFactory::new()
            ->withFormFile('base64content')
            ->make();

        $this->assertEquals('base64content', $declaration->getFormFile());
    }

    public function testCountCreatesMultipleInstances(): void
    {
        $declarations = DismissalWithoutNoticeDeclarationFactory::new()
            ->count(3)
            ->make();

        $this->assertIsArray($declarations);
        $this->assertCount(3, $declarations);
        foreach ($declarations as $declaration) {
            $this->assertInstanceOf(DismissalWithoutNoticeDeclaration::class, $declaration);
        }
    }
}
