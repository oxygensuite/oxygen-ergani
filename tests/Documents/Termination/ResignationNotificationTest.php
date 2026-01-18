<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace Tests\Documents\Termination;

use OxygenSuite\OxygenErgani\Http\Documents\Termination\ResignationNotification;
use OxygenSuite\OxygenErgani\Models\Termination\NotificationDeclaration;
use Tests\TestCase;

class ResignationNotificationTest extends TestCase
{
    public function test_submit(): void
    {
        $declaration = NotificationDeclaration::factory()
            ->withNotificationMethods('Phone call on 10/01/2026, Email on 11/01/2026')
            ->make([
                'f_afm' => '999999999',
                'f_comments' => 'Employee absent without notice',
            ]);

        $document = new ResignationNotification('test-access-token');
        $document->getConfig()->setHandler($this->mockResponse(200, 'termination-notification.json'));
        $response = $document->handle($declaration);

        $this->assertIsArray($response);
        $this->assertSame('501', $response[0]->id);
        $this->assertSame('E5O501', $response[0]->protocol);
        $this->assertSame('15/01/2026 10:35', $response[0]->submissionDate->format('d/m/Y H:i'));
    }

    public function test_submit_with_multiple_declarations(): void
    {
        $declaration1 = NotificationDeclaration::factory()->make();
        $declaration2 = NotificationDeclaration::factory()->make([
            'f_afm' => '888888888',
            'f_eponymo' => 'ALLOS',
            'f_onoma' => 'KAPOIOS',
        ]);

        $document = new ResignationNotification('test-access-token');
        $document->getConfig()->setHandler($this->mockResponse(200, 'termination-notification.json'));
        $response = $document->handle([$declaration1, $declaration2]);

        $this->assertIsArray($response);
        $this->assertSame('501', $response[0]->id);
    }

    public function test_model_notification_methods_field(): void
    {
        $declaration = NotificationDeclaration::make()
            ->setNotificationMethods('Phone call, Email, Registered letter');

        $this->assertSame('Phone call, Email, Registered letter', $declaration->getNotificationMethods());
    }

    public function test_model_does_not_have_salary_field(): void
    {
        $declaration = NotificationDeclaration::factory()->make();
        $array = $declaration->toSortedArray();

        // E5O does not have f_apodoxes (salary) field
        $this->assertArrayNotHasKey('f_apodoxes', $array);
        // E5O does not have f_file (form file) field
        $this->assertArrayNotHasKey('f_file', $array);
    }

    public function test_model_to_sorted_array(): void
    {
        $declaration = NotificationDeclaration::factory()->make();

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

        // Notification specific
        $this->assertArrayHasKey('f_tropoi_oxlhshs', $array);
    }
}
