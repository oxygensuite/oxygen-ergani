<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace Tests\Documents\Modification;

use OxygenSuite\OxygenErgani\Enums\EmploymentStatus;
use OxygenSuite\OxygenErgani\Enums\LoanType;
use OxygenSuite\OxygenErgani\Enums\SalaryPaymentSource;
use OxygenSuite\OxygenErgani\Enums\WorkerType;
use OxygenSuite\OxygenErgani\Factories\Factory;
use OxygenSuite\OxygenErgani\Http\Documents\Modification\BorrowedEmploymentModification;
use OxygenSuite\OxygenErgani\Models\Modification\BorrowedModificationDeclaration;
use Tests\TestCase;

class BorrowedEmploymentModificationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Factory::resetFaker();
    }

    public function test_submit(): void
    {
        $declaration = BorrowedModificationDeclaration::factory()->make();

        $document = new BorrowedEmploymentModification('test-access-token');
        $document->getConfig()->setHandler($this->mockResponse(200, 'borrowed-employment-modification.json'));
        $response = $document->handle($declaration);

        $this->assertIsArray($response);
        $this->assertSame('800', $response[0]->id);
        $this->assertSame('MAD800', $response[0]->protocol);
        $this->assertSame('17/01/2026 12:30', $response[0]->submissionDate->format('d/m/Y H:i'));
    }

    public function test_submit_with_multiple_declarations(): void
    {
        $declarations = BorrowedModificationDeclaration::factory(2)->make();

        $document = new BorrowedEmploymentModification('test-access-token');
        $document->getConfig()->setHandler($this->mockResponse(200, 'borrowed-employment-modification.json'));
        $response = $document->handle($declarations);

        $this->assertIsArray($response);
        $this->assertSame('800', $response[0]->id);
    }

    public function test_submit_genuine_loan(): void
    {
        $declaration = BorrowedModificationDeclaration::factory()
            ->genuineLoan()
            ->make();

        $document = new BorrowedEmploymentModification('test-access-token');
        $document->getConfig()->setHandler($this->mockResponse(200, 'borrowed-employment-modification.json'));
        $response = $document->handle($declaration);

        $this->assertIsArray($response);
        $this->assertSame('800', $response[0]->id);
    }

    public function test_submit_epa_loan(): void
    {
        $declaration = BorrowedModificationDeclaration::factory()
            ->epaLoan()
            ->make();

        $document = new BorrowedEmploymentModification('test-access-token');
        $document->getConfig()->setHandler($this->mockResponse(200, 'borrowed-employment-modification.json'));
        $response = $document->handle($declaration);

        $this->assertIsArray($response);
        $this->assertSame('800', $response[0]->id);
    }

    public function test_model_borrow_fields(): void
    {
        $declaration = BorrowedModificationDeclaration::make()
            ->setLoanType(LoanType::GENUINE)
            ->setLoanStartDate('01/01/2026')
            ->setLoanEndDate('31/03/2026')
            ->setLoanCompanyAfm('999888777')
            ->setLoanCompanyName('LOAN COMPANY');

        $this->assertSame('0', $declaration->getLoanType());
        $this->assertSame('01/01/2026', $declaration->getLoanStartDate());
        $this->assertSame('31/03/2026', $declaration->getLoanEndDate());
        $this->assertSame('999888777', $declaration->getLoanCompanyAfm());
        $this->assertSame('LOAN COMPANY', $declaration->getLoanCompanyName());
    }

    public function test_model_salary_payment_source(): void
    {
        $declaration = BorrowedModificationDeclaration::make()
            ->setSalaryPaymentSource(SalaryPaymentSource::DIRECT_EMPLOYER);

        $this->assertSame('0', $declaration->getSalaryPaymentSource());

        $declaration->setSalaryPaymentSource(SalaryPaymentSource::INDIRECT_EMPLOYER);
        $this->assertSame('1', $declaration->getSalaryPaymentSource());
    }

    public function test_model_salary_fields_when_paid_by_indirect(): void
    {
        $declaration = BorrowedModificationDeclaration::make()
            ->setSalaryPaymentSource(SalaryPaymentSource::INDIRECT_EMPLOYER)
            ->setGrossSalary(1500.00)
            ->setHourlyWage(10.50)
            ->setSalaryPaymentTiming('Monthly');

        $this->assertSame('1', $declaration->getSalaryPaymentSource());
        $this->assertSame(1500.0, $declaration->getGrossSalary());
        $this->assertSame(10.5, $declaration->getHourlyWage());
        $this->assertSame('Monthly', $declaration->getSalaryPaymentTiming());
    }

    public function test_model_employment_status(): void
    {
        $declaration = BorrowedModificationDeclaration::make()
            ->setEmploymentStatus(EmploymentStatus::FULL)
            ->setWorkerType(WorkerType::EMPLOYEE);

        $this->assertSame('0', $declaration->getEmploymentStatus());
        $this->assertSame('1', $declaration->getWorkerType());
    }

    public function test_model_change_date(): void
    {
        $declaration = BorrowedModificationDeclaration::make()
            ->setChangeDate('17/01/2026');

        $this->assertSame('17/01/2026', $declaration->getChangeDate());
    }

    public function test_model_specialty(): void
    {
        $declaration = BorrowedModificationDeclaration::make()
            ->setSpecialtyCode('313200')
            ->setSpecialtyDescription('Software Engineer');

        $this->assertSame('313200', $declaration->getSpecialtyCode());
        $this->assertSame('Software Engineer', $declaration->getSpecialtyDescription());
    }

    public function test_model_collective_agreement(): void
    {
        $declaration = BorrowedModificationDeclaration::make()
            ->setCollectiveAgreementApplies(true)
            ->setCollectiveAgreementComment('EGSSE 2025');

        $this->assertSame('1', $declaration->getCollectiveAgreementApplies());
        $this->assertSame('EGSSE 2025', $declaration->getCollectiveAgreementComment());
    }

    public function test_model_work_organization(): void
    {
        $declaration = BorrowedModificationDeclaration::make()
            ->setWorkingTimeDigitalOrganization(true)
            ->setWeeklyHours(40.0)
            ->setFullEmploymentHours(40.0)
            ->setWeekDays(5)
            ->setWorkingCard(true)
            ->setBreakMinutes(30)
            ->setBreakWithinSchedule(true);

        $this->assertSame('1', $declaration->getWorkingTimeDigitalOrganization());
        $this->assertSame(40.0, $declaration->getWeeklyHours());
        $this->assertSame(40.0, $declaration->getFullEmploymentHours());
        $this->assertSame('5', $declaration->getWeekDays());
        $this->assertSame('1', $declaration->getWorkingCard());
        $this->assertSame(30, $declaration->getBreakMinutes());
        $this->assertSame('1', $declaration->getBreakWithinSchedule());
    }

    public function test_model_to_sorted_array(): void
    {
        $declaration = BorrowedModificationDeclaration::factory()->make();

        $array = $declaration->toSortedArray();

        // Branch/Location
        $this->assertArrayHasKey('f_aa_pararthmatos', $array);
        $this->assertArrayHasKey('f_ypiresia_sepe', $array);
        $this->assertArrayHasKey('f_ypiresia_oaed', $array);

        // Personal
        $this->assertArrayHasKey('f_eponymo', $array);
        $this->assertArrayHasKey('f_onoma', $array);
        $this->assertArrayHasKey('f_birthdate', $array);

        // Change Date
        $this->assertArrayHasKey('f_date_metabolhs', $array);

        // Borrow Details (Required for MAD)
        $this->assertArrayHasKey('f_borrow_type', $array);
        $this->assertArrayHasKey('f_borrow_date_from', $array);
        $this->assertArrayHasKey('f_borrow_date_to', $array);
        $this->assertArrayHasKey('f_borrow_company_afm', $array);
        $this->assertArrayHasKey('f_borrow_company_eponimia', $array);

        // Salary Payment Source (MAD-only)
        $this->assertArrayHasKey('f_kataboli_apodoxon', $array);

        // Employment Status
        $this->assertArrayHasKey('f_kathestosapasxolisis', $array);
        $this->assertArrayHasKey('f_xaraktirismos', $array);

        // Work Organization
        $this->assertArrayHasKey('f_working_time_digital_organization', $array);
        $this->assertArrayHasKey('f_week_hours', $array);
        $this->assertArrayHasKey('f_working_card', $array);
    }

    public function test_greek_float_casting(): void
    {
        $declaration = BorrowedModificationDeclaration::make()
            ->setSalaryPaymentSource(SalaryPaymentSource::INDIRECT_EMPLOYER)
            ->setGrossSalary(1500.00)
            ->setHourlyWage(10.50)
            ->setWeeklyHours(38.0)
            ->setFullEmploymentHours(40.0);

        $array = $declaration->toSortedArray();

        $this->assertSame('1.500,00', $array['f_apodoxes']);
        $this->assertSame('10,50', $array['f_hour_apodoxes']);
        $this->assertSame('38,0', $array['f_week_hours']);
        $this->assertSame('40,0', $array['f_full_employment_hours']);
    }

    public function test_factory_paid_by_direct_employer(): void
    {
        $declaration = BorrowedModificationDeclaration::factory()
            ->paidByDirectEmployer()
            ->make();

        $this->assertSame('0', $declaration->get('f_kataboli_apodoxon'));
        $this->assertEmpty($declaration->get('f_apodoxes'));
        $this->assertEmpty($declaration->get('f_hour_apodoxes'));
    }

    public function test_factory_paid_by_indirect_employer(): void
    {
        $declaration = BorrowedModificationDeclaration::factory()
            ->paidByIndirectEmployer(1800.00, 12.00)
            ->make();

        $this->assertSame('1', $declaration->get('f_kataboli_apodoxon'));
        $this->assertSame(1800.0, $declaration->getGrossSalary());
        $this->assertSame(12.0, $declaration->getHourlyWage());
    }

    public function test_factory_states(): void
    {
        $declaration = BorrowedModificationDeclaration::factory()
            ->epaLoan()
            ->partTime(25.0)
            ->withCollectiveAgreement('EGSSE')
            ->make();

        $this->assertSame('1', $declaration->get('f_borrow_type'));
        $this->assertSame('1', $declaration->get('f_kathestosapasxolisis'));
        $this->assertSame(25.0, $declaration->getWeeklyHours());
        $this->assertSame('1', $declaration->get('f_efarmostea_sillogiki_simbasi'));
        $this->assertSame('EGSSE', $declaration->get('f_efarmostea_sillogiki_simbasi_comments'));
    }

    public function test_factory_foreign_national(): void
    {
        $declaration = BorrowedModificationDeclaration::factory()
            ->foreignNationalDirectAccess('002')
            ->make();

        $this->assertSame('002', $declaration->get('f_yphkoothta'));
        $this->assertSame('1', $declaration->get('f_res_permit_inst'));
        $this->assertNotEmpty($declaration->get('f_res_permit_inst_type'));
        $this->assertNotEmpty($declaration->get('f_res_permit_inst_ar'));
    }
}
