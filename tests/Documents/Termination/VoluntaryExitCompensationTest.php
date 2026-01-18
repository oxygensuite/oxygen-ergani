<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace Tests\Documents\Termination;

use OxygenSuite\OxygenErgani\Http\Documents\Termination\VoluntaryExitCompensation;
use OxygenSuite\OxygenErgani\Models\Termination\CompensatedExitDeclaration;
use Tests\TestCase;

class VoluntaryExitCompensationTest extends TestCase
{
    public function test_submit(): void
    {
        $declaration = CompensatedExitDeclaration::factory()
            ->withSalary(1500.00)
            ->withCompensation(5000.00)
            ->withFormFile('SGVsbG8sIHdvcmxkIQ==')
            ->make([
                'f_afm' => '999999999',
                'f_comments' => 'Voluntary exit with compensation',
            ]);

        $document = new VoluntaryExitCompensation('test-access-token');
        $document->getConfig()->setHandler($this->mockResponse(200, 'termination-compensated-exit.json'));
        $response = $document->handle($declaration);

        $this->assertIsArray($response);
        $this->assertSame('504', $response[0]->id);
        $this->assertSame('E5E504', $response[0]->protocol);
        $this->assertSame('15/01/2026 10:50', $response[0]->submissionDate->format('d/m/Y H:i'));
    }

    public function test_submit_with_multiple_declarations(): void
    {
        $declaration1 = CompensatedExitDeclaration::factory()->make();
        $declaration2 = CompensatedExitDeclaration::factory()->make([
            'f_afm' => '888888888',
            'f_eponymo' => 'ALLOS',
            'f_onoma' => 'KAPOIOS',
        ]);

        $document = new VoluntaryExitCompensation('test-access-token');
        $document->getConfig()->setHandler($this->mockResponse(200, 'termination-compensated-exit.json'));
        $response = $document->handle([$declaration1, $declaration2]);

        $this->assertIsArray($response);
        $this->assertSame('504', $response[0]->id);
    }

    public function test_model_compensation_field(): void
    {
        $declaration = CompensatedExitDeclaration::make()
            ->setGrossSalary(1500.00)
            ->setCompensationAmount(5000.00);

        $this->assertSame(1500.0, $declaration->getGrossSalary());
        $this->assertSame(5000.0, $declaration->getCompensationAmount());
    }

    public function test_model_to_sorted_array(): void
    {
        $declaration = CompensatedExitDeclaration::factory()
            ->withSalary(1500.00)
            ->withCompensation(5000.00)
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
