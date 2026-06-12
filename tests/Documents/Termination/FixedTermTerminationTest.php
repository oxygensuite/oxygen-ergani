<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace Tests\Documents\Termination;

use OxygenSuite\OxygenErgani\Enums\EmploymentStatus;
use OxygenSuite\OxygenErgani\Enums\EmploymentType;
use OxygenSuite\OxygenErgani\Enums\FixedTermTerminationReason;
use OxygenSuite\OxygenErgani\Enums\WorkerType;
use OxygenSuite\OxygenErgani\Http\Documents\Termination\FixedTermTermination;
use OxygenSuite\OxygenErgani\Models\Termination\FixedTermTerminationDeclaration;
use Tests\TestCase;

class FixedTermTerminationTest extends TestCase
{
    public function test_submit(): void
    {
        $declaration = FixedTermTerminationDeclaration::factory()
            ->withSalary(1500.00)
            ->expiredContract()
            ->make([
                'f_afm' => '999999999',
                'f_comments' => 'Contract expired naturally',
            ]);

        $document = new FixedTermTermination('test-access-token');
        $document->getConfig()->setHandler($this->mockResponse(200, 'termination-fixed-term.json'));
        $response = $document->handle($declaration);

        $this->assertIsArray($response);
        $this->assertSame('701', $response[0]->id);
        $this->assertSame('E7N701', $response[0]->protocol);
        $this->assertSame('17/01/2026 11:00', $response[0]->submissionDate->format('d/m/Y H:i'));
    }

    public function test_submit_with_multiple_declarations(): void
    {
        $declaration1 = FixedTermTerminationDeclaration::factory()->make();
        $declaration2 = FixedTermTerminationDeclaration::factory()->make([
            'f_afm' => '888888888',
            'f_eponymo' => 'ΑΛΛΟΣ',
            'f_onoma' => 'ΚΑΠΟΙΟΣ',
        ]);

        $document = new FixedTermTermination('test-access-token');
        $document->getConfig()->setHandler($this->mockResponse(200, 'termination-fixed-term.json'));
        $response = $document->handle([$declaration1, $declaration2]);

        $this->assertIsArray($response);
        $this->assertSame('701', $response[0]->id);
    }

    public function test_model_employment_relationship(): void
    {
        // Fixed-term (value 1) - using integer
        $declaration = FixedTermTerminationDeclaration::make()
            ->setEmploymentRelationship(1);
        $this->assertSame('1', $declaration->getEmploymentRelationship());

        // Project-based (value 2) - using integer
        $declaration = FixedTermTerminationDeclaration::make()
            ->setEmploymentRelationship(2);
        $this->assertSame('2', $declaration->getEmploymentRelationship());

        // Using EmploymentType enum
        $declaration = FixedTermTerminationDeclaration::make()
            ->setEmploymentRelationship(EmploymentType::FIXED_TERM);
        $this->assertSame('1', $declaration->getEmploymentRelationship());

        $declaration = FixedTermTerminationDeclaration::make()
            ->setEmploymentRelationship(EmploymentType::PROJECT);
        $this->assertSame('2', $declaration->getEmploymentRelationship());
    }

    public function test_model_compensation_clause(): void
    {
        $declaration = FixedTermTerminationDeclaration::make()
            ->setCompensationClause(false);
        $this->assertFalse($declaration->hasCompensationClause());

        $declaration->setCompensationClause(true);
        $this->assertTrue($declaration->hasCompensationClause());
    }

    public function test_model_termination_reasons(): void
    {
        // Test all 5 termination reason values (0, 3, 4, 5, 6)
        $testCases = [
            FixedTermTerminationReason::CONTRACT_EXPIRATION,
            FixedTermTerminationReason::WORK_COMPLETION,
            FixedTermTerminationReason::EARLY_BY_EMPLOYER,
            FixedTermTerminationReason::EARLY_BY_EMPLOYEE,
            FixedTermTerminationReason::MUTUAL_AGREEMENT,
        ];

        foreach ($testCases as $reason) {
            $declaration = FixedTermTerminationDeclaration::make()
                ->setTerminationReason($reason)
                ->setTerminationReasonComments('Test comment');

            $this->assertSame($reason->value, $declaration->getTerminationReason());
            $this->assertSame('Test comment', $declaration->getTerminationReasonComments());
        }

        // Test with integer value
        $declaration = FixedTermTerminationDeclaration::make()
            ->setTerminationReason(3);
        $this->assertSame(3, $declaration->getTerminationReason());
    }

    public function test_model_dates(): void
    {
        $declaration = FixedTermTerminationDeclaration::make()
            ->setHiringDate('01/06/2024')
            ->setContractEndDate('31/05/2025')
            ->setTerminationDate('31/05/2025');

        $this->assertSame('01/06/2024', $declaration->getHiringDate());
        $this->assertSame('31/05/2025', $declaration->getContractEndDate());
        $this->assertSame('31/05/2025', $declaration->getTerminationDate());
    }

    public function test_model_employment_classification(): void
    {
        $declaration = FixedTermTerminationDeclaration::make()
            ->setEmploymentStatus(EmploymentStatus::FULL)
            ->setWorkerType(WorkerType::EMPLOYEE)
            ->setSpecialtyCode('313200');

        $this->assertSame('0', $declaration->getEmploymentStatus());
        $this->assertSame('1', $declaration->getWorkerType());
        $this->assertSame('313200', $declaration->getSpecialtyCode());
    }

