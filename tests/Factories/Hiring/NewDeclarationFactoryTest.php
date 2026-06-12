<?php

namespace Tests\Factories\Hiring;

use OxygenSuite\OxygenErgani\Enums\BasicsAcceptance;
use OxygenSuite\OxygenErgani\Enums\EmploymentStatus;
use OxygenSuite\OxygenErgani\Enums\EmploymentType;
use OxygenSuite\OxygenErgani\Enums\ResponsiblePosition;
use OxygenSuite\OxygenErgani\Enums\Sex;
use OxygenSuite\OxygenErgani\Enums\WorkerType;
use OxygenSuite\OxygenErgani\Enums\WorkLocation;
use OxygenSuite\OxygenErgani\Factories\Factory;
use OxygenSuite\OxygenErgani\Factories\Hiring\NewDeclarationFactory;
use OxygenSuite\OxygenErgani\Models\Hiring\NewDeclaration;
use PHPUnit\Framework\TestCase;

class NewDeclarationFactoryTest extends TestCase
{
    protected function setUp(): void
    {
        Factory::resetFaker();
    }

    public function testCreatesNewDeclarationInstance(): void
    {
        $declaration = NewDeclarationFactory::new()->make();

        $this->assertInstanceOf(NewDeclaration::class, $declaration);
    }

    public function testModelHasFactoryMethod(): void
    {
        $factory = NewDeclaration::factory();

        $this->assertInstanceOf(NewDeclarationFactory::class, $factory);
    }

    public function testModelFactoryWithCount(): void
    {
        $declarations = NewDeclaration::factory(3)->make();

        $this->assertIsArray($declarations);
        $this->assertCount(3, $declarations);
        foreach ($declarations as $declaration) {
            $this->assertInstanceOf(NewDeclaration::class, $declaration);
        }
    }

    public function testDefinitionGeneratesRequiredFields(): void
    {
        $declaration = NewDeclarationFactory::new()->make();

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
        $this->assertNotNull($declaration->getWeeklyHours());
        $this->assertNotNull($declaration->getGrossSalary());
    }

    public function testAfmIsValid(): void
    {
        $declaration = NewDeclarationFactory::new()->make();
        $afm = $declaration->getAfm();

        $this->assertMatchesRegularExpression('/^\d{9}$/', $afm);
    }

    public function testAmkaIsValid(): void
    {
        $declaration = NewDeclarationFactory::new()->make();
        $amka = $declaration->getAmka();

        $this->assertMatchesRegularExpression('/^\d{11}$/', $amka);
    }

    public function testDefaultsToGreekNational(): void
    {
        $declaration = NewDeclarationFactory::new()->make();

        $this->assertEquals('001', $declaration->getNationality());
        $this->assertEquals('0', $declaration->getResPermitDirectAccess());
        $this->assertEquals('0', $declaration->getResPermitApproval());
        $this->assertEquals('0', $declaration->getSeasonalWorkVisa());
    }

    public function testDefaultsToFullTimeIndefinite(): void
    {
        $declaration = NewDeclarationFactory::new()->make();

        $this->assertEquals((string) EmploymentType::INDEFINITE->value, $declaration->getEmploymentType());
        $this->assertEquals((string) EmploymentStatus::FULL->value, $declaration->getEmploymentStatus());
    }

    public function testFixedTermState(): void
    {
        $declaration = NewDeclarationFactory::new()
            ->fixedTerm('01/01/2025', '30/06/2025')
            ->make();

        $this->assertEquals((string) EmploymentType::FIXED_TERM->value, $declaration->getEmploymentType());
        $this->assertEquals('01/01/2025', $declaration->getFixedTermFrom());
        $this->assertEquals('30/06/2025', $declaration->getFixedTermTo());
    }

    public function testPartTimeState(): void
    {
        $declaration = NewDeclarationFactory::new()
            ->partTime(20.0)
            ->make();

        $this->assertEquals((string) EmploymentStatus::PARTIAL->value, $declaration->getEmploymentStatus());
        $this->assertEquals(20.0, $declaration->getWeeklyHours());
    }

    public function testWithTrialPeriodState(): void
    {
        $declaration = NewDeclarationFactory::new()
            ->withTrialPeriod('31/12/2025')
            ->make();

        $this->assertEquals('1', $declaration->getTrialPeriod());
        $this->assertEquals('31/12/2025', $declaration->getTrialPeriodEndDate());
    }

