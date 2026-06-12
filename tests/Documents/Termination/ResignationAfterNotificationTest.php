<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace Tests\Documents\Termination;

use OxygenSuite\OxygenErgani\Http\Documents\Termination\ResignationAfterNotification;
use OxygenSuite\OxygenErgani\Models\Termination\ResignationAfterNotificationDeclaration;
use Tests\TestCase;

class ResignationAfterNotificationTest extends TestCase
{
    public function test_submit(): void
    {
        $declaration = ResignationAfterNotificationDeclaration::factory()
            ->withSalary(1500.00)
            ->withNotificationReference('E5O501', '15/01/2026')
            ->make([
                'f_afm' => '999999999',
                'f_apoxwrisidate' => '20/01/2026',
                'f_comments' => 'Resignation confirmed after notification',
            ]);

        $document = new ResignationAfterNotification('test-access-token');
        $document->getConfig()->setHandler($this->mockResponse(200, 'termination-after-notification.json'));
        $response = $document->handle($declaration);

        $this->assertIsArray($response);
        $this->assertSame('502', $response[0]->id);
        $this->assertSame('E5AO502', $response[0]->protocol);
        $this->assertSame('15/01/2026 10:40', $response[0]->submissionDate->format('d/m/Y H:i'));
    }

    public function test_submit_with_multiple_declarations(): void
    {
        $declaration1 = ResignationAfterNotificationDeclaration::factory()->make();
        $declaration2 = ResignationAfterNotificationDeclaration::factory()->make([
            'f_afm' => '888888888',
            'f_eponymo' => 'ALLOS',
            'f_onoma' => 'KAPOIOS',
        ]);

        $document = new ResignationAfterNotification('test-access-token');
        $document->getConfig()->setHandler($this->mockResponse(200, 'termination-after-notification.json'));
        $response = $document->handle([$declaration1, $declaration2]);

        $this->assertIsArray($response);
        $this->assertSame('502', $response[0]->id);
    }

    public function test_model_notification_reference_fields(): void
    {
        $declaration = ResignationAfterNotificationDeclaration::make()
            ->setNotificationProtocol('E5O501')
            ->setNotificationDate('15/01/2026');

        $this->assertSame('E5O501', $declaration->getNotificationProtocol());
        $this->assertSame('15/01/2026', $declaration->getNotificationDate());
    }

    public function test_model_salary_field(): void
    {
        $declaration = ResignationAfterNotificationDeclaration::make()
            ->setGrossSalary(1500.00);

        $this->assertSame(1500.0, $declaration->getGrossSalary());
    }

    public function test_model_to_sorted_array(): void
    {
        $declaration = ResignationAfterNotificationDeclaration::factory()
            ->withSalary(1500.00)
            ->withNotificationReference('E5O501', '15/01/2026')
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

        // Notification reference
        $this->assertArrayHasKey('f_oxlhsh_protocol', $array);
        $this->assertArrayHasKey('f_oxlhsh_date_ypovolis', $array);
    }
}
