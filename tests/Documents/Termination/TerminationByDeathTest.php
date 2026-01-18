<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace Tests\Documents\Termination;

use OxygenSuite\OxygenErgani\Http\Documents\Termination\TerminationByDeath;
use OxygenSuite\OxygenErgani\Models\Termination\DeathTerminationDeclaration;
use Tests\TestCase;

class TerminationByDeathTest extends TestCase
{
    public function test_submit(): void
    {
        $declaration = DeathTerminationDeclaration::factory()
            ->withSalary(1500.00)
            ->withFormFile('SGVsbG8sIHdvcmxkIQ==')
            ->make([
                'f_afm' => '999999999',
                'f_comments' => 'Termination due to employee death',
            ]);

        $document = new TerminationByDeath('test-access-token');
        $document->getConfig()->setHandler($this->mockResponse(200, 'termination-by-death.json'));
        $response = $document->handle($declaration);

        $this->assertIsArray($response);
        $this->assertSame('503', $response[0]->id);
        $this->assertSame('E5D503', $response[0]->protocol);
        $this->assertSame('15/01/2026 10:45', $response[0]->submissionDate->format('d/m/Y H:i'));
    }

    public function test_submit_with_multiple_declarations(): void
    {
        $declaration1 = DeathTerminationDeclaration::factory()->make();
        $declaration2 = DeathTerminationDeclaration::factory()->make([
            'f_afm' => '888888888',
            'f_eponymo' => 'ALLOS',
            'f_onoma' => 'KAPOIOS',
        ]);

        $document = new TerminationByDeath('test-access-token');
        $document->getConfig()->setHandler($this->mockResponse(200, 'termination-by-death.json'));
        $response = $document->handle([$declaration1, $declaration2]);

        $this->assertIsArray($response);
        $this->assertSame('503', $response[0]->id);
    }

    public function test_model_salary_and_file_fields(): void
    {
        $declaration = DeathTerminationDeclaration::make()
            ->setGrossSalary(1500.00)
            ->setFormFile('SGVsbG8sIHdvcmxkIQ==');

        $this->assertSame(1500.0, $declaration->getGrossSalary());
        $this->assertSame('SGVsbG8sIHdvcmxkIQ==', $declaration->getFormFile());
    }

    public function test_salary_formatted_in_sorted_array(): void
    {
        $declaration = DeathTerminationDeclaration::make()
            ->setGrossSalary(1500.00);

        // Getter returns float
        $this->assertSame(1500.0, $declaration->getGrossSalary());

        // toSortedArray() formats in Greek format
        $array = $declaration->toSortedArray();
        $this->assertSame('1.500,00', $array['f_apodoxes']);
    }

    public function test_model_to_sorted_array(): void
    {
        $declaration = DeathTerminationDeclaration::factory()
            ->withSalary(1500.00)
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

        // Files
        $this->assertArrayHasKey('f_file', $array);
    }
}
