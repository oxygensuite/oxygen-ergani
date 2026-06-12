<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace Tests\Documents\Dismissal;

use OxygenSuite\OxygenErgani\Http\Documents\Dismissal\TrialPeriodTermination;
use OxygenSuite\OxygenErgani\Models\Dismissal\TrialPeriodTerminationDeclaration;
use Tests\TestCase;

class TrialPeriodTerminationTest extends TestCase
{
    public function test_submit(): void
    {
        $declaration = TrialPeriodTerminationDeclaration::factory()
            ->withSalary(1200.00)
            ->withFormFile('SGVsbG8sIHdvcmxkIQ==')
            ->make([
                'f_afm' => '999999999',
                'f_comments' => 'Trial period termination',
            ]);

        $document = new TrialPeriodTermination('test-access-token');
        $document->getConfig()->setHandler($this->mockResponse(200, 'trial-period-termination.json'));
        $response = $document->handle($declaration);

        $this->assertIsArray($response);
        $this->assertSame('604', $response[0]->id);
        $this->assertSame('E6LT604', $response[0]->protocol);
        $this->assertSame('17/01/2026 10:50', $response[0]->submissionDate->format('d/m/Y H:i'));
    }

    public function test_submit_with_multiple_declarations(): void
    {
        $declaration1 = TrialPeriodTerminationDeclaration::factory()->make();
        $declaration2 = TrialPeriodTerminationDeclaration::factory()->make([
            'f_afm' => '888888888',
            'f_eponymo' => 'ALLOS',
            'f_onoma' => 'KAPOIOS',
        ]);

        $document = new TrialPeriodTermination('test-access-token');
        $document->getConfig()->setHandler($this->mockResponse(200, 'trial-period-termination.json'));
        $response = $document->handle([$declaration1, $declaration2]);

        $this->assertIsArray($response);
        $this->assertSame('604', $response[0]->id);
    }

    public function test_model_dates(): void
    {
        $declaration = TrialPeriodTerminationDeclaration::make()
            ->setHiringDate('01/02/2025')
            ->setTerminationDate('17/01/2026');

        $this->assertSame('01/02/2025', $declaration->getHiringDate());
        $this->assertSame('17/01/2026', $declaration->getTerminationDate());
    }

    public function test_model_salary(): void
    {
        $declaration = TrialPeriodTerminationDeclaration::make()
            ->setGrossSalary(1500.00);

        $this->assertSame(1500.0, $declaration->getGrossSalary());
    }

    public function test_model_form_file(): void
    {
        $declaration = TrialPeriodTerminationDeclaration::make()
            ->setFormFile('SGVsbG8sIHdvcmxkIQ==');

        $this->assertSame('SGVsbG8sIHdvcmxkIQ==', $declaration->getFormFile());
    }

    public function test_model_to_sorted_array(): void
    {
        $declaration = TrialPeriodTerminationDeclaration::factory()
            ->withSalary(1200.00)
            ->make();

        $array = $declaration->toSortedArray();

        // Branch/Location
        $this->assertArrayHasKey('f_aa_pararthmatos', $array);
        $this->assertArrayHasKey('f_ypiresia_sepe', $array);

        // Personal
        $this->assertArrayHasKey('f_eponymo', $array);
        $this->assertArrayHasKey('f_onoma', $array);
        $this->assertArrayHasKey('f_afm', $array);

        // Employment Dates
        $this->assertArrayHasKey('f_proslipsidate', $array);
        $this->assertArrayHasKey('f_apolysisdate', $array);

        // Salary
        $this->assertArrayHasKey('f_apodoxes', $array);

        // Files
        $this->assertArrayHasKey('f_file', $array);
        $this->assertArrayHasKey('f_comments', $array);

        // Should NOT have compensation/severance
        $this->assertArrayNotHasKey('f_posoapozimiosis', $array);

        // Should NOT have employment classification
        $this->assertArrayNotHasKey('f_kathestosapasxolisis', $array);
        $this->assertArrayNotHasKey('f_xaraktirismos', $array);
        $this->assertArrayNotHasKey('f_eidikothta', $array);
    }

    public function test_greek_float_casting(): void
    {
        $declaration = TrialPeriodTerminationDeclaration::factory()
            ->withSalary(1234.56)
            ->make();

        $array = $declaration->toSortedArray();

        $this->assertSame('1.234,56', $array['f_apodoxes']);
    }

    public function test_factory_full_trial_period_state(): void
    {
        $declaration = TrialPeriodTerminationDeclaration::factory()
            ->fullTrialPeriod()
            ->make();

        // Should have hiring date 12 months ago and termination today
        $this->assertNotEmpty($declaration->getHiringDate());
        $this->assertNotEmpty($declaration->getTerminationDate());
    }

    public function test_factory_date_states(): void
    {
        $declaration = TrialPeriodTerminationDeclaration::factory()
            ->hireDate('15/01/2025')
            ->terminationDate('15/12/2025')
            ->make();

        $this->assertSame('15/01/2025', $declaration->get('f_proslipsidate'));
        $this->assertSame('15/12/2025', $declaration->get('f_apolysisdate'));
    }
}