    public function testForeignNationalDirectAccessState(): void
    {
        $declaration = NewDeclarationFactory::new()
            ->foreignNationalDirectAccess('002')
            ->make();

        $this->assertEquals('002', $declaration->getNationality());
        $this->assertEquals('1', $declaration->getResPermitDirectAccess());
        $this->assertNotNull($declaration->getResPermitDirectAccessType());
        $this->assertNotNull($declaration->getResPermitDirectAccessNumber());
        $this->assertNotNull($declaration->getResPermitDirectAccessExpiry());
    }

    public function testForeignNationalApprovalState(): void
    {
        $declaration = NewDeclarationFactory::new()
            ->foreignNationalApproval('003')
            ->make();

        $this->assertEquals('003', $declaration->getNationality());
        $this->assertEquals('1', $declaration->getResPermitApproval());
        $this->assertNotNull($declaration->getResPermitApprovalType());
        $this->assertNotNull($declaration->getResPermitApprovalNumber());
        $this->assertNotNull($declaration->getResPermitApprovalExpiry());
    }

    public function testWithDypaPlacementState(): void
    {
        $declaration = NewDeclarationFactory::new()
            ->withDypaPlacement('TEST001')
            ->make();

        $this->assertEquals('1', $declaration->getDypaPlacement());
        $this->assertEquals('TEST001', $declaration->getDypaProgram());
    }

    public function testWithDypaReplacementState(): void
    {
        $declaration = NewDeclarationFactory::new()
            ->withDypaReplacement('PROG001')
            ->make();

        $this->assertEquals('1', $declaration->getDypaPlacement());
        $this->assertEquals('PROG001', $declaration->getDypaProgram());
        $this->assertEquals('1', $declaration->getReplaceProgram());
        $this->assertNotNull($declaration->getReplacedEmployeeAfm());
        $this->assertNotNull($declaration->getReplacedEmployeeAmka());
    }

    public function testWithSupplementaryInsuranceState(): void
    {
        $declaration = NewDeclarationFactory::new()
            ->withSupplementaryInsurance(['201', '301'])
            ->make();

        $selections = $declaration->getSupplementaryInsuranceSelections();
        $this->assertCount(2, $selections);
        $this->assertEquals('201', $selections[0]->getSupplementaryInsuranceCode());
        $this->assertEquals('301', $selections[1]->getSupplementaryInsuranceCode());
    }

    public function testWithUnpredictableScheduleState(): void
    {
        $declaration = NewDeclarationFactory::new()
            ->withUnpredictableSchedule()
            ->make();

        $this->assertEquals((string) EmploymentStatus::ON_DEMAND->value, $declaration->getEmploymentStatus());
        $this->assertEquals('1', $declaration->getUnpredictableSchedule());
        $this->assertNotNull($declaration->getReferenceDaysHours());
        $this->assertNotNull($declaration->getMinNotificationPeriod());
        $this->assertNotNull($declaration->getAssignmentCancellationDeadline());
    }

    public function testAsManagerState(): void
    {
        $declaration = NewDeclarationFactory::new()
            ->asManager()
            ->make();

        $this->assertEquals(ResponsiblePosition::MANAGERIAL_AUTHORITY->value, $declaration->getResponsiblePosition());
        $this->assertGreaterThanOrEqual(3000, $declaration->getGrossSalary());
    }

    public function testAsWorkerState(): void
    {
        $declaration = NewDeclarationFactory::new()
            ->asWorker()
            ->make();

        $this->assertEquals((string) WorkerType::WORKER->value, $declaration->getWorkerType());
    }

    public function testRemoteWorkState(): void
    {
        $declaration = NewDeclarationFactory::new()
            ->remoteWork('Remote from Athens')
            ->make();

        $this->assertEquals((string) WorkLocation::OTHER->value, $declaration->getWorkLocation());
        $this->assertEquals('Remote from Athens', $declaration->getWorkLocationComment());
    }

    public function testMaleState(): void
    {
        $declaration = NewDeclarationFactory::new()
            ->male()
            ->make();

        $this->assertEquals((string) Sex::MALE->value, $declaration->getSex());
    }

    public function testFemaleState(): void
    {
        $declaration = NewDeclarationFactory::new()
            ->female()
            ->make();

        $this->assertEquals((string) Sex::FEMALE->value, $declaration->getSex());
    }

