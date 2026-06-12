<?php

namespace Tests\Factories\Termination;

use OxygenSuite\OxygenErgani\Enums\EmploymentStatus;
use OxygenSuite\OxygenErgani\Enums\EmploymentType;
use OxygenSuite\OxygenErgani\Enums\Sex;
use OxygenSuite\OxygenErgani\Enums\WorkerType;
use OxygenSuite\OxygenErgani\Factories\Factory;
use OxygenSuite\OxygenErgani\Factories\Termination\VoluntaryResignationDeclarationFactory;
use OxygenSuite\OxygenErgani\Models\Termination\VoluntaryResignationDeclaration;
use PHPUnit\Framework\TestCase;

class VoluntaryResignationDeclarationFactoryTest extends TestCase
{
    protected function setUp(): void
    {
        Factory::resetFaker();
    }

    public function testCreatesDeclarationInstance(): void
    {
        $declaration = VoluntaryResignationDeclarationFactory::new()->make();

        $this->assertInstanceOf(VoluntaryResignationDeclaration::class, $declaration);
    }

    public function testModelHasFactoryMethod(): void
    {
        $factory = VoluntaryResignationDeclaration::factory();

        $this->assertInstanceOf(VoluntaryResignationDeclarationFactory::class, $factory);
    }

    public function testModelFactoryWithCount(): void
    {
        $declarations = VoluntaryResignationDeclaration::factory(3)->make();

        $this->assertIsArray($declarations);
        $this->assertCount(3, $declarations);
        foreach ($declarations as $declaration) {
            $this->assertInstanceOf(VoluntaryResignationDeclaration::class, $declaration);
        }
    }

    public function testDefinitionGeneratesRequiredFields(): void
    {
        $declaration = VoluntaryResignationDeclarationFactory::new()->make();

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

        // E5N specific: HasSalary
        $this->assertNotNull($declaration->getGrossSalary());
    }

    public function testAfmIsValid(): void
    {
        $declaration = VoluntaryResignationDeclarationFactory::new()->make();
        $afm = $declaration->getAfm();

        $this->assertMatchesRegularExpression('/^\d{9}$/', $afm);
    }

    public function testAmkaIsValid(): void
    {
        $declaration = VoluntaryResignationDeclarationFactory::new()->make();
        $amka = $declaration->getAmka();

        $this->assertMatchesRegularExpression('/^\d{11}$/', $amka);
    }

    public function testDefaultsToGreekNational(): void
    {
        $declaration = VoluntaryResignationDeclarationFactory::new()->make();

        $this->assertEquals('001', $declaration->getNationality());
        $this->assertEquals('0', $declaration->getResPermitDirectAccess());
        $this->assertEquals('0', $declaration->getResPermitApproval());
        $this->assertEquals('0', $declaration->getSeasonalWorkVisa());
    }

    public function testDefaultsToFullTimeIndefinite(): void
    {
        $declaration = VoluntaryResignationDeclarationFactory::new()->make();

        $this->assertEquals((string) EmploymentType::INDEFINITE->value, $declaration->getEmploymentType());
        $this->assertEquals((string) EmploymentStatus::FULL->value, $declaration->getEmploymentStatus());
    }

    public function testWithSalaryState(): void
    {
        $declaration = VoluntaryResignationDeclarationFactory::new()
            ->withSalary(2500.00)
            ->make();

        $this->assertEquals(2500.0, $declaration->getGrossSalary());
    }

    public function testWithFormFileState(): void
    {
        $declaration = VoluntaryResignationDeclarationFactory::new()
            ->withFormFile('base64content')
            ->make();

        $this->assertEquals('base64content', $declaration->getFormFile());
    }

    public function testFixedTermState(): void
    {
        $declaration = VoluntaryResignationDeclarationFactory::new()
            ->fixedTerm('01/01/2025', '30/06/2025')
            ->make();

        $this->assertEquals((string) EmploymentType::FIXED_TERM->value, $declaration->getEmploymentType());
        $this->assertEquals('01/01/2025', $declaration->getFixedTermFrom());
        $this->assertEquals('30/06/2025', $declaration->getFixedTermTo());
    }

