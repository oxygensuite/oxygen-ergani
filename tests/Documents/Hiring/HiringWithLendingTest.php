<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace Tests\Documents\Hiring;

use OxygenSuite\OxygenErgani\Http\Documents\Hiring\HiringWithLending;
use OxygenSuite\OxygenErgani\Models\Hiring\LendingDeclaration;
use OxygenSuite\OxygenErgani\Models\Hiring\SupplementaryInsuranceSelection;
use Tests\TestCase;

class HiringWithLendingTest extends TestCase
{
    public function test_submit(): void
    {
        $declaration = $this->createLendingDeclaration()
            ->setLendingDateFrom('28/11/2025')
            ->setLendingDateTo('07/12/2025')
            ->setDirectEmployerAfm('666666666')
            ->setDirectEmployerName('LENDING COMPANY')
            ->setIndividualContract(0);

        $document = new HiringWithLending('test-access-token');
        $document->getConfig()->setHandler($this->mockResponse(200, 'hiring-with-lending.json'));
        $response = $document->handle($declaration);

        $this->assertIsArray($response);
        $this->assertSame('203', $response[0]->id);
        $this->assertSame('E3PD203', $response[0]->protocol);
        $this->assertSame('28/11/2025 11:00', $response[0]->submissionDate->format('d/m/Y H:i'));
    }

    public function test_model_lending_fields(): void
    {
        $declaration = LendingDeclaration::make()
            ->setLendingDateFrom('28/11/2025')
            ->setLendingDateTo('07/12/2025')
            ->setDirectEmployerAfm('888888888')
            ->setDirectEmployerName('LENDING COMPANY');

        $this->assertSame('28/11/2025', $declaration->getLendingDateFrom());
        $this->assertSame('07/12/2025', $declaration->getLendingDateTo());
        $this->assertSame('888888888', $declaration->getDirectEmployerAfm());
        $this->assertSame('LENDING COMPANY', $declaration->getDirectEmployerName());
    }

    public function test_model_employment_fields(): void
    {
        $declaration = LendingDeclaration::make()
            ->setHiringDate('28/11/2025')
            ->setExperienceYears(3)
            ->setGrossSalary(1200.00)
            ->setHourlyWage(8.00)
            ->setEmploymentType(1)
            ->setFixedTermFrom('01/01/2025')
            ->setFixedTermTo('31/12/2025')
            ->setSpecialCase('');

        $this->assertSame('28/11/2025', $declaration->getHiringDate());
        $this->assertSame(3, $declaration->getExperienceYears());
        $this->assertSame(1200.0, $declaration->getGrossSalary());
        $this->assertSame(8.0, $declaration->getHourlyWage());
        $this->assertSame('1', $declaration->getEmploymentType());
        $this->assertSame('01/01/2025', $declaration->getFixedTermFrom());
        $this->assertSame('31/12/2025', $declaration->getFixedTermTo());
        $this->assertSame('', $declaration->getSpecialCase());
    }

    public function test_model_dypa_fields(): void
    {
        $declaration = LendingDeclaration::make()
            ->setDypaPlacement(true)
            ->setDypaProgram('LENDING_PROGRAM')
            ->setReplaceProgram(false)
            ->setReplacedEmployeeAfm('')
            ->setReplacedEmployeeAmka('');

        $this->assertSame('1', $declaration->getDypaPlacement());
        $this->assertSame('LENDING_PROGRAM', $declaration->getDypaProgram());
        $this->assertSame('0', $declaration->getReplaceProgram());
        $this->assertSame('', $declaration->getReplacedEmployeeAfm());
        $this->assertSame('', $declaration->getReplacedEmployeeAmka());
    }

    public function test_model_trial_period_fields(): void
    {
        $declaration = LendingDeclaration::make()
            ->setTrialPeriod(true)
            ->setTrialPeriodEndDate('28/02/2026');

        $this->assertSame('1', $declaration->getTrialPeriod());
        $this->assertSame('28/02/2026', $declaration->getTrialPeriodEndDate());
    }

    public function test_model_acceptance_files_fields(): void
    {
        $declaration = LendingDeclaration::make()
            ->setBasicsAcceptance(true)
            ->setFile('base64encodedcontent')
            ->setIndividualContract(1)
            ->setContractFile('contractbase64');

        $this->assertSame('1', $declaration->getBasicsAcceptance());
        $this->assertSame('base64encodedcontent', $declaration->getFile());
        $this->assertSame('1', $declaration->getIndividualContract());
        $this->assertSame('contractbase64', $declaration->getContractFile());
    }

    public function test_model_wage_payment_fields(): void
    {
        $declaration = LendingDeclaration::make()
            ->setWagePaymentTime('Weekly')
            ->setMandatoryTraining(true)
            ->setCollectiveAgreementApplicable(true)
            ->setCollectiveAgreementComments('Lending CBA');

        $this->assertSame('Weekly', $declaration->getWagePaymentTime());
        $this->assertSame('1', $declaration->getMandatoryTraining());
        $this->assertSame('1', $declaration->getCollectiveAgreementApplicable());
        $this->assertSame('Lending CBA', $declaration->getCollectiveAgreementComments());
    }

    public function test_model_insurance_fields(): void
    {
        $selection = SupplementaryInsuranceSelection::factory()->make(['f_kod_epikourikis' => '005']);

        $declaration = LendingDeclaration::make()
            ->setMainInsurance('004')
            ->addSupplementaryInsurance($selection)
            ->setAdditionalInsuranceBenefits('Health insurance');

        $this->assertSame('004', $declaration->getMainInsurance());
        $selections = $declaration->getSupplementaryInsuranceSelections();
        $this->assertCount(1, $selections);
        $this->assertSame('005', $selections[0]->getSupplementaryInsuranceCode());
        $this->assertSame('Health insurance', $declaration->getAdditionalInsuranceBenefits());
    }

    private function createLendingDeclaration(): LendingDeclaration
    {
        return LendingDeclaration::factory()->make();
    }
}
