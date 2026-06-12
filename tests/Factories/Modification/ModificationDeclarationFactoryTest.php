<?php

namespace Tests\Factories\Modification;

use OxygenSuite\OxygenErgani\Enums\EmploymentStatus;
use OxygenSuite\OxygenErgani\Enums\EmploymentType;
use OxygenSuite\OxygenErgani\Enums\Sex;
use OxygenSuite\OxygenErgani\Enums\WorkerType;
use OxygenSuite\OxygenErgani\Factories\Factory;
use OxygenSuite\OxygenErgani\Factories\Modification\ModificationDeclarationFactory;
use OxygenSuite\OxygenErgani\Models\Modification\ModificationDeclaration;
use PHPUnit\Framework\TestCase;

class ModificationDeclarationFactoryTest extends TestCase
{
    protected function setUp(): void
    {
        Factory::resetFaker();
    }

    public function testCreatesDeclarationInstance(): void
    {
        $declaration = ModificationDeclarationFactory::new()->make();

        $this->assertInstanceOf(ModificationDeclaration::class, $declaration);
    }

    public function testModelHasFactoryMethod(): void
    {
        $factory = ModificationDeclaration::factory();

        $this->assertInstanceOf(ModificationDeclarationFactory::class, $factory);
    }

    public function testDefinitionGeneratesRequiredFields(): void
    {
        $declaration = ModificationDeclarationFactory::new()->make();

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

        // MA specific
        $this->assertNotNull($declaration->getChangeDate());
    }

    public function testAfmIsValid(): void
    {
        $declaration = ModificationDeclarationFactory::new()->make();
        $afm = $declaration->getAfm();

        $this->assertMatchesRegularExpression('/^\d{9}$/', $afm);
    }

    public function testAmkaIsValid(): void
    {
        $declaration = ModificationDeclarationFactory::new()->make();
        $amka = $declaration->getAmka();

        $this->assertMatchesRegularExpression('/^\d{11}$/', $amka);
    }

    public function testDefaultsToGreekNational(): void
    {
        $declaration = ModificationDeclarationFactory::new()->make();

        $this->assertEquals('001', $declaration->getNationality());
    }

    public function testDefaultsToFullTimeIndefinite(): void
    {
        $declaration = ModificationDeclarationFactory::new()->make();

        $this->assertEquals((string) EmploymentType::INDEFINITE->value, $declaration->getEmploymentType());
        $this->assertEquals((string) EmploymentStatus::FULL->value, $declaration->getEmploymentStatus());
    }

    public function testFixedTermState(): void
    {
        $declaration = ModificationDeclarationFactory::new()
            ->fixedTerm('01/01/2025', '30/06/2025')
            ->make();

        $this->assertEquals((string) EmploymentType::FIXED_TERM->value, $declaration->getEmploymentType());
        $this->assertEquals('01/01/2025', $declaration->getFixedTermFrom());
        $this->assertEquals('30/06/2025', $declaration->getFixedTermTo());
    }

    public function testPartTimeState(): void
    {
        $declaration = ModificationDeclarationFactory::new()
            ->partTime(25.0)
            ->make();

        $this->assertEquals((string) EmploymentStatus::PARTIAL->value, $declaration->getEmploymentStatus());
        $this->assertEquals(25.0, $declaration->getWeeklyHours());
    }

    public function testBorrowedState(): void
    {
        $declaration = ModificationDeclarationFactory::new()
            ->borrowed()
            ->make();

        $this->assertEquals((string) EmploymentType::BORROWED->value, $declaration->getEmploymentType());
        $this->assertNotNull($declaration->getLoanStartDate());
        $this->assertNotNull($declaration->getLoanEndDate());
        $this->assertNotNull($declaration->getLoanCompanyAfm());
        $this->assertNotNull($declaration->getLoanCompanyName());
    }

    public function testWithTrialPeriodState(): void
    {
        $declaration = ModificationDeclarationFactory::new()
            ->withTrialPeriod('30/06/2025')
            ->make();

        $this->assertEquals('1', $declaration->getTrialPeriod());
        $this->assertEquals('30/06/2025', $declaration->getTrialPeriodEndDate());
    }

    public function testWithCollectiveAgreementState(): void
    {
        $declaration = ModificationDeclarationFactory::new()
            ->withCollectiveAgreement('ΕΓΣΣΕ 2025')
            ->make();

        $this->assertEquals('1', $declaration->getCollectiveAgreementApplies());
        $this->assertEquals('ΕΓΣΣΕ 2025', $declaration->getCollectiveAgreementComment());
    }

    public function testForeignNationalDirectAccessState(): void
    {
        $declaration = ModificationDeclarationFactory::new()
            ->foreignNationalDirectAccess('002')
            ->make();

        $this->assertEquals('002', $declaration->getNationality());
        $this->assertEquals('1', $declaration->getResPermitDirectAccess());
        $this->assertNotNull($declaration->getResPermitDirectAccessType());
    }

    public function testAsManagerState(): void
    {
        $declaration = ModificationDeclarationFactory::new()
            ->asManager()
            ->make();

        $this->assertNotEquals('0', $declaration->getResponsiblePosition());
    }

    public function testAsWorkerState(): void
    {
        $declaration = ModificationDeclarationFactory::new()
            ->asWorker()
            ->make();

        $this->assertEquals((string) WorkerType::WORKER->value, $declaration->getWorkerType());
    }

    public function testMaleState(): void
    {
        $declaration = ModificationDeclarationFactory::new()
            ->male()
            ->make();

        $this->assertEquals((string) Sex::MALE->value, $declaration->getSex());
    }

    public function testFemaleState(): void
    {
        $declaration = ModificationDeclarationFactory::new()
            ->female()
            ->make();

        $this->assertEquals((string) Sex::FEMALE->value, $declaration->getSex());
    }

    public function testRemoteWorkState(): void
    {
        $declaration = ModificationDeclarationFactory::new()
            ->remoteWork('Τηλεργασία')
            ->make();

        $this->assertEquals('Τηλεργασία', $declaration->getWorkLocationComment());
    }

    public function testWithRelatedProtocolState(): void
    {
        $declaration = ModificationDeclarationFactory::new()
            ->withRelatedProtocol('MA12345', '15/01/2025')
            ->make();

        $this->assertEquals('MA12345', $declaration->getRelatedProtocol());
        $this->assertEquals('15/01/2025', $declaration->getRelatedDate());
    }

    public function testChainingMultipleStates(): void
    {
        $declaration = ModificationDeclarationFactory::new()
            ->fixedTerm()
            ->partTime(20.0)
            ->male()
            ->make();

        $this->assertEquals((string) EmploymentType::FIXED_TERM->value, $declaration->getEmploymentType());
        $this->assertEquals((string) EmploymentStatus::PARTIAL->value, $declaration->getEmploymentStatus());
        $this->assertEquals((string) Sex::MALE->value, $declaration->getSex());
    }

    public function testCountCreatesMultipleInstances(): void
    {
        $declarations = ModificationDeclarationFactory::new()
            ->count(3)
            ->make();

        $this->assertIsArray($declarations);
        $this->assertCount(3, $declarations);
        foreach ($declarations as $declaration) {
            $this->assertInstanceOf(ModificationDeclaration::class, $declaration);
        }
    }
}
