<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace Tests\Documents\Dismissal;

use OxygenSuite\OxygenErgani\Http\Documents\Dismissal\Transfer;
use OxygenSuite\OxygenErgani\Models\Dismissal\TransferDeclaration;
use Tests\TestCase;

class TransferTest extends TestCase
{
    public function test_submit(): void
    {
        $declaration = TransferDeclaration::factory()
            ->transferDate('17/01/2026')
            ->toCompany('123456789', 'ACME CORP')
            ->make([
                'f_afm' => '999999999',
                'f_comments' => 'Transfer to new company',
            ]);

        $document = new Transfer('test-access-token');
        $document->getConfig()->setHandler($this->mockResponse(200, 'transfer.json'));
        $response = $document->handle($declaration);

        $this->assertIsArray($response);
        $this->assertSame('200', $response[0]->id);
        $this->assertSame('E6M200', $response[0]->protocol);
        $this->assertSame('17/01/2026 10:00', $response[0]->submissionDate->format('d/m/Y H:i'));
    }

    public function test_submit_with_multiple_declarations(): void
    {
        $declaration1 = TransferDeclaration::factory()->make();
        $declaration2 = TransferDeclaration::factory()->make([
            'f_afm' => '888888888',
            'f_eponymo' => 'ALLOS',
            'f_onoma' => 'KAPOIOS',
        ]);

        $document = new Transfer('test-access-token');
        $document->getConfig()->setHandler($this->mockResponse(200, 'transfer.json'));
        $response = $document->handle([$declaration1, $declaration2]);

        $this->assertIsArray($response);
        $this->assertSame('200', $response[0]->id);
    }

    public function test_model_transfer_date(): void
    {
        $declaration = TransferDeclaration::make()
            ->setTransferDate('17/01/2026');

        $this->assertSame('17/01/2026', $declaration->getTransferDate());
    }

    public function test_model_transfer_company(): void
    {
        $declaration = TransferDeclaration::make()
            ->setTransferCompanyAfm('123456789')
            ->setTransferCompanyName('ACME CORPORATION');

        $this->assertSame('123456789', $declaration->getTransferCompanyAfm());
        $this->assertSame('ACME CORPORATION', $declaration->getTransferCompanyName());
    }

    public function test_model_to_sorted_array(): void
    {
        $declaration = TransferDeclaration::factory()->make();

        $array = $declaration->toSortedArray();

        // Branch/Location
        $this->assertArrayHasKey('f_aa_pararthmatos', $array);
        $this->assertArrayHasKey('f_ypiresia_sepe', $array);

        // Personal
        $this->assertArrayHasKey('f_eponymo', $array);
        $this->assertArrayHasKey('f_onoma', $array);
        $this->assertArrayHasKey('f_afm', $array);

        // Transfer Details
        $this->assertArrayHasKey('f_date_metabibashs', $array);
        $this->assertArrayHasKey('f_transfer_company_afm', $array);
        $this->assertArrayHasKey('f_transfer_company_eponimia', $array);

        // Should NOT have salary/compensation/loan fields
        $this->assertArrayNotHasKey('f_apodoxes', $array);
        $this->assertArrayNotHasKey('f_posoapozimiosis', $array);
        $this->assertArrayNotHasKey('f_file', $array);
        $this->assertArrayNotHasKey('f_borrow_type', $array);
    }

    public function test_factory_transfer_date_state(): void
    {
        $declaration = TransferDeclaration::factory()
            ->transferDate('01/06/2026')
            ->make();

        $this->assertSame('01/06/2026', $declaration->get('f_date_metabibashs'));
    }

    public function test_factory_company_state(): void
    {
        $declaration = TransferDeclaration::factory()
            ->toCompany('987654321', 'TEST COMPANY')
            ->make();

        $this->assertSame('987654321', $declaration->get('f_transfer_company_afm'));
        $this->assertSame('TEST COMPANY', $declaration->get('f_transfer_company_eponimia'));
    }
}
