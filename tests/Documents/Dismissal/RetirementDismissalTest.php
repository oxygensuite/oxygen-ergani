<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace Tests\Documents\Dismissal;

use OxygenSuite\OxygenErgani\Enums\EmploymentStatus;
use OxygenSuite\OxygenErgani\Enums\WorkerType;
use OxygenSuite\OxygenErgani\Http\Documents\Dismissal\RetirementDismissal;
use OxygenSuite\OxygenErgani\Models\Dismissal\RetirementDismissalDeclaration;
use Tests\TestCase;

class RetirementDismissalTest extends TestCase
{
    public function test_submit(): void
    {
        $declaration = RetirementDismissalDeclaration::factory()
            ->withSalary(2500.00)
            ->withSeverance(15000.00)
            ->withFormFile('SGVsbG8sIHdvcmxkIQ==')
            ->make([
                'f_afm' => '999999999',
                'f_comments' => 'Retirement dismissal',
            ]);

        $document = new RetirementDismissal('test-access-token');
        $document->getConfig()->setHandler($this->mockResponse(200, 'retirement-dismissal.json'));
        $response = $document->handle($declaration);

        $this->assertIsArray($response);
        $this->assertSame('602', $response[0]->id);
        $this->assertSame('E6SXP602', $response[0]->protocol);
        $this->assertSame('17/01/2026 10:40', $response[0]->submissionDate->format('d/m/Y H:i'));
    }

    public function test_submit_with_multiple_declarations(): void
    {
        $declaration1 = RetirementDismissalDeclaration::factory()->make();
        $declaration2 = RetirementDismissalDeclaration::factory()->make([
            'f_afm' => '888888888',
            'f_eponymo' => 'ALLOS',
            'f_onoma' => 'KAPOIOS',
        ]);

        $document = new RetirementDismissal('test-access-token');
        $document->getConfig()->setHandler($this->mockResponse(200, 'retirement-dismissal.json'));
        $response = $document->handle([$declaration1, $declaration2]);

        $this->assertIsArray($response);
        $this->assertSame('602', $response[0]->id);
    }

    public function test_model_employment_classification(): void
    {
        $declaration = RetirementDismissalDeclaration::make()
            ->setEmploymentStatus(EmploymentStatus::FULL)
            ->setWorkerType(WorkerType::EMPLOYEE)
            ->setSpecialtyCode('313200');

        $this->assertSame('0', $declaration->getEmploymentStatus());
        $this->assertSame('1', $declaration->getWorkerType());
        $this->assertSame('313200', $declaration->getSpecialtyCode());
    }

    public function test_model_termination_notification(): void
    {
        $declaration = RetirementDismissalDeclaration::make()
            ->setTerminationNotificationDate('17/01/2026');

        $this->assertSame('17/01/2026', $declaration->getTerminationNotificationDate());
    }

    public function test_model_salary_and_compensation(): void
    {
        $declaration = RetirementDismissalDeclaration::make()
            ->setGrossSalary(3000.00)
            ->setCompensationAmount(20000.00);

        $this->assertSame(3000.0, $declaration->getGrossSalary());
        $this->assertSame(20000.0, $declaration->getCompensationAmount());
    }

    public function test_model_dates(): void
    {
        $declaration = RetirementDismissalDeclaration::make()
            ->setHiringDate('01/01/1995')
            ->setDismissalDate('17/01/2026');

        $this->assertSame('01/01/1995', $declaration->getHiringDate());
        $this->assertSame('17/01/2026', $declaration->getDismissalDate());
    }

    public function test_model_to_sorted_array(): void
    {
        $declaration = RetirementDismissalDeclaration::factory()
            ->withSalary(3000.00)
            ->withSeverance(20000.00)
            ->make();

        $array = $declaration->toSortedArray();

        // Branch/Location
        $this->assertArrayHasKey('f_aa_pararthmatos', $array);
        $this->assertArrayHasKey('f_ypiresia_sepe', $array);

        // Employment Classification
        $this->assertArrayHasKey('f_kathestosapasxolisis', $array);
        $this->assertArrayHasKey('f_xaraktirismos', $array);
        $this->assertArrayHasKey('f_eidikothta', $array);

        // Employment Dates
        $this->assertArrayHasKey('f_proslipsidate', $array);
        $this->assertArrayHasKey('f_apolysisdate', $array);

        // Salary and Compensation
        $this->assertArrayHasKey('f_apodoxes', $array);
        $this->assertArrayHasKey('f_koinopoihshdate', $array);
        $this->assertArrayHasKey('f_posoapozimiosis', $array);

        // Files
        $this->assertArrayHasKey('f_file', $array);

        // Should NOT have collective dismissal fields
        $this->assertArrayNotHasKey('f_omadiki', $array);
        $this->assertArrayNotHasKey('f_omadikiarithmos', $array);
        $this->assertArrayNotHasKey('f_omadikidate', $array);
    }

    public function test_greek_float_casting(): void
    {
        $declaration = RetirementDismissalDeclaration::factory()
            ->withSalary(3500.50)
            ->withSeverance(25000.75)
            ->make();

        $array = $declaration->toSortedArray();

        $this->assertSame('3.500,50', $array['f_apodoxes']);
        $this->assertSame('25.000,75', $array['f_posoapozimiosis']);
    }

    public function test_factory_states(): void
    {
        $declaration = RetirementDismissalDeclaration::factory()
            ->fullTime()
            ->asEmployee()
            ->withSeverance(30000.00)
            ->make();

        $this->assertSame('0', $declaration->get('f_kathestosapasxolisis'));
        $this->assertSame('1', $declaration->get('f_xaraktirismos'));
        $this->assertSame(30000.0, $declaration->getCompensationAmount());
    }
}
