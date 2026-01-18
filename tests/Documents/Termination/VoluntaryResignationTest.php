<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace Tests\Documents\Termination;

use OxygenSuite\OxygenErgani\Http\Documents\Termination\VoluntaryResignation;
use OxygenSuite\OxygenErgani\Models\Termination\VoluntaryResignationDeclaration;
use Tests\TestCase;

class VoluntaryResignationTest extends TestCase
{
    public function test_submit(): void
    {
        $declaration = VoluntaryResignationDeclaration::factory()
            ->withSalary(1500.00)
            ->withFormFile('SGVsbG8sIHdvcmxkIQ==')
            ->make([
                'f_afm' => '999999999',
                'f_comments' => 'Voluntary resignation',
            ]);

        $document = new VoluntaryResignation('test-access-token');
        $document->getConfig()->setHandler($this->mockResponse(200, 'termination-voluntary-resignation.json'));
        $response = $document->handle($declaration);

        $this->assertIsArray($response);
        $this->assertSame('500', $response[0]->id);
        $this->assertSame('E5N500', $response[0]->protocol);
        $this->assertSame('15/01/2026 10:30', $response[0]->submissionDate->format('d/m/Y H:i'));
    }

    public function test_submit_with_multiple_declarations(): void
    {
        $declaration1 = VoluntaryResignationDeclaration::factory()->make();
        $declaration2 = VoluntaryResignationDeclaration::factory()->make([
            'f_afm' => '888888888',
            'f_eponymo' => 'ALLOS',
            'f_onoma' => 'KAPOIOS',
        ]);

        $document = new VoluntaryResignation('test-access-token');
        $document->getConfig()->setHandler($this->mockResponse(200, 'termination-voluntary-resignation.json'));
        $response = $document->handle([$declaration1, $declaration2]);

        $this->assertIsArray($response);
        $this->assertSame('500', $response[0]->id);
    }

    public function test_model_basic_fields(): void
    {
        $declaration = VoluntaryResignationDeclaration::make()
            ->setBranchCode(0)
            ->setRelatedProtocol('REL123')
            ->setRelatedDate('27/11/2025')
            ->setLaborInspectionServiceCode('11080')
            ->setDypaServiceCode('101213')
            ->setBranchActivityCode('4673')
            ->setMunicipalityCode('91790101');

        $this->assertSame('0', $declaration->getBranchCode());
        $this->assertSame('REL123', $declaration->getRelatedProtocol());
        $this->assertSame('27/11/2025', $declaration->getRelatedDate());
        $this->assertSame('11080', $declaration->getLaborInspectionServiceCode());
        $this->assertSame('101213', $declaration->getDypaServiceCode());
        $this->assertSame('4673', $declaration->getBranchActivityCode());
        $this->assertSame('91790101', $declaration->getMunicipalityCode());
    }

    public function test_model_personal_fields(): void
    {
        $declaration = VoluntaryResignationDeclaration::make()
            ->setLastName('KAPOIOS')
            ->setFirstName('ALLOS')
            ->setFatherName('PATERAS')
            ->setMotherName('MITERA')
            ->setBirthDate('01/01/1980')
            ->setSex(1);

        $this->assertSame('KAPOIOS', $declaration->getLastName());
        $this->assertSame('ALLOS', $declaration->getFirstName());
        $this->assertSame('PATERAS', $declaration->getFatherName());
        $this->assertSame('MITERA', $declaration->getMotherName());
        $this->assertSame('01/01/1980', $declaration->getBirthDate());
        $this->assertSame('1', $declaration->getSex());
    }

    public function test_model_employment_fields(): void
    {
        $declaration = VoluntaryResignationDeclaration::make()
            ->setWorkerType(1)
            ->setEmploymentType(0)
            ->setEmploymentStatus(0)
            ->setSpecialtyCode('313200')
            ->setHiringDate('01/06/2020')
            ->setDepartureDate('15/01/2026')
            ->setGrossSalary(1500.00);

        $this->assertSame('1', $declaration->getWorkerType());
        $this->assertSame('0', $declaration->getEmploymentType());
        $this->assertSame('0', $declaration->getEmploymentStatus());
        $this->assertSame('313200', $declaration->getSpecialtyCode());
        $this->assertSame('01/06/2020', $declaration->getHiringDate());
        $this->assertSame('15/01/2026', $declaration->getDepartureDate());
        $this->assertSame(1500.0, $declaration->getGrossSalary());
    }

    public function test_model_file_fields(): void
    {
        $declaration = VoluntaryResignationDeclaration::make()
            ->setFormFile('SGVsbG8sIHdvcmxkIQ==')
            ->setComments('Test comments')
            ->setForeignWorkerFile('')
            ->setMinorWorkerFile('');

        $this->assertSame('SGVsbG8sIHdvcmxkIQ==', $declaration->getFormFile());
        $this->assertSame('Test comments', $declaration->getComments());
        $this->assertSame('', $declaration->getForeignWorkerFile());
        $this->assertSame('', $declaration->getMinorWorkerFile());
    }

    public function test_model_to_sorted_array(): void
    {
        $declaration = VoluntaryResignationDeclaration::factory()
            ->withSalary(1500.00)
            ->withFormFile('SGVsbG8sIHdvcmxkIQ==')
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

        // Employment
        $this->assertArrayHasKey('f_proslipsidate', $array);
        $this->assertArrayHasKey('f_apoxwrisidate', $array);
        $this->assertArrayHasKey('f_apodoxes', $array);

        // Files
        $this->assertArrayHasKey('f_file', $array);
        $this->assertArrayHasKey('f_comments', $array);
    }
}
