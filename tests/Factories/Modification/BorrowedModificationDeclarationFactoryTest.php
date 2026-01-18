<?php

namespace Tests\Factories\Modification;

use OxygenSuite\OxygenErgani\Enums\EmploymentStatus;
use OxygenSuite\OxygenErgani\Enums\LoanType;
use OxygenSuite\OxygenErgani\Enums\SalaryPaymentSource;
use OxygenSuite\OxygenErgani\Enums\Sex;
use OxygenSuite\OxygenErgani\Enums\WorkerType;
use OxygenSuite\OxygenErgani\Factories\Factory;
use OxygenSuite\OxygenErgani\Factories\Modification\BorrowedModificationDeclarationFactory;
use OxygenSuite\OxygenErgani\Models\Modification\BorrowedModificationDeclaration;
use PHPUnit\Framework\TestCase;

class BorrowedModificationDeclarationFactoryTest extends TestCase
{
    protected function setUp(): void
    {
        Factory::resetFaker();
    }

    public function testCreatesDeclarationInstance(): void
    {
        $declaration = BorrowedModificationDeclarationFactory::new()->make();

        $this->assertInstanceOf(BorrowedModificationDeclaration::class, $declaration);
    }

    public function testModelHasFactoryMethod(): void
    {
        $factory = BorrowedModificationDeclaration::factory();

        $this->assertInstanceOf(BorrowedModificationDeclarationFactory::class, $factory);
    }

    public function testDefinitionGeneratesRequiredFields(): void
    {
        $declaration = BorrowedModificationDeclarationFactory::new()->make();

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

        // MAD specific - borrow details are required
        $this->assertNotNull($declaration->getChangeDate());
        $this->assertNotNull($declaration->getLoanType());
        $this->assertNotNull($declaration->getLoanStartDate());
        $this->assertNotNull($declaration->getLoanEndDate());
        $this->assertNotNull($declaration->getLoanCompanyAfm());
        $this->assertNotNull($declaration->getLoanCompanyName());
        $this->assertNotNull($declaration->getSalaryPaymentSource());
    }

    public function testAfmIsValid(): void
    {
        $declaration = BorrowedModificationDeclarationFactory::new()->make();
        $afm = $declaration->getAfm();

        $this->assertMatchesRegularExpression('/^\d{9}$/', $afm);
    }

    public function testDefaultsToGenuineLoan(): void
    {
        $declaration = BorrowedModificationDeclarationFactory::new()->make();

        $this->assertEquals((string) LoanType::GENUINE->value, $declaration->getLoanType());
    }

    public function testDefaultsToDirectEmployerPayment(): void
    {
        $declaration = BorrowedModificationDeclarationFactory::new()->make();

        $this->assertEquals((string) SalaryPaymentSource::DIRECT_EMPLOYER->value, $declaration->getSalaryPaymentSource());
    }

    public function testGenuineLoanState(): void
    {
        $declaration = BorrowedModificationDeclarationFactory::new()
            ->genuineLoan()
            ->make();

        $this->assertEquals((string) LoanType::GENUINE->value, $declaration->getLoanType());
    }

    public function testEpaLoanState(): void
    {
        $declaration = BorrowedModificationDeclarationFactory::new()
            ->epaLoan()
            ->make();

        $this->assertEquals((string) LoanType::EPA->value, $declaration->getLoanType());
    }

    public function testPaidByDirectEmployerState(): void
    {
        $declaration = BorrowedModificationDeclarationFactory::new()
            ->paidByDirectEmployer()
            ->make();

        $this->assertEquals((string) SalaryPaymentSource::DIRECT_EMPLOYER->value, $declaration->getSalaryPaymentSource());
    }

    public function testPaidByIndirectEmployerState(): void
    {
        $declaration = BorrowedModificationDeclarationFactory::new()
            ->paidByIndirectEmployer(1500.00, 10.00)
            ->make();

        $this->assertEquals((string) SalaryPaymentSource::INDIRECT_EMPLOYER->value, $declaration->getSalaryPaymentSource());
        $this->assertEquals(1500.0, $declaration->getGrossSalary());
        $this->assertEquals(10.0, $declaration->getHourlyWage());
    }

    public function testPartTimeState(): void
    {
        $declaration = BorrowedModificationDeclarationFactory::new()
            ->partTime(25.0)
            ->make();

        $this->assertEquals((string) EmploymentStatus::PARTIAL->value, $declaration->getEmploymentStatus());
        $this->assertEquals(25.0, $declaration->getWeeklyHours());
    }

    public function testWithCollectiveAgreementState(): void
    {
        $declaration = BorrowedModificationDeclarationFactory::new()
            ->withCollectiveAgreement('ΕΓΣΣΕ')
            ->make();

        $this->assertEquals('1', $declaration->getCollectiveAgreementApplies());
        $this->assertEquals('ΕΓΣΣΕ', $declaration->getCollectiveAgreementComment());
    }

    public function testForeignNationalDirectAccessState(): void
    {
        $declaration = BorrowedModificationDeclarationFactory::new()
            ->foreignNationalDirectAccess('002')
            ->make();

        $this->assertEquals('002', $declaration->getNationality());
        $this->assertEquals('1', $declaration->getResPermitDirectAccess());
    }

    public function testAsWorkerState(): void
    {
        $declaration = BorrowedModificationDeclarationFactory::new()
            ->asWorker()
            ->make();

        $this->assertEquals((string) WorkerType::WORKER->value, $declaration->getWorkerType());
    }

    public function testMaleState(): void
    {
        $declaration = BorrowedModificationDeclarationFactory::new()
            ->male()
            ->make();

        $this->assertEquals((string) Sex::MALE->value, $declaration->getSex());
    }

    public function testFemaleState(): void
    {
        $declaration = BorrowedModificationDeclarationFactory::new()
            ->female()
            ->make();

        $this->assertEquals((string) Sex::FEMALE->value, $declaration->getSex());
    }

    public function testRemoteWorkState(): void
    {
        $declaration = BorrowedModificationDeclarationFactory::new()
            ->remoteWork('Τηλεργασία')
            ->make();

        $this->assertEquals('Τηλεργασία', $declaration->getWorkLocationComment());
    }

    public function testWithRelatedProtocolState(): void
    {
        $declaration = BorrowedModificationDeclarationFactory::new()
            ->withRelatedProtocol('MAD12345', '15/01/2025')
            ->make();

        $this->assertEquals('MAD12345', $declaration->getRelatedProtocol());
        $this->assertEquals('15/01/2025', $declaration->getRelatedDate());
    }

    public function testChainingMultipleStates(): void
    {
        $declaration = BorrowedModificationDeclarationFactory::new()
            ->epaLoan()
            ->paidByIndirectEmployer(1800.00, 12.00)
            ->partTime(20.0)
            ->male()
            ->make();

        $this->assertEquals((string) LoanType::EPA->value, $declaration->getLoanType());
        $this->assertEquals((string) SalaryPaymentSource::INDIRECT_EMPLOYER->value, $declaration->getSalaryPaymentSource());
        $this->assertEquals((string) EmploymentStatus::PARTIAL->value, $declaration->getEmploymentStatus());
        $this->assertEquals((string) Sex::MALE->value, $declaration->getSex());
    }

    public function testCountCreatesMultipleInstances(): void
    {
        $declarations = BorrowedModificationDeclarationFactory::new()
            ->count(3)
            ->make();

        $this->assertIsArray($declarations);
        $this->assertCount(3, $declarations);
        foreach ($declarations as $declaration) {
            $this->assertInstanceOf(BorrowedModificationDeclaration::class, $declaration);
        }
    }
}