    public function testWithAcceptanceFileState(): void
    {
        $declaration = NewDeclarationFactory::new()
            ->withAcceptanceFile('base64content')
            ->make();

        $this->assertEquals((string) BasicsAcceptance::WITH_FILE->value, $declaration->getBasicsAcceptance());
        $this->assertEquals('base64content', $declaration->getFile());
    }

    public function testWithCollectiveAgreementState(): void
    {
        $declaration = NewDeclarationFactory::new()
            ->withCollectiveAgreement('National Agreement')
            ->make();

        $this->assertEquals('1', $declaration->getCollectiveAgreementApplicable());
        $this->assertEquals('National Agreement', $declaration->getCollectiveAgreementComments());
    }

    public function testChainingMultipleStates(): void
    {
        $declaration = NewDeclarationFactory::new()
            ->fixedTerm()
            ->partTime(25.0)
            ->withTrialPeriod()
            ->male()
            ->make();

        $this->assertEquals((string) EmploymentType::FIXED_TERM->value, $declaration->getEmploymentType());
        $this->assertEquals((string) EmploymentStatus::PARTIAL->value, $declaration->getEmploymentStatus());
        $this->assertEquals('1', $declaration->getTrialPeriod());
        $this->assertEquals((string) Sex::MALE->value, $declaration->getSex());
        $this->assertEquals(25.0, $declaration->getWeeklyHours());
    }

    public function testCountCreatesMultipleInstances(): void
    {
        $declarations = NewDeclarationFactory::new()
            ->count(3)
            ->make();

        $this->assertIsArray($declarations);
        $this->assertCount(3, $declarations);
        foreach ($declarations as $declaration) {
            $this->assertInstanceOf(NewDeclaration::class, $declaration);
        }
    }

    public function testStateCanOverrideDefinition(): void
    {
        $declaration = NewDeclarationFactory::new()
            ->state(['f_eponymo' => 'ΤΕΣΤΟΠΟΥΛΟΣ'])
            ->make();

        $this->assertEquals('ΤΕΣΤΟΠΟΥΛΟΣ', $declaration->getLastName());
    }

    public function testMakeCanOverrideState(): void
    {
        $declaration = NewDeclarationFactory::new()
            ->state(['f_eponymo' => 'STATE'])
            ->make(['f_eponymo' => 'OVERRIDE']);

        $this->assertEquals('OVERRIDE', $declaration->getLastName());
    }

    public function testExceptExcludesFields(): void
    {
        $declaration = NewDeclarationFactory::new()
            ->except(['f_afm', 'f_amka'])
            ->make();

        $this->assertNull($declaration->getAfm());
        $this->assertNull($declaration->getAmka());
    }

    public function testWithRelatedProtocolState(): void
    {
        $declaration = NewDeclarationFactory::new()
            ->withRelatedProtocol('E3N12345', '15/01/2025')
            ->make();

        $this->assertEquals('E3N12345', $declaration->getRelatedProtocol());
        $this->assertEquals('15/01/2025', $declaration->getRelatedDate());
    }

    public function testAsMinorState(): void
    {
        $declaration = NewDeclarationFactory::new()
            ->asMinor('BOOK123')
            ->make();

        $this->assertEquals('BOOK123', $declaration->getMinorWorkBookNumber());
        $this->assertNotNull($declaration->getMinorWorkerFile());

        // Verify birth date is within minor age range (15-17 years old)
        $birthDate = \DateTimeImmutable::createFromFormat('d/m/Y', $declaration->getBirthDate());
        $this->assertNotFalse($birthDate);
        $age = (int) $birthDate->diff(new \DateTimeImmutable())->y;
        $this->assertLessThanOrEqual(17, $age);
        $this->assertGreaterThanOrEqual(15, $age);
    }

    public function testWithSeasonalVisaState(): void
    {
        $declaration = NewDeclarationFactory::new()
            ->withSeasonalVisa('004')
            ->make();

        $this->assertEquals('004', $declaration->getNationality());
        $this->assertEquals('1', $declaration->getSeasonalWorkVisa());
        $this->assertNotNull($declaration->getSeasonalWorkVisaNumber());
        $this->assertNotNull($declaration->getSeasonalWorkVisaFrom());
        $this->assertNotNull($declaration->getSeasonalWorkVisaTo());
    }
}
