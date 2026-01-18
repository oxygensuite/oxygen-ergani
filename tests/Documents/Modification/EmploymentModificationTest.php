<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace Tests\Documents\Modification;

use OxygenSuite\OxygenErgani\Enums\EmploymentStatus;
use OxygenSuite\OxygenErgani\Enums\EmploymentType;
use OxygenSuite\OxygenErgani\Enums\SettlementType;
use OxygenSuite\OxygenErgani\Enums\WorkerType;
use OxygenSuite\OxygenErgani\Factories\Factory;
use OxygenSuite\OxygenErgani\Http\Documents\Modification\EmploymentModification;
use OxygenSuite\OxygenErgani\Models\Modification\ModificationDeclaration;
use OxygenSuite\OxygenErgani\Models\Modification\ModificationTypeSelection;
use OxygenSuite\OxygenErgani\Models\Hiring\SupplementaryInsuranceSelection;
use Tests\TestCase;

class EmploymentModificationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Factory::resetFaker();
    }

    public function test_submit(): void
    {
        $declaration = ModificationDeclaration::factory()->make();

        $document = new EmploymentModification('test-access-token');
        $document->getConfig()->setHandler($this->mockResponse(200, 'employment-modification.json'));
        $response = $document->handle($declaration);

        $this->assertIsArray($response);
        $this->assertSame('700', $response[0]->id);
        $this->assertSame('MA700', $response[0]->protocol);
        $this->assertSame('17/01/2026 11:30', $response[0]->submissionDate->format('d/m/Y H:i'));
    }

    public function test_submit_with_multiple_declarations(): void
    {
        $declarations = ModificationDeclaration::factory(2)->make();

        $document = new EmploymentModification('test-access-token');
        $document->getConfig()->setHandler($this->mockResponse(200, 'employment-modification.json'));
        $response = $document->handle($declarations);

        $this->assertIsArray($response);
        $this->assertSame('700', $response[0]->id);
    }

    public function test_submit_with_modification_types(): void
    {
        $declaration = ModificationDeclaration::factory()
            ->withModificationTypes(['01', '02'])
            ->make();

        $document = new EmploymentModification('test-access-token');
        $document->getConfig()->setHandler($this->mockResponse(200, 'employment-modification.json'));
        $response = $document->handle($declaration);

        $this->assertIsArray($response);
        $this->assertSame('700', $response[0]->id);
    }

    public function test_submit_with_supplementary_insurance(): void
    {
        $declaration = ModificationDeclaration::factory()
            ->withSupplementaryInsurance(['201', '202'])
            ->make();

        $document = new EmploymentModification('test-access-token');
        $document->getConfig()->setHandler($this->mockResponse(200, 'employment-modification.json'));
        $response = $document->handle($declaration);

        $this->assertIsArray($response);
        $this->assertSame('700', $response[0]->id);
    }

    public function test_model_settlement_fields(): void
    {
        $declaration = ModificationDeclaration::make()
            ->setSettlementType(SettlementType::COLLECTIVE)
            ->setSettlementTypeComment('SSE 2025')
            ->setReferencePeriodFrom('01/01/2026')
            ->setReferencePeriodTo('31/12/2026');

        $this->assertSame('0', $declaration->getSettlementType());
        $this->assertSame('SSE 2025', $declaration->getSettlementTypeComment());
        $this->assertSame('01/01/2026', $declaration->getReferencePeriodFrom());
        $this->assertSame('31/12/2026', $declaration->getReferencePeriodTo());
    }

    public function test_model_change_date(): void
    {
        $declaration = ModificationDeclaration::make()
            ->setChangeDate('17/01/2026');

        $this->assertSame('17/01/2026', $declaration->getChangeDate());
    }

    public function test_model_employment_type(): void
    {
        $declaration = ModificationDeclaration::make()
            ->setEmploymentType(EmploymentType::FIXED_TERM)
            ->setFixedTermFrom('01/01/2026')
            ->setFixedTermTo('30/06/2026');

        $this->assertSame('1', $declaration->getEmploymentType());
        $this->assertSame('01/01/2026', $declaration->getFixedTermFrom());
        $this->assertSame('30/06/2026', $declaration->getFixedTermTo());
    }

    public function test_model_employment_type_borrowed(): void
    {
        $declaration = ModificationDeclaration::make()
            ->setEmploymentType(EmploymentType::BORROWED);

        $this->assertSame('3', $declaration->getEmploymentType());
    }

    public function test_model_salary_fields(): void
    {
        $declaration = ModificationDeclaration::make()
            ->setGrossSalary(1500.00)
            ->setHourlyWage(10.50)
            ->setSalaryPaymentTiming('Monthly');

        $this->assertSame(1500.0, $declaration->getGrossSalary());
        $this->assertSame(10.5, $declaration->getHourlyWage());
        $this->assertSame('Monthly', $declaration->getSalaryPaymentTiming());
    }

    public function test_model_employment_status(): void
    {
        $declaration = ModificationDeclaration::make()
            ->setEmploymentStatus(EmploymentStatus::PARTIAL)
            ->setWorkerType(WorkerType::EMPLOYEE);

        $this->assertSame('1', $declaration->getEmploymentStatus());
        $this->assertSame('1', $declaration->getWorkerType());
    }

    public function test_model_insurance_fields(): void
    {
        $declaration = ModificationDeclaration::make()
            ->setPrimaryInsurance('101')
            ->setAdditionalInsuranceBenefits('Additional benefits')
            ->setMandatoryTraining(true);

        $this->assertSame('101', $declaration->getPrimaryInsurance());
        $this->assertSame('Additional benefits', $declaration->getAdditionalInsuranceBenefits());
        $this->assertSame('1', $declaration->getMandatoryTraining());
    }

    public function test_model_trial_period(): void
    {
        $declaration = ModificationDeclaration::make()
            ->setTrialPeriod(true)
            ->setTrialPeriodEndDate('17/04/2026');

        $this->assertSame('1', $declaration->getTrialPeriod());
        $this->assertSame('17/04/2026', $declaration->getTrialPeriodEndDate());
    }

    public function test_model_dypa_fields(): void
    {
        $declaration = ModificationDeclaration::make()
            ->setDypaPlacement(true)
            ->setDypaProgram('PROGRAM001');

        $this->assertSame('1', $declaration->getDypaPlacement());
        $this->assertSame('PROGRAM001', $declaration->getDypaProgram());
    }

    public function test_model_acceptance_fields(): void
    {
        $declaration = ModificationDeclaration::make()
            ->setBasicsAcceptance(true)
            ->setAcceptanceFile('SGVsbG8sIHdvcmxkIQ==')
            ->setRotationDecisionFile('Q29udHJhY3QgZmlsZQ==');

        $this->assertSame('1', $declaration->getBasicsAcceptance());
        $this->assertSame('SGVsbG8sIHdvcmxkIQ==', $declaration->getAcceptanceFile());
        $this->assertSame('Q29udHJhY3QgZmlsZQ==', $declaration->getRotationDecisionFile());
    }

    public function test_model_modification_types(): void
    {
        $selection1 = ModificationTypeSelection::make()
            ->setModificationTypeCode('01');

        $selection2 = ModificationTypeSelection::make()
            ->setModificationTypeCode('02');

        $declaration = ModificationDeclaration::make()
            ->addModificationTypeSelection($selection1)
            ->addModificationTypeSelection($selection2);

        $selections = $declaration->getModificationTypeSelections();
        $this->assertCount(2, $selections);
        $this->assertSame('01', $selections[0]->getModificationTypeCode());
        $this->assertSame('02', $selections[1]->getModificationTypeCode());
    }

    public function test_model_supplementary_insurance(): void
    {
        $selection1 = SupplementaryInsuranceSelection::make()
            ->setSupplementaryInsuranceCode('201');

        $selection2 = SupplementaryInsuranceSelection::make()
            ->setSupplementaryInsuranceCode('202');

        $declaration = ModificationDeclaration::make()
            ->addSupplementaryInsuranceSelection($selection1)
            ->addSupplementaryInsuranceSelection($selection2);

        $selections = $declaration->getSupplementaryInsuranceSelections();
        $this->assertCount(2, $selections);
        $this->assertSame('201', $selections[0]->getSupplementaryInsuranceCode());
        $this->assertSame('202', $selections[1]->getSupplementaryInsuranceCode());
    }

    public function test_model_to_sorted_array(): void
    {
        $declaration = ModificationDeclaration::factory()
            ->withModificationTypes(['01'])
            ->withSupplementaryInsurance(['201'])
            ->make();

        $array = $declaration->toSortedArray();

        // Branch/Location
        $this->assertArrayHasKey('f_aa_pararthmatos', $array);
        $this->assertArrayHasKey('f_ypiresia_sepe', $array);
        $this->assertArrayHasKey('f_ypiresia_oaed', $array);

        // Personal
        $this->assertArrayHasKey('f_eponymo', $array);
        $this->assertArrayHasKey('f_onoma', $array);
        $this->assertArrayHasKey('f_birthdate', $array);

        // Change Details
        $this->assertArrayHasKey('f_date_metabolhs', $array);
        $this->assertArrayHasKey('f_eidos_dieuthethshs', $array);

        // Employment
        $this->assertArrayHasKey('f_sxeshapasxolisis', $array);
        $this->assertArrayHasKey('f_kathestosapasxolisis', $array);
        $this->assertArrayHasKey('f_xaraktirismos', $array);

        // Insurance
        $this->assertArrayHasKey('f_kyria_asfalish', $array);

        // Work Organization
        $this->assertArrayHasKey('f_working_time_digital_organization', $array);
        $this->assertArrayHasKey('f_week_hours', $array);
        $this->assertArrayHasKey('f_working_card', $array);

        // Nested Arrays
        $this->assertArrayHasKey('ModificationTypeSelections', $array);
        $this->assertArrayHasKey('SupplementaryInsuranceSelections', $array);
    }

    public function test_greek_float_casting(): void
    {
        $declaration = ModificationDeclaration::make()
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

    public function test_factory_states(): void
    {
        $declaration = ModificationDeclaration::factory()
            ->fixedTerm('01/01/2026', '30/06/2026')
            ->partTime(25.0)
            ->withSettlement(SettlementType::INDIVIDUAL, 'Individual agreement')
            ->withTrialPeriod('17/04/2026')
            ->make();

        $this->assertSame('1', $declaration->get('f_sxeshapasxolisis'));
        $this->assertSame('01/01/2026', $declaration->get('f_orismenou_apo'));
        $this->assertSame('30/06/2026', $declaration->get('f_orismenou_ews'));
        $this->assertSame('1', $declaration->get('f_kathestosapasxolisis'));
        $this->assertSame(25.0, $declaration->getWeeklyHours());
        $this->assertSame('1', $declaration->get('f_eidos_dieuthethshs'));
        $this->assertSame('Individual agreement', $declaration->get('f_eidos_dieuthethshs_comments'));
        $this->assertSame('1', $declaration->get('f_trial_period'));
        $this->assertSame('17/04/2026', $declaration->get('f_trial_date_to'));
    }

    public function test_factory_borrowed_state(): void
    {
        $declaration = ModificationDeclaration::factory()
            ->borrowed()
            ->make();

        $this->assertSame('3', $declaration->get('f_sxeshapasxolisis'));
        $this->assertSame('0', $declaration->get('f_borrow_type'));
        $this->assertNotNull($declaration->get('f_borrow_company_afm'));
        $this->assertMatchesRegularExpression('/^\d{9}$/', $declaration->get('f_borrow_company_afm'));
    }

    public function test_modification_type_selection_model(): void
    {
        $selection = ModificationTypeSelection::make()
            ->setModificationTypeCode('01');

        $this->assertSame('01', $selection->getModificationTypeCode());

        $array = $selection->toSortedArray();
        $this->assertArrayHasKey('f_typos_metabolhs', $array);
        $this->assertSame('01', $array['f_typos_metabolhs']);
    }
}
