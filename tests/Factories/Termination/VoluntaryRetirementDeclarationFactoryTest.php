<?php

namespace Tests\Factories\Termination;

use OxygenSuite\OxygenErgani\Enums\EmploymentStatus;
use OxygenSuite\OxygenErgani\Enums\EmploymentType;
use OxygenSuite\OxygenErgani\Enums\Sex;
use OxygenSuite\OxygenErgani\Enums\WorkerType;
use OxygenSuite\OxygenErgani\Factories\Factory;
use OxygenSuite\OxygenErgani\Factories\Termination\VoluntaryRetirementDeclarationFactory;
use OxygenSuite\OxygenErgani\Models\Termination\VoluntaryRetirementDeclaration;
use PHPUnit\Framework\TestCase;

class VoluntaryRetirementDeclarationFactoryTest extends TestCase
{
    protected function setUp(): void
    {
        Factory::resetFaker();
    }

    public function testCreatesDeclarationInstance(): void
    {
        $declaration = VoluntaryRetirementDeclarationFactory::new()->make();

        $this->assertInstanceOf(VoluntaryRetirementDeclaration::class, $declaration);
    }

    public function testModelHasFactoryMethod(): void
    {
        $factory = VoluntaryRetirementDeclaration::factory();

        $this->assertInstanceOf(VoluntaryRetirementDeclarationFactory::class, $factory);
    }

    public function testModelFactoryWithCount(): void
    {
        $declarations = VoluntaryRetirementDeclaration::factory(3)->make();

        $this->assertIsArray($declarations);
        $this->assertCount(3, $declarations);
        foreach ($declarations as $declaration) {
            $this->assertInstanceOf(VoluntaryRetirementDeclaration::class, $declaration);
        }
    }

    public function testDefinitionGeneratesRequiredFields(): void
    {
        $declaration = VoluntaryRetirementDeclarationFactory::new()->make();

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

        // E5S specific: HasSalary and HasCompensation
        $this->assertNotNull($declaration->getGrossSalary());
        $this->assertNotNull($declaration->getCompensationAmount());
    }

    public function testAfmIsValid(): void
    {
        $declaration = VoluntaryRetirementDeclarationFactory::new()->make();
        $afm = $declaration->getAfm();

        $this->assertMatchesRegularExpression('/^\d{9}$/', $afm);
    }

    public function testAmkaIsValid(): void
    {
        $declaration = VoluntaryRetirementDeclarationFactory::new()->make();
        $amka = $declaration->getAmka();

        $this->assertMatchesRegularExpression('/^\d{11}$/', $amka);
    }

    public function testDefaultsToGreekNational(): void
    {
        $declaration = VoluntaryRetirementDeclarationFactory::new()->make();

        $this->assertEquals('001', $declaration->getNationality());
        $this->assertEquals('0', $declaration->getResPermitDirectAccess());
        $this->assertEquals('0', $declaration->getResPermitApproval());
        $this->assertEquals('0', $declaration->getSeasonalWorkVisa());
    }

    public function testDefaultsToFullTimeIndefinite(): void
    {
        $declaration = VoluntaryRetirementDeclarationFactory::new()->make();

        $this->assertEquals((string) EmploymentType::INDEFINITE->value, $declaration->getEmploymentType());
        $this->assertEquals((string) EmploymentStatus::FULL->value, $declaration->getEmploymentStatus());
    }

    public function testWithSalaryState(): void
    {
        $declaration = VoluntaryRetirementDeclarationFactory::new()
            ->withSalary(2500.00)
            ->make();

        $this->assertEquals(2500.0, $declaration->getGrossSalary());
    }

    public function testWithCompensationState(): void
    {
        $declaration = VoluntaryRetirementDeclarationFactory::new()
            ->withCompensation(25000.00)
            ->make();

        $this->assertEquals(25000.0, $declaration->getCompensationAmount());
    }

    public function testWithFormFileState(): void
    {
        $declaration = VoluntaryRetirementDeclarationFactory::new()
            ->withFormFile('retirementbase64')
            ->make();

        $this->assertEquals('retirementbase64', $declaration->getFormFile());
    }

    public function testFixedTermState(): void
    {
        $declaration = VoluntaryRetirementDeclarationFactory::new()
            ->fixedTerm('01/01/2025', '30/06/2025')
            ->make();

        $this->assertEquals((string) EmploymentType::FIXED_TERM->value, $declaration->getEmploymentType());
        $this->assertEquals('01/01/2025', $declaration->getFixedTermFrom());
        $this->assertEquals('30/06/2025', $declaration->getFixedTermTo());
    }

    public function testPartTimeState(): void
    {
        $declaration = VoluntaryRetirementDeclarationFactory::new()
            ->partTime()
            ->make();

        $this->assertEquals((string) EmploymentStatus::PARTIAL->value, $declaration->getEmploymentStatus());
    }

    public function testAsWorkerState(): void
    {
        $declaration = VoluntaryRetirementDeclarationFactory::new()
            ->asWorker()
            ->make();

        $this->assertEquals((string) WorkerType::WORKER->value, $declaration->getWorkerType());
    }

    public function testMaleState(): void
    {
        $declaration = VoluntaryRetirementDeclarationFactory::new()
            ->male()
            ->make();

        $this->assertEquals((string) Sex::MALE->value, $declaration->getSex());
    }

    public function testFemaleState(): void
    {
        $declaration = VoluntaryRetirementDeclarationFactory::new()
            ->female()
            ->make();

        $this->assertEquals((string) Sex::FEMALE->value, $declaration->getSex());
    }

    public function testChainingMultipleStates(): void
    {
        $declaration = VoluntaryRetirementDeclarationFactory::new()
            ->partTime()
            ->male()
            ->withSalary(2000.00)
            ->withCompensation(20000.00)
            ->make();

        $this->assertEquals((string) EmploymentStatus::PARTIAL->value, $declaration->getEmploymentStatus());
        $this->assertEquals((string) Sex::MALE->value, $declaration->getSex());
        $this->assertEquals(2000.0, $declaration->getGrossSalary());
        $this->assertEquals(20000.0, $declaration->getCompensationAmount());
    }

    public function testCountCreatesMultipleInstances(): void
    {
        $declarations = VoluntaryRetirementDeclarationFactory::new()
            ->count(3)
            ->make();

        $this->assertIsArray($declarations);
        $this->assertCount(3, $declarations);
        foreach ($declarations as $declaration) {
            $this->assertInstanceOf(VoluntaryRetirementDeclaration::class, $declaration);
        }
    }

    public function testStateCanOverrideDefinition(): void
    {
        $declaration = VoluntaryRetirementDeclarationFactory::new()
            ->state(['f_eponymo' => 'ΤΕΣΤΟΠΟΥΛΟΣ'])
            ->make();

        $this->assertEquals('ΤΕΣΤΟΠΟΥΛΟΣ', $declaration->getLastName());
    }

    public function testExceptExcludesFields(): void
    {
        $declaration = VoluntaryRetirementDeclarationFactory::new()
            ->except(['f_afm', 'f_amka'])
            ->make();

        $this->assertNull($declaration->getAfm());
        $this->assertNull($declaration->getAmka());
    }
}
