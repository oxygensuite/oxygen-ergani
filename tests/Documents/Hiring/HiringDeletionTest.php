<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace Tests\Documents\Hiring;

use OxygenSuite\OxygenErgani\Http\Documents\Hiring\HiringDeletion;
use OxygenSuite\OxygenErgani\Models\Hiring\DeletionDeclaration;
use Tests\TestCase;

class HiringDeletionTest extends TestCase
{
    public function test_submit(): void
    {
        $declaration = $this->createDeletionDeclaration()
            ->setBorrowType(0)
            ->setBorrowDateFrom('28/11/2025')
            ->setBorrowDateTo('07/12/2025')
            ->setBorrowCompanyAfm('777777777')
            ->setWagePaymentBy(0)
            ->setEmployeeBorrowAgreement(true);

        $document = new HiringDeletion('test-access-token');
        $document->getConfig()->setHandler($this->mockResponse(200, 'hiring-deletion.json'));
        $response = $document->handle($declaration);

        $this->assertIsArray($response);
        $this->assertSame('202', $response[0]->id);
        $this->assertSame('E3D202', $response[0]->protocol);
        $this->assertSame('28/11/2025 10:30', $response[0]->submissionDate->format('d/m/Y H:i'));
    }

    public function test_model_borrowing_fields(): void
    {
        $declaration = DeletionDeclaration::make()
            ->setBorrowType(0)
            ->setBorrowDateFrom('28/11/2025')
            ->setBorrowDateTo('07/12/2025')
            ->setBorrowCompanyAfm('888888888')
            ->setBorrowCompanyName('BORROW COMPANY')
            ->setWagePaymentBy(1)
            ->setEmployeeBorrowAgreement(true);

        $this->assertSame('0', $declaration->getBorrowType());
        $this->assertSame('28/11/2025', $declaration->getBorrowDateFrom());
        $this->assertSame('07/12/2025', $declaration->getBorrowDateTo());
        $this->assertSame('888888888', $declaration->getBorrowCompanyAfm());
        $this->assertSame('BORROW COMPANY', $declaration->getBorrowCompanyName());
        $this->assertSame('1', $declaration->getWagePaymentBy());
        $this->assertSame('1', $declaration->getEmployeeBorrowAgreement());
    }

    public function test_model_wage_fields(): void
    {
        $declaration = DeletionDeclaration::make()
            ->setGrossSalary(2000.00)
            ->setHourlyWage(15.00)
            ->setWagePaymentTime('Monthly')
            ->setCollectiveAgreementApplicable(true)
            ->setCollectiveAgreementComments('Sector CBA');

        $this->assertSame(2000.0, $declaration->getGrossSalary());
        $this->assertSame(15.0, $declaration->getHourlyWage());
        $this->assertSame('Monthly', $declaration->getWagePaymentTime());
        $this->assertSame('1', $declaration->getCollectiveAgreementApplicable());
        $this->assertSame('Sector CBA', $declaration->getCollectiveAgreementComments());
    }

    public function test_model_borrow_type_epa(): void
    {
        $declaration = DeletionDeclaration::make()
            ->setBorrowType(1); // EPA (Temporary Employment Agency)

        $this->assertSame('1', $declaration->getBorrowType());
    }

    private function createDeletionDeclaration(): DeletionDeclaration
    {
        return DeletionDeclaration::factory()->make();
    }
}
