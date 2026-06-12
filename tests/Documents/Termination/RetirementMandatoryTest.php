<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace Tests\Documents\Termination;

use OxygenSuite\OxygenErgani\Http\Documents\Termination\RetirementMandatory;
use OxygenSuite\OxygenErgani\Models\Termination\MandatoryRetirementDeclaration;
use Tests\TestCase;

class RetirementMandatoryTest extends TestCase
{
    public function test_submit(): void
    {
        $declaration = MandatoryRetirementDeclaration::factory()
            ->withSalary(2500.00)
            ->withCompensation(15000.00)
            ->withFormFile('SGVsbG8sIHdvcmxkIQ==')
            ->make([
                'f_afm' => '999999999',
                'f_birthdate' => '01/01/1955',
                'f_proslipsidate' => '01/06/1990',
                'f_comments' => 'Mandatory retirement after 15 years',
            ]);

        $document = new RetirementMandatory('test-access-token');
        $document->getConfig()->setHandler($this->mockResponse(200, 'termination-retirement-mandatory.json'));
        $response = $document->handle($declaration);

        $this->assertIsArray($response);
        $this->assertSame('506', $response[0]->id);
        $this->assertSame('E5DS506', $response[0]->protocol);
        $this->assertSame('15/01/2026 11:00', $response[0]->submissionDate->format('d/m/Y H:i'));
    }

    public function test_submit_with_multiple_declarations(): void
    {
        $declaration1 = MandatoryRetirementDeclaration::factory()->make();
        $declaration2 = MandatoryRetirementDeclaration::factory()->make([
            'f_afm' => '888888888',
            'f_eponymo' => 'ALLOS',
            'f_onoma' => 'KAPOIOS',
        ]);

        $document = new RetirementMandatory('test-access-token');
        $document->getConfig()->setHandler($this->mockResponse(200, 'termination-retirement-mandatory.json'));
        $response = $document->handle([$declaration1, $declaration2]);

        $this->assertIsArray($response);
        $this->assertSame('506', $response[0]->id);
    }

    public function test_model_compensation_field(): void
    {
        $declaration = MandatoryRetirementDeclaration::make()
            ->setGrossSalary(2500.00)
            ->setCompensationAmount(15000.00);

        $this->assertSame(2500.0, $declaration->getGrossSalary());
        $this->assertSame(15000.0, $declaration->getCompensationAmount());
    }

    public function test_model_to_sorted_array(): void
    {
        $declaration = MandatoryRetirementDeclaration::factory()
            ->withSalary(2500.00)
            ->withCompensation(15000.00)
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
