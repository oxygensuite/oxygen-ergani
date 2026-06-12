<?php

namespace Tests\Factories\Termination;

use OxygenSuite\OxygenErgani\Enums\EmploymentStatus;
use OxygenSuite\OxygenErgani\Enums\EmploymentType;
use OxygenSuite\OxygenErgani\Enums\Sex;
use OxygenSuite\OxygenErgani\Enums\WorkerType;
use OxygenSuite\OxygenErgani\Factories\Factory;
use OxygenSuite\OxygenErgani\Factories\Termination\CompensatedExitDeclarationFactory;
use OxygenSuite\OxygenErgani\Models\Termination\CompensatedExitDeclaration;
use PHPUnit\Framework\TestCase;

class CompensatedExitDeclarationFactoryTest extends TestCase
{
    protected function setUp(): void
    {
        Factory::resetFaker();
    }

    public function testCreatesDeclarationInstance(): void
    {
        $declaration = CompensatedExitDeclarationFactory::new()->make();

        $this->assertInstanceOf(CompensatedExitDeclaration::class, $declaration);
    }

    public function testModelHasFactoryMethod(): void
    {
        $factory = CompensatedExitDeclaration::factory();

        $this->assertInstanceOf(CompensatedExitDeclarationFactory::class, $factory);
    }

    public function testModelFactoryWithCount(): void
    {
        $declarations = CompensatedExitDeclaration::factory(3)->make();

        $this->assertIsArray($declarations);
        $this->assertCount(3, $declarations);
        foreach ($declarations as $declaration) {
            $this->assertInstanceOf(CompensatedExitDeclaration::class, $declaration);
        }
    }

    public function testDefinitionGeneratesRequiredFields(): void
    {
        $declaration = CompensatedExitDeclarationFactory::new()->make();

        // Personal info
        $this->assertNotNull($declaration->getFirstName());
        $this->assertNotNull($declaration->getLastName());
        $this->assertNotNull($declaration->getFatherName());
        $this->assertNotNull($declaration->getMotherName());
        $this->assertNotNull($declaration->getBirthDate());
        $this->assertNotNull($declaration->getSex());

        // IDs
        $this->assertNotNull($declaration->getAfm());
        $this->assertNotNull($declaration->getAmka());
        $this->assertNotNull($declaration->getIdNumber());

        // Employment
        $this->assertNotNull($declaration->getHiringDate());
        $this->assertNotNull($declaration->getDepartureDate());

        // E5E specific: HasSalary and HasCompensation
        $this->assertNotNull($declaration->getGrossSalary());
        $this->assertNotNull($declaration->getCompensationAmount());
    }

    public function testAfmIsValid(): void
    {
        $declaration = CompensatedExitDeclarationFactory::new()->make();
        $afm = $declaration->getAfm();

        $this->assertMatchesRegularExpression('/^\d{9}$/', $afm);
    }

    public function testAmkaIsValid(): void
    {
        $declaration = CompensatedExitDeclarationFactory::new()->make();
        $amka = $declaration->getAmka();

        $this->assertMatchesRegularExpression('/^\d{11}$/', $amka);
    }

    public function testDefaultsToGreekNational(): void
    {
        $declaration = CompensatedExitDeclarationFactory::new()->make();

        $this->assertEquals('001', $declaration->getNationality());
        $this->assertEquals('0', $declaration->getResPermitDirectAccess());
        $this->assertEquals('0', $declaration->getResPermitApproval());
        $this->assertEquals('0', $declaration->getSeasonalWorkVisa());
    }

    public function testDefaultsToFullTimeIndefinite(): void
    {
        $declaration = CompensatedExitDeclarationFactory::new()->make();

        $this->assertEquals((string) EmploymentType::INDEFINITE->value, $declaration->getEmploymentType());
        $this->assertEquals((string) EmploymentStatus::FULL->value, $declaration->getEmploymentStatus());
    }

    public function testWithSalaryState(): void
    {
        $declaration = CompensatedExitDeclarationFactory::new()
            ->withSalary(2500.00)
            ->make();

        $this->assertEquals(2500.0, $declaration->getGrossSalary());
    }

    public function testWithCompensationState(): void
    {
        $declaration = CompensatedExitDeclarationFactory::new()
            ->withCompensation(15000.00)
            ->make();

        $this->assertEquals(15000.0, $declaration->getCompensationAmount());
    }

    public function testWithFormFileState(): void
    {
        $declaration = CompensatedExitDeclarationFactory::new()
            ->withFormFile('agreementbase64')
            ->make();

        $this->assertEquals('agreementbase64', $declaration->getFormFile());
    }

    public function testFixedTermState(): void
    {
        $declaration = CompensatedExitDeclarationFactory::new()
            ->fixedTerm('01/01/2025', '30/06/2025')
            ->make();

        $this->assertEquals((string) EmploymentType::FIXED_TERM->value, $declaration->getEmploymentType());
        $this->assertEquals('01/01/2025', $declaration->getFixedTermFrom());
        $this->assertEquals('30/06/2025', $declaration->getFixedTermTo());
    }

    public function testPartTimeState(): void
    {
        $declaration = CompensatedExitDeclarationFactory::new()
            ->partTime()
            ->make();

        $this->assertEquals((string) EmploymentStatus::PARTIAL->value, $declaration->getEmploymentStatus());
    }

    public function testAsWorkerState(): void
    {
        $declaration = CompensatedExitDeclarationFactory::new()
            ->asWorker()
            ->make();

        $this->assertEquals((string) WorkerType::WORKER->value, $declaration->getWorkerType());
    }

    public function testMaleState(): void
    {
        $declaration = CompensatedExitDeclarationFactory::new()
            ->male()
            ->make();

        $this->assertEquals((string) Sex::MALE->value, $declaration->getSex());
    }

    public function testFemaleState(): void
    {
        $declaration = CompensatedExitDeclarationFactory::new()
            ->female()
            ->make();

        $this->assertEquals((string) Sex::FEMALE->value, $declaration->getSex());
    }

    public function testChainingMultipleStates(): void
    {
        $declaration = CompensatedExitDeclarationFactory::new()
            ->partTime()
            ->male()
            ->withSalary(2000.00)
            ->withCompensation(10000.00)
            ->make();

        $this->assertEquals((string) EmploymentStatus::PARTIAL->value, $declaration->getEmploymentStatus());
        $this->assertEquals((string) Sex::MALE->value, $declaration->getSex());
        $this->assertEquals(2000.0, $declaration->getGrossSalary());
        $this->assertEquals(10000.0, $declaration->getCompensationAmount());
    }

    public function testCountCreatesMultipleInstances(): void
    {
        $declarations = CompensatedExitDeclarationFactory::new()
            ->count(3)
            ->make();

        $this->assertIsArray($declarations);
        $this->assertCount(3, $declarations);
        foreach ($declarations as $declaration) {
            $this->assertInstanceOf(CompensatedExitDeclaration::class, $declaration);
        }
    }

    public function testStateCanOverrideDefinition(): void
    {
        $declaration = CompensatedExitDeclarationFactory::new()
            ->state(['f_eponymo' => 'ΤΕΣΤΟΠΟΥΛΟΣ'])
            ->make();

        $this->assertEquals('ΤΕΣΤΟΠΟΥΛΟΣ', $declaration->getLastName());
    }

    public function testExceptExcludesFields(): void
    {
        $declaration = CompensatedExitDeclarationFactory::new()
            ->except(['f_afm', 'f_amka'])
            ->make();

        $this->assertNull($declaration->getAfm());
        $this->assertNull($declaration->getAmka());
    }
}