    public function test_model_salary(): void
    {
        $declaration = FixedTermTerminationDeclaration::make()
            ->setGrossSalary(1500.00);

        $this->assertSame(1500.0, $declaration->getGrossSalary());
    }

    public function test_model_to_sorted_array(): void
    {
        $declaration = FixedTermTerminationDeclaration::factory()
            ->withSalary(1500.00)
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

        // E7N Specific - Employment
        $this->assertArrayHasKey('f_xaraktirismos', $array);
        $this->assertArrayHasKey('f_sxeshapasxolisis', $array);
        $this->assertArrayHasKey('f_kathestosapasxolisis', $array);
        $this->assertArrayHasKey('f_oros', $array);
        $this->assertArrayHasKey('f_eidikothta', $array);

        // E7N Specific - Dates
        $this->assertArrayHasKey('f_proslipsidate', $array);
        $this->assertArrayHasKey('f_lixisymbashdate', $array);
        $this->assertArrayHasKey('f_apolysisdate', $array);

        // E7N Specific - Salary
        $this->assertArrayHasKey('f_apodoxes', $array);

        // E7N Specific - Termination Reason
        $this->assertArrayHasKey('f_logosperatosis', $array);
        $this->assertArrayHasKey('f_logosperatosiscomments', $array);

        // Files (no f_file in E7N)
        $this->assertArrayHasKey('f_foreign_file', $array);
        $this->assertArrayHasKey('f_young_file', $array);
        $this->assertArrayNotHasKey('f_file', $array);
    }

    public function test_greek_float_casting(): void
    {
        $declaration = FixedTermTerminationDeclaration::factory()
            ->withSalary(1234.56)
            ->make();

        $array = $declaration->toSortedArray();

        // Salary should be formatted as Greek float (thousands separator: dot, decimal: comma)
        $this->assertSame('1.234,56', $array['f_apodoxes']);
    }

    public function test_factory_employment_relationship_states(): void
    {
        $fixedTerm = FixedTermTerminationDeclaration::factory()
            ->fixedTerm()
            ->make();
        $this->assertSame('1', $fixedTerm->getEmploymentRelationship());

        $projectBased = FixedTermTerminationDeclaration::factory()
            ->projectBased()
            ->make();
        $this->assertSame('2', $projectBased->getEmploymentRelationship());
    }

    public function test_factory_compensation_clause_states(): void
    {
        $withClause = FixedTermTerminationDeclaration::factory()
            ->withCompensationClause()
            ->make();
        $this->assertTrue($withClause->hasCompensationClause());

        $withoutClause = FixedTermTerminationDeclaration::factory()
            ->withoutCompensationClause()
            ->make();
        $this->assertFalse($withoutClause->hasCompensationClause());
    }

    public function test_factory_termination_reason_states(): void
    {
        $expired = FixedTermTerminationDeclaration::factory()
            ->expiredContract()
            ->make();
        $this->assertSame(0, $expired->getTerminationReason());

        $completed = FixedTermTerminationDeclaration::factory()
            ->completedWork('Project finished')
            ->make();
        $this->assertSame(3, $completed->getTerminationReason());
        $this->assertSame('Project finished', $completed->getTerminationReasonComments());

        $byEmployer = FixedTermTerminationDeclaration::factory()
            ->terminatedByEmployer('Serious breach')
            ->make();
        $this->assertSame(4, $byEmployer->getTerminationReason());
        $this->assertSame('Serious breach', $byEmployer->getTerminationReasonComments());

        $byEmployee = FixedTermTerminationDeclaration::factory()
            ->terminatedByEmployee('Personal reasons')
            ->make();
        $this->assertSame(5, $byEmployee->getTerminationReason());
        $this->assertSame('Personal reasons', $byEmployee->getTerminationReasonComments());

        $mutual = FixedTermTerminationDeclaration::factory()
            ->mutualAgreement('Both parties agreed')
            ->make();
        $this->assertSame(6, $mutual->getTerminationReason());
        $this->assertSame('Both parties agreed', $mutual->getTerminationReasonComments());
    }

    public function test_factory_employment_classification_states(): void
    {
        $fullTime = FixedTermTerminationDeclaration::factory()
            ->fullTime()
            ->make();
        $this->assertSame('0', $fullTime->getEmploymentStatus());

        $partTime = FixedTermTerminationDeclaration::factory()
            ->partTime()
            ->make();
        $this->assertSame('1', $partTime->getEmploymentStatus());

        $worker = FixedTermTerminationDeclaration::factory()
            ->asWorker()
            ->make();
        $this->assertSame('0', $worker->getWorkerType());

        $employee = FixedTermTerminationDeclaration::factory()
            ->asEmployee()
            ->make();
        $this->assertSame('1', $employee->getWorkerType());
    }

    public function test_factory_contract_period_state(): void
    {
        $declaration = FixedTermTerminationDeclaration::factory()
            ->contractPeriod('01/01/2024', '31/12/2024')
            ->terminationDate('31/12/2024')
            ->make();

        $this->assertSame('01/01/2024', $declaration->getHiringDate());
        $this->assertSame('31/12/2024', $declaration->getContractEndDate());
        $this->assertSame('31/12/2024', $declaration->getTerminationDate());
    }

    public function test_factory_salary_state(): void
    {
        $declaration = FixedTermTerminationDeclaration::factory()
            ->withSalary(2500.00)
            ->make();

        $this->assertSame(2500.0, $declaration->getGrossSalary());
    }
}
