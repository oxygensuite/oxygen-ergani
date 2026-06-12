<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace Tests\Documents\Hiring;

use OxygenSuite\OxygenErgani\Http\Documents\Hiring\HiringModification;
use OxygenSuite\OxygenErgani\Models\Hiring\ModificationDeclaration;
use OxygenSuite\OxygenErgani\Models\Hiring\SupplementaryInsuranceSelection;
use Tests\TestCase;

class HiringModificationTest extends TestCase
{
    public function test_submit(): void
    {
        $declaration = $this->createModificationDeclaration()
            ->setTransferDate('28/11/2025')
            ->setTransferCompanyAfm('888888888')
            ->setTransferCompanyName('TRANSFER COMPANY');

        $document = new HiringModification('test-access-token');
        $document->getConfig()->setHandler($this->mockResponse(200, 'hiring-modification.json'));
        $response = $document->handle($declaration);

        $this->assertIsArray($response);
        $this->assertSame('201', $response[0]->id);
        $this->assertSame('E3M201', $response[0]->protocol);
        $this->assertSame('28/11/2025 10:00', $response[0]->submissionDate->format('d/m/Y H:i'));
    }

    public function test_model_transfer_fields(): void
    {
        $declaration = ModificationDeclaration::make()
            ->setTransferDate('28/11/2025')
            ->setTransferCompanyAfm('777777777')
            ->setTransferCompanyName('TRANSFER COMPANY');

        $this->assertSame('28/11/2025', $declaration->getTransferDate());
        $this->assertSame('777777777', $declaration->getTransferCompanyAfm());
        $this->assertSame('TRANSFER COMPANY', $declaration->getTransferCompanyName());
    }

    public function test_model_employment_fields(): void
    {
        $declaration = ModificationDeclaration::make()
            ->setHiringDate('28/11/2025')
            ->setExperienceYears(5)
            ->setGrossSalary(1800.00)
            ->setHourlyWage(12.00)
            ->setEmploymentType(0)
            ->setSpecialCase('');

        $this->assertSame('28/11/2025', $declaration->getHiringDate());
        $this->assertSame(5, $declaration->getExperienceYears());
        $this->assertSame(1800.0, $declaration->getGrossSalary());
        $this->assertSame(12.0, $declaration->getHourlyWage());
        $this->assertSame('0', $declaration->getEmploymentType());
        $this->assertSame('', $declaration->getSpecialCase());
    }

    public function test_model_dypa_fields(): void
    {
        $declaration = ModificationDeclaration::make()
            ->setDypaPlacement(true)
            ->setDypaProgram('PROGRAM001')
            ->setReplaceProgram(true)
            ->setReplacedEmployeeAfm('111111111')
            ->setReplacedEmployeeAmka('11111111111');

        $this->assertSame('1', $declaration->getDypaPlacement());
        $this->assertSame('PROGRAM001', $declaration->getDypaProgram());
        $this->assertSame('1', $declaration->getReplaceProgram());
        $this->assertSame('111111111', $declaration->getReplacedEmployeeAfm());
        $this->assertSame('11111111111', $declaration->getReplacedEmployeeAmka());
    }

    public function test_model_trial_period_fields(): void
    {
        $declaration = ModificationDeclaration::make()
            ->setTrialPeriod(true)
            ->setTrialPeriodEndDate('28/05/2026');

        $this->assertSame('1', $declaration->getTrialPeriod());
        $this->assertSame('28/05/2026', $declaration->getTrialPeriodEndDate());
    }

    public function test_model_wage_payment_fields(): void
    {
        $declaration = ModificationDeclaration::make()
            ->setWagePaymentTime('Bi-weekly')
            ->setMandatoryTraining(true)
            ->setCollectiveAgreementApplicable(true)
            ->setCollectiveAgreementComments('Industry CBA');

        $this->assertSame('Bi-weekly', $declaration->getWagePaymentTime());
        $this->assertSame('1', $declaration->getMandatoryTraining());
        $this->assertSame('1', $declaration->getCollectiveAgreementApplicable());
        $this->assertSame('Industry CBA', $declaration->getCollectiveAgreementComments());
    }

    public function test_model_insurance_fields(): void
    {
        $selection = SupplementaryInsuranceSelection::factory()->make(['f_kod_epikourikis' => '003']);

        $declaration = ModificationDeclaration::make()
            ->setMainInsurance('002')
            ->addSupplementaryInsurance($selection)
            ->setAdditionalInsuranceBenefits('Life insurance');

        $this->assertSame('002', $declaration->getMainInsurance());
        $selections = $declaration->getSupplementaryInsuranceSelections();
        $this->assertCount(1, $selections);
        $this->assertSame('003', $selections[0]->getSupplementaryInsuranceCode());
        $this->assertSame('Life insurance', $declaration->getAdditionalInsuranceBenefits());
    }

    private function createModificationDeclaration(): ModificationDeclaration
    {
        return ModificationDeclaration::factory()->make();
    }
}
