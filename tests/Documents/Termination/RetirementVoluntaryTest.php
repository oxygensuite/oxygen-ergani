<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace Tests\Documents\Termination;

use OxygenSuite\OxygenErgani\Http\Documents\Termination\RetirementVoluntary;
use OxygenSuite\OxygenErgani\Models\Termination\VoluntaryRetirementDeclaration;
use Tests\TestCase;

class RetirementVoluntaryTest extends TestCase
{
    public function test_submit(): void
    {
        $declaration = VoluntaryRetirementDeclaration::factory()
            ->withSalary(2000.00)
            ->withCompensation(10000.00)
            ->withFormFile('SGVsbG8sIHdvcmxkIQ==')
            ->make([
                'f_afm' => '999999999',
                'f_birthdate' => '01/01/1960',
                'f_proslipsidate' => '01/06/2000',
                'f_comments' => 'Voluntary retirement',
            ]);

        $document = new RetirementVoluntary('test-access-token');
        $document->getConfig()->setHandler($this->mockResponse(200, 'termination-retirement-voluntary.json'));
        $response = $document->handle($declaration);

        $this->assertIsArray($response);
        $this->assertSame('505', $response[0]->id);
        $this->assertSame('E5S505', $response[0]->protocol);
        $this->assertSame('15/01/2026 10:55', $response[0]->submissionDate->format('d/m/Y H:i'));
    }

    public function test_submit_with_multiple_declarations(): void
    {
        $declaration1 = VoluntaryRetirementDeclaration::factory()->make();
        $declaration2 = VoluntaryRetirementDeclaration::factory()->make([
            'f_afm' => '888888888',
            'f_eponymo' => 'ALLOS',
            'f_onoma' => 'KAPOIOS',
        ]);

        $document = new RetirementVoluntary('test-access-token');
        $document->getConfig()->setHandler($this->mockResponse(200, 'termination-retirement-voluntary.json'));
        $response = $document->handle([$declaration1, $declaration2]);

        $this->assertIsArray($response);
        $this->assertSame('505', $response[0]->id);
    }

    public function test_model_compensation_field(): void
    {
        $declaration = VoluntaryRetirementDeclaration::make()
            ->setGrossSalary(1500.00)
            ->setCompensationAmount(10000.00);

        $this->assertSame(1500.0, $declaration->getGrossSalary());
        $this->assertSame(10000.0, $declaration->getCompensationAmount());
    }

    public function test_model_to_sorted_array(): void
    {
        $declaration = VoluntaryRetirementDeclaration::factory()
            ->withSalary(2000.00)
            ->withCompensation(10000.00)
            ->withFormFile('SGVsbG8sIHdvcmxkIQ==')
            ->make();

        $array = $declaration->toSortedArray();

        // Branch/Location
        $this->assertArrayHasKey('f_aa_pararthmatos', $array);
        $this->assertArrayHasKey('f_ypiresia_sepe', $array);

        // Personal
        $this->assertArrayHasKey('f_eponymo', $array);
        $this->assertArrayHasKey('f_onoma', $array);

        // Employment
        $this->assertArrayHasKey('f_proslipsidate', $array);
        $this->assertArrayHasKey('f_apoxwrisidate', $array);
        $this->assertArrayHasKey('f_apodoxes', $array);

        // Compensation
        $this->assertArrayHasKey('f_posoapozimiosis', $array);

        // Files
        $this->assertArrayHasKey('f_file', $array);
    }
}
