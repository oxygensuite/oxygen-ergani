<?php

namespace Tests\Factories\Termination;

use DateTimeImmutable;
use OxygenSuite\OxygenErgani\Enums\EmploymentStatus;
use OxygenSuite\OxygenErgani\Enums\EmploymentType;
use OxygenSuite\OxygenErgani\Enums\Sex;
use OxygenSuite\OxygenErgani\Enums\WorkerType;
use OxygenSuite\OxygenErgani\Factories\Factory;
use OxygenSuite\OxygenErgani\Factories\Termination\MandatoryRetirementDeclarationFactory;
use OxygenSuite\OxygenErgani\Models\Termination\MandatoryRetirementDeclaration;
use PHPUnit\Framework\TestCase;

class MandatoryRetirementDeclarationFactoryTest extends TestCase
{
    protected function setUp(): void
    {
        Factory::resetFaker();
    }

    public function testCreatesDeclarationInstance(): void
    {
        $declaration = MandatoryRetirementDeclarationFactory::new()->make();

        $this->assertInstanceOf(MandatoryRetirementDeclaration::class, $declaration);
    }

    public function testModelHasFactoryMethod(): void
    {
        $factory = MandatoryRetirementDeclaration::factory();

        $this->assertInstanceOf(MandatoryRetirementDeclarationFactory::class, $factory);
    }

    public function testModelFactoryWithCount(): void
    {
        $declarations = MandatoryRetirementDeclaration::factory(3)->make();

        $this->assertIsArray($declarations);
        $this->assertCount(3, $declarations);
        foreach ($declarations as $declaration) {
            $this->assertInstanceOf(MandatoryRetirementDeclaration::class, $declaration);
        }
    }

    public function testDefinitionGeneratesRequiredFields(): void
    {
        $declaration = MandatoryRetirementDeclarationFactory::new()->make();

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

        // E5DS specific: HasSalary and HasCompensation
        $this->assertNotNull($declaration->getGrossSalary());
        $this->assertNotNull($declaration->getCompensationAmount());
    }

    public function testAfmIsValid(): void
    {
        $declaration = MandatoryRetirementDeclarationFactory::new()->make();
        $afm = $declaration->getAfm();

        $this->assertMatchesRegularExpression('/^\d{9}$/', $afm);
    }

    public function testAmkaIsValid(): void
    {
        $declaration = MandatoryRetirementDeclarationFactory::new()->make();
        $amka = $declaration->getAmka();

        $this->assertMatchesRegularExpression('/^\d{11}$/', $amka);
    }

    public function testDefaultsToGreekNational(): void
    {
        $declaration = MandatoryRetirementDeclarationFactory::new()->make();

        $this->assertEquals('001', $declaration->getNationality());
        $this->assertEquals('0', $declaration->getResPermitDirectAccess());
        $this->assertEquals('0', $declaration->getResPermitApproval());
        $this->assertEquals('0', $declaration->getSeasonalWorkVisa());
    }

    public function testDefaultsToFullTimeIndefinite(): void
    {
        $declaration = MandatoryRetirementDeclarationFactory::new()->make();

        $this->assertEquals((string) EmploymentType::INDEFINITE->value, $declaration->getEmploymentType());
        $this->assertEquals((string) EmploymentStatus::FULL->value, $declaration->getEmploymentStatus());
    }

    public function testEmployeeIsRetirementAge(): void
    {
        $declaration = MandatoryRetirementDeclarationFactory::new()->make();

        // Birth date should be for someone 67-70 years old (mandatory retirement age)
        $birthDate = DateTimeImmutable::createFromFormat('d/m/Y', $declaration->getBirthDate());
        $this->assertNotFalse($birthDate);

        $age = (int) $birthDate->diff(new DateTimeImmutable())->y;
        $this->assertGreaterThanOrEqual(67, $age);
        $this->assertLessThanOrEqual(70, $age);
    }

    public function testWithSalaryState(): void
    {
        $declaration = MandatoryRetirementDeclarationFactory::new()
            ->withSalary(2500.00)
            ->make();

        $this->assertEquals(2500.0, $declaration->getGrossSalary());
    }