    public function testPartTimeState(): void
    {
        $declaration = VoluntaryResignationDeclarationFactory::new()
            ->partTime()
            ->make();

        $this->assertEquals((string) EmploymentStatus::PARTIAL->value, $declaration->getEmploymentStatus());
    }

    public function testForeignNationalState(): void
    {
        $declaration = VoluntaryResignationDeclarationFactory::new()
            ->foreignNational('002')
            ->make();

        $this->assertEquals('002', $declaration->getNationality());
        $this->assertEquals('1', $declaration->getResPermitDirectAccess());
        $this->assertNotNull($declaration->getResPermitDirectAccessType());
        $this->assertNotNull($declaration->getResPermitDirectAccessNumber());
        $this->assertNotNull($declaration->getResPermitDirectAccessExpiry());
    }

    public function testAsWorkerState(): void
    {
        $declaration = VoluntaryResignationDeclarationFactory::new()
            ->asWorker()
            ->make();

        $this->assertEquals((string) WorkerType::WORKER->value, $declaration->getWorkerType());
    }

    public function testMaleState(): void
    {
        $declaration = VoluntaryResignationDeclarationFactory::new()
            ->male()
            ->make();

        $this->assertEquals((string) Sex::MALE->value, $declaration->getSex());
    }

    public function testFemaleState(): void
    {
        $declaration = VoluntaryResignationDeclarationFactory::new()
            ->female()
            ->make();

        $this->assertEquals((string) Sex::FEMALE->value, $declaration->getSex());
    }

    public function testWithRelatedProtocolState(): void
    {
        $declaration = VoluntaryResignationDeclarationFactory::new()
            ->withRelatedProtocol('E5N12345', '15/01/2025')
            ->make();

        $this->assertEquals('E5N12345', $declaration->getRelatedProtocol());
        $this->assertEquals('15/01/2025', $declaration->getRelatedDate());
    }

    public function testDepartureDateState(): void
    {
        $declaration = VoluntaryResignationDeclarationFactory::new()
            ->departureDate('31/12/2025')
            ->make();

        $this->assertEquals('31/12/2025', $declaration->getDepartureDate());
    }

    public function testChainingMultipleStates(): void
    {
        $declaration = VoluntaryResignationDeclarationFactory::new()
            ->fixedTerm()
            ->partTime()
            ->male()
            ->withSalary(1800.00)
            ->make();

        $this->assertEquals((string) EmploymentType::FIXED_TERM->value, $declaration->getEmploymentType());
        $this->assertEquals((string) EmploymentStatus::PARTIAL->value, $declaration->getEmploymentStatus());
        $this->assertEquals((string) Sex::MALE->value, $declaration->getSex());
        $this->assertEquals(1800.0, $declaration->getGrossSalary());
    }

    public function testCountCreatesMultipleInstances(): void
    {
        $declarations = VoluntaryResignationDeclarationFactory::new()
            ->count(3)
            ->make();

        $this->assertIsArray($declarations);
        $this->assertCount(3, $declarations);
        foreach ($declarations as $declaration) {
            $this->assertInstanceOf(VoluntaryResignationDeclaration::class, $declaration);
        }
    }

    public function testStateCanOverrideDefinition(): void
    {
        $declaration = VoluntaryResignationDeclarationFactory::new()
            ->state(['f_eponymo' => 'ΤΕΣΤΟΠΟΥΛΟΣ'])
            ->make();

        $this->assertEquals('ΤΕΣΤΟΠΟΥΛΟΣ', $declaration->getLastName());
    }

    public function testExceptExcludesFields(): void
    {
        $declaration = VoluntaryResignationDeclarationFactory::new()
            ->except(['f_afm', 'f_amka'])
            ->make();

        $this->assertNull($declaration->getAfm());
        $this->assertNull($declaration->getAmka());
    }
}
