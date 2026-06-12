<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace Tests\Documents\Hiring;

use OxygenSuite\OxygenErgani\Factories\Factory;
use OxygenSuite\OxygenErgani\Http\Documents\Hiring\HiringNew;
use OxygenSuite\OxygenErgani\Models\Hiring\NewDeclaration;
use OxygenSuite\OxygenErgani\Models\Hiring\SupplementaryInsuranceSelection;
use Tests\TestCase;

class HiringNewTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Factory::resetFaker();
    }

    public function test_submit(): void
    {
        $declaration = NewDeclaration::factory()->make();

        $document = new HiringNew('test-access-token');
        $document->getConfig()->setHandler($this->mockResponse(200, 'hiring-new.json'));
        $response = $document->handle($declaration);

        $this->assertIsArray($response);
        $this->assertSame('200', $response[0]->id);
        $this->assertSame('E3N200', $response[0]->protocol);
        $this->assertSame('28/11/2025 09:30', $response[0]->submissionDate->format('d/m/Y H:i'));
    }

    public function test_submit_with_multiple_declarations(): void
    {
        $declarations = NewDeclaration::factory(2)->make();

        $document = new HiringNew('test-access-token');
        $document->getConfig()->setHandler($this->mockResponse(200, 'hiring-new.json'));
        $response = $document->handle($declarations);

        $this->assertIsArray($response);
        $this->assertSame('200', $response[0]->id);
    }

    public function test_model_basic_fields(): void
    {
        $declaration = NewDeclaration::make()
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
        $declaration = NewDeclaration::make()
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

    public function test_model_identity_fields(): void
    {
        $declaration = NewDeclaration::make()
            ->setNationality('048')
            ->setIdType('ΔAT')
            ->setIdNumber('Λ010123')
            ->setIdIssuingAuthority('Α.Τ. ΝΕΑΣ ΙΩΝΙΑΣ')
            ->setIdIssueDate('01/01/2000')
            ->setIdExpiryDate('01/01/2030');

        $this->assertSame('048', $declaration->getNationality());
        $this->assertSame('ΔAT', $declaration->getIdType());
        $this->assertSame('Λ010123', $declaration->getIdNumber());
        $this->assertSame('Α.Τ. ΝΕΑΣ ΙΩΝΙΑΣ', $declaration->getIdIssuingAuthority());
        $this->assertSame('01/01/2000', $declaration->getIdIssueDate());
        $this->assertSame('01/01/2030', $declaration->getIdExpiryDate());
    }

    public function test_model_residence_permit_direct_access(): void
    {
        $declaration = NewDeclaration::make()
            ->setResPermitDirectAccess(true)
            ->setResPermitDirectAccessType('12345')
            ->setResPermitDirectAccessNumber('RP001')
            ->setResPermitDirectAccessExpiry('31/12/2030');

        $this->assertSame('1', $declaration->getResPermitDirectAccess());
        $this->assertSame('12345', $declaration->getResPermitDirectAccessType());
        $this->assertSame('RP001', $declaration->getResPermitDirectAccessNumber());
        $this->assertSame('31/12/2030', $declaration->getResPermitDirectAccessExpiry());
    }

    public function test_model_residence_permit_approval(): void
    {
        $declaration = NewDeclaration::make()
            ->setResPermitApproval(false)
            ->setResPermitApprovalType('54321')
            ->setResPermitApprovalNumber('RP002')
            ->setResPermitApprovalExpiry('31/12/2031');

        $this->assertSame('0', $declaration->getResPermitApproval());
        $this->assertSame('54321', $declaration->getResPermitApprovalType());
        $this->assertSame('RP002', $declaration->getResPermitApprovalNumber());
        $this->assertSame('31/12/2031', $declaration->getResPermitApprovalExpiry());
    }

    public function test_model_visa_fields(): void
    {
        $declaration = NewDeclaration::make()
            ->setSeasonalWorkVisa('1')
            ->setSeasonalWorkVisaNumber('VISA001')
            ->setSeasonalWorkVisaFrom('01/06/2025')
            ->setSeasonalWorkVisaTo('30/09/2025');

        $this->assertSame('1', $declaration->getSeasonalWorkVisa());
        $this->assertSame('VISA001', $declaration->getSeasonalWorkVisaNumber());
        $this->assertSame('01/06/2025', $declaration->getSeasonalWorkVisaFrom());
        $this->assertSame('30/09/2025', $declaration->getSeasonalWorkVisaTo());
    }

    public function test_model_tax_insurance_fields(): void
    {
        $declaration = NewDeclaration::make()
            ->setMaritalStatus(1)
            ->setNumberOfChildren(2)
            ->setAfm('999999999')
            ->setTaxOffice('1145')
            ->setAmika('53454486')
            ->setAmka('01010101010')
            ->setUnemploymentCardNumber('UNEMP001')
            ->setMinorWorkBookNumber('MINOR001')
            ->setEducationLevel('11');

        $this->assertSame('1', $declaration->getMaritalStatus());
        $this->assertSame(2, $declaration->getNumberOfChildren());
        $this->assertSame('999999999', $declaration->getAfm());
        $this->assertSame('1145', $declaration->getTaxOffice());
        $this->assertSame('53454486', $declaration->getAmika());
        $this->assertSame('01010101010', $declaration->getAmka());
        $this->assertSame('UNEMP001', $declaration->getUnemploymentCardNumber());
        $this->assertSame('MINOR001', $declaration->getMinorWorkBookNumber());
        $this->assertSame('11', $declaration->getEducationLevel());
    }

    public function test_model_employment_fields(): void
    {
        $declaration = NewDeclaration::make()
            ->setHiringDate('28/11/2025')
            ->setStartTime('09:00')
            ->setEndTime('17:00')
            ->setWeeklyHours(38.0)
            ->setSpecialtyCode('313200')
            ->setSpecialtyDescription('Software Engineer')
            ->setExperienceYears(10)
            ->setGrossSalary(1500.00)
            ->setHourlyWage(10.00)
            ->setEmploymentType(1)
            ->setFixedTermFrom('04/01/2024')
            ->setFixedTermTo('02/01/2025')
            ->setEmploymentStatus(0)
            ->setWorkerType(1)
            ->setSpecialCase('')
            ->setResponsiblePosition('');

        $this->assertSame('28/11/2025', $declaration->getHiringDate());
        $this->assertSame('09:00', $declaration->getStartTime());
        $this->assertSame('17:00', $declaration->getEndTime());
        $this->assertSame(38.0, $declaration->getWeeklyHours());
        $this->assertSame('313200', $declaration->getSpecialtyCode());
        $this->assertSame('Software Engineer', $declaration->getSpecialtyDescription());
        $this->assertSame(10, $declaration->getExperienceYears());
        $this->assertSame(1500.0, $declaration->getGrossSalary());
        $this->assertSame(10.0, $declaration->getHourlyWage());
        $this->assertSame('1', $declaration->getEmploymentType());
        $this->assertSame('04/01/2024', $declaration->getFixedTermFrom());
        $this->assertSame('02/01/2025', $declaration->getFixedTermTo());
        $this->assertSame('0', $declaration->getEmploymentStatus());
        $this->assertSame('1', $declaration->getWorkerType());
        $this->assertSame('', $declaration->getSpecialCase());
        $this->assertSame('', $declaration->getResponsiblePosition());
    }

    public function test_model_work_organization_fields(): void
    {
        $declaration = NewDeclaration::make()
            ->setWorkingTimeDigitalOrganization(true)
            ->setFullEmploymentHours(40.0)
            ->setWeekDays(5)
            ->setFlexibleArrivalMinutes(15)
            ->setWorkingCard(true)
            ->setBreakMinutes(30)
            ->setBreakWithinSchedule(true);

        $this->assertSame('1', $declaration->getWorkingTimeDigitalOrganization());
        $this->assertSame(40.0, $declaration->getFullEmploymentHours());
        $this->assertSame('5', $declaration->getWeekDays());
        $this->assertSame(15, $declaration->getFlexibleArrivalMinutes());
        $this->assertSame('1', $declaration->getWorkingCard());
        $this->assertSame(30, $declaration->getBreakMinutes());
        $this->assertSame('1', $declaration->getBreakWithinSchedule());
    }

    public function test_model_dypa_fields(): void
    {
        $declaration = NewDeclaration::make()
            ->setDypaPlacement(false)
            ->setDypaProgram('')
            ->setReplaceProgram(false)
            ->setReplacedEmployeeAfm('')
            ->setReplacedEmployeeAmka('');

        $this->assertSame('0', $declaration->getDypaPlacement());
        $this->assertSame('', $declaration->getDypaProgram());
        $this->assertSame('0', $declaration->getReplaceProgram());
        $this->assertSame('', $declaration->getReplacedEmployeeAfm());
        $this->assertSame('', $declaration->getReplacedEmployeeAmka());
    }

    public function test_model_trial_period_fields(): void
    {
        $declaration = NewDeclaration::make()
            ->setTrialPeriod(true)
            ->setTrialPeriodEndDate('28/02/2026');

        $this->assertSame('1', $declaration->getTrialPeriod());
        $this->assertSame('28/02/2026', $declaration->getTrialPeriodEndDate());
    }

    public function test_model_files_fields(): void
    {
        $declaration = NewDeclaration::make()
            ->setBasicsAcceptance(false)
            ->setFile('SGVsbG8sIHdvcmxkIQ==')
            ->setContractFile('')
            ->setComments('Test comments')
            ->setForeignWorkerFile('')
            ->setMinorWorkerFile('');

        $this->assertSame('0', $declaration->getBasicsAcceptance());
        $this->assertSame('SGVsbG8sIHdvcmxkIQ==', $declaration->getFile());
        $this->assertSame('', $declaration->getContractFile());
        $this->assertSame('Test comments', $declaration->getComments());
        $this->assertSame('', $declaration->getForeignWorkerFile());
        $this->assertSame('', $declaration->getMinorWorkerFile());
    }

    public function test_model_wage_payment_fields(): void
    {
        $declaration = NewDeclaration::make()
            ->setWagePaymentTime('Monthly')
            ->setMandatoryTraining(false)
            ->setCollectiveAgreementApplicable(true)
            ->setCollectiveAgreementComments('CBA Comments');

        $this->assertSame('Monthly', $declaration->getWagePaymentTime());
        $this->assertSame('0', $declaration->getMandatoryTraining());
        $this->assertSame('1', $declaration->getCollectiveAgreementApplicable());
        $this->assertSame('CBA Comments', $declaration->getCollectiveAgreementComments());
    }

    public function test_model_insurance_fields(): void
    {
        $selection1 = SupplementaryInsuranceSelection::factory()->make(['f_kod_epikourikis' => '001']);
        $selection2 = SupplementaryInsuranceSelection::factory()->make(['f_kod_epikourikis' => '002']);

        $declaration = NewDeclaration::make()
            ->setMainInsurance('001')
            ->addSupplementaryInsurance($selection1)
            ->addSupplementaryInsurance($selection2)
            ->setAdditionalInsuranceBenefits('Additional benefits');

        $this->assertSame('001', $declaration->getMainInsurance());
        $selections = $declaration->getSupplementaryInsuranceSelections();
        $this->assertCount(2, $selections);
        $this->assertSame('001', $selections[0]->getSupplementaryInsuranceCode());
        $this->assertSame('002', $selections[1]->getSupplementaryInsuranceCode());
        $this->assertSame('Additional benefits', $declaration->getAdditionalInsuranceBenefits());
    }

    public function test_model_unpredictable_schedule_fields(): void
    {
        $declaration = NewDeclaration::make()
            ->setUnpredictableSchedule(true)
            ->setReferenceDaysHours('Mon-Fri, 09:00-17:00')
            ->setMinNotificationPeriod('24 hours')
            ->setAssignmentCancellationDeadline('12 hours');

        $this->assertSame('1', $declaration->getUnpredictableSchedule());
        $this->assertSame('Mon-Fri, 09:00-17:00', $declaration->getReferenceDaysHours());
        $this->assertSame('24 hours', $declaration->getMinNotificationPeriod());
        $this->assertSame('12 hours', $declaration->getAssignmentCancellationDeadline());
    }

    public function test_model_work_location_fields(): void
    {
        $declaration = NewDeclaration::make()
            ->setWorkLocation(1)
            ->setWorkLocationComment('Remote work from home');

        $this->assertSame('1', $declaration->getWorkLocation());
        $this->assertSame('Remote work from home', $declaration->getWorkLocationComment());
    }

    public function test_supplementary_insurance_selection_model(): void
    {
        $selection = SupplementaryInsuranceSelection::factory()->make(['f_kod_epikourikis' => '001']);

        $this->assertSame('001', $selection->getSupplementaryInsuranceCode());

        $array = $selection->toSortedArray();
        $this->assertArrayHasKey('f_kod_epikourikis', $array);
        $this->assertSame('001', $array['f_kod_epikourikis']);
    }

    public function test_model_to_sorted_array(): void
    {
        $declaration = NewDeclaration::factory()
            ->withSupplementaryInsurance(['001'])
            ->make();

        $array = $declaration->toSortedArray();

        // Branch/Location
        $this->assertArrayHasKey('f_aa_pararthmatos', $array);
        $this->assertArrayHasKey('f_rel_protocol', $array);
        $this->assertArrayHasKey('f_ypiresia_sepe', $array);
        $this->assertArrayHasKey('f_ypiresia_oaed', $array);

        // Personal
        $this->assertArrayHasKey('f_eponymo', $array);
        $this->assertArrayHasKey('f_onoma', $array);
        $this->assertArrayHasKey('f_birthdate', $array);

        // Employment
        $this->assertArrayHasKey('f_proslipsidate', $array);
        $this->assertArrayHasKey('f_week_hours', $array);
        $this->assertArrayHasKey('f_apodoxes', $array);

        // Work Organization
        $this->assertArrayHasKey('f_working_time_digital_organization', $array);
        $this->assertArrayHasKey('f_week_days', $array);
        $this->assertArrayHasKey('f_working_card', $array);

        // Insurance
        $this->assertArrayHasKey('f_kyria_asfalisi', $array);
        $this->assertArrayHasKey('SupplementaryInsuranceSelections', $array);
    }

    public function test_greek_float_fields_formatted_in_sorted_array(): void
    {
        $declaration = NewDeclaration::make()
            ->setGrossSalary(1500.00)
            ->setHourlyWage(10.50)
            ->setWeeklyHours(38.0)
            ->setFullEmploymentHours(40.0);

        // Getters should return floats
        $this->assertSame(1500.0, $declaration->getGrossSalary());
        $this->assertSame(10.5, $declaration->getHourlyWage());
        $this->assertSame(38.0, $declaration->getWeeklyHours());
        $this->assertSame(40.0, $declaration->getFullEmploymentHours());

        // toSortedArray() should format values in Greek format
        $array = $declaration->toSortedArray();
        $this->assertSame('1.500,00', $array['f_apodoxes']);
        $this->assertSame('10,50', $array['f_hour_apodoxes']);
        $this->assertSame('38,0', $array['f_week_hours']);          // 1 decimal per XSD
        $this->assertSame('40,0', $array['f_full_employment_hours']); // 1 decimal per XSD
    }
}