    public function testWithCompensationState(): void
    {
        $declaration = MandatoryRetirementDeclarationFactory::new()
            ->withCompensation(40000.00)
            ->make();

        $this->assertEquals(40000.0, $declaration->getCompensationAmount());
    }

    public function testDefaultCompensationIsHigherThanOtherTypes(): void
    {
        $declaration = MandatoryRetirementDeclarationFactory::new()->make();

        // Mandatory retirement typically has higher compensation (15+ years service)
        $this->assertGreaterThanOrEqual(5000.0, $declaration->getCompensationAmount());
    }

    public function testWithFormFileState(): void
    {
        $declaration = MandatoryRetirementDeclarationFactory::new()
            ->withFormFile('retirementbase64')
            ->make();

        $this->assertEquals('retirementbase64', $declaration->getFormFile());
    }

    public function testFixedTermState(): void
    {
        $declaration = MandatoryRetirementDeclarationFactory::new()
            ->fixedTerm('01/01/2025', '30/06/2025')
            ->make();

        $this->assertEquals((string) EmploymentType::FIXED_TERM->value, $declaration->getEmploymentType());
        $this->assertEquals('01/01/2025', $declaration->getFixedTermFrom());
        $this->assertEquals('30/06/2025', $declaration->getFixedTermTo());
    }

    public function testPartTimeState(): void
    {
        $declaration = MandatoryRetirementDeclarationFactory::new()
            ->partTime()
            ->make();

        $this->assertEquals((string) EmploymentStatus::PARTIAL->value, $declaration->getEmploymentStatus());
    }

    public function testAsWorkerState(): void
    {
        $declaration = MandatoryRetirementDeclarationFactory::new()
            ->asWorker()
            ->make();

        $this->assertEquals((string) WorkerType::WORKER->value, $declaration->getWorkerType());
    }

    public function testMaleState(): void
    {
        $declaration = MandatoryRetirementDeclarationFactory::new()
            ->male()
            ->make();

        $this->assertEquals((string) Sex::MALE->value, $declaration->getSex());
    }

    public function testFemaleState(): void
    {
        $declaration = MandatoryRetirementDeclarationFactory::new()
            ->female()
            ->make();

        $this->assertEquals((string) Sex::FEMALE->value, $declaration->getSex());
    }

    public function testChainingMultipleStates(): void
    {
        $declaration = MandatoryRetirementDeclarationFactory::new()
            ->partTime()
            ->male()
            ->withSalary(2500.00)
            ->withCompensation(45000.00)
            ->make();

        $this->assertEquals((string) EmploymentStatus::PARTIAL->value, $declaration->getEmploymentStatus());
        $this->assertEquals((string) Sex::MALE->value, $declaration->getSex());
        $this->assertEquals(2500.0, $declaration->getGrossSalary());
        $this->assertEquals(45000.0, $declaration->getCompensationAmount());
    }

    public function testCountCreatesMultipleInstances(): void
    {
        $declarations = MandatoryRetirementDeclarationFactory::new()
            ->count(3)
            ->make();

        $this->assertIsArray($declarations);
        $this->assertCount(3, $declarations);
        foreach ($declarations as $declaration) {
            $this->assertInstanceOf(MandatoryRetirementDeclaration::class, $declaration);
        }
    }

    public function testStateCanOverrideDefinition(): void
    {
        $declaration = MandatoryRetirementDeclarationFactory::new()
            ->state(['f_eponymo' => 'ΤΕΣΤΟΠΟΥΛΟΣ'])
            ->make();

        $this->assertEquals('ΤΕΣΤΟΠΟΥΛΟΣ', $declaration->getLastName());
    }

    public function testExceptExcludesFields(): void
    {
        $declaration = MandatoryRetirementDeclarationFactory::new()
            ->except(['f_afm', 'f_amka'])
            ->make();

        $this->assertNull($declaration->getAfm());
        $this->assertNull($declaration->getAmka());
    }
}
