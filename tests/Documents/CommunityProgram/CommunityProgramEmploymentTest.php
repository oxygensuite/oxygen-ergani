<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace Tests\Documents\CommunityProgram;

use DateTime;
use OxygenSuite\OxygenErgani\Enums\EmploymentStatus;
use OxygenSuite\OxygenErgani\Enums\EmploymentType;
use OxygenSuite\OxygenErgani\Enums\MaritalStatus;
use OxygenSuite\OxygenErgani\Enums\Sex;
use OxygenSuite\OxygenErgani\Http\Documents\CommunityProgram\CommunityProgramEmployment;
use OxygenSuite\OxygenErgani\Http\Documents\CommunityProgram\CommunityProgramEmploymentCorrection;
use OxygenSuite\OxygenErgani\Models\CommunityProgram\CommunityProgramDeclaration;
use Tests\TestCase;

class CommunityProgramEmploymentTest extends TestCase
{
    public function test_community_program_employment_submit(): void
    {
        $document = new CommunityProgramEmployment('test-access-token');
        $mock = $this->mockResponse(200, 'community-program-employment.json');
        $document->getConfig()->setHandler($mock);
        $response = $document->handle($this->sampleDeclaration());

        $this->assertStringEndsWith('Documents/WebE31', $mock->getLastRequest()->getUri()->getPath());
        $this->assertSame('600', $response[0]->id);
        $this->assertSame('E31-600', $response[0]->protocol);

        $body = json_decode((string) $mock->getLastRequest()->getBody(), true);
        $this->assertCount(1, $body['AnaggeliesE31']['AnaggeliaE31']);
    }

    public function test_community_program_employment_correction_submit(): void
    {
        $declaration = $this->sampleDeclaration()
            ->setRelatedProtocol('E31-600')
            ->setRelatedDate(new DateTime('2026-10-15'));

        $document = new CommunityProgramEmploymentCorrection('test-access-token');
        $mock = $this->mockResponse(200, 'community-program-employment.json');
        $document->getConfig()->setHandler($mock);
        $document->handle([$declaration, $this->sampleDeclaration()]);

        $this->assertStringEndsWith('Documents/WebE31C', $mock->getLastRequest()->getUri()->getPath());

        $body = json_decode((string) $mock->getLastRequest()->getBody(), true);
        $this->assertCount(2, $body['AnaggeliesE31']['AnaggeliaE31']);
        $item = $body['AnaggeliesE31']['AnaggeliaE31'][0];
        $this->assertSame('E31-600', $item['f_protocol_sx']);
        $this->assertSame('15/10/2026', $item['f_date_ypovolis_sx']);
    }

    /**
     * The payload must match the official ERGANI E3.1 sample (testE31.json), field for field and in order.
     */
    public function test_matches_ergani_sample(): void
    {
        $expected = [
            'f_aa_pararthmatos' => '0',
            'f_protocol_sx' => '',
            'f_date_ypovolis_sx' => '',
            'f_ypiresia_oaed' => '101213',
            'f_ypiresia_sepe' => '24010',
            'f_programma_benefit' => '363201-24',
            'f_mer_organization' => 'ΔΟΚΙΜΗ',
            'f_mer_stakod1' => '7022',
            'f_mer_stakod2' => '0112',
            'f_mer_stakod3' => '0114',
            'f_mer_stakod4' => '0116',
            'f_mer_stakod5' => '0119',
            'f_mpa_stakod' => '7022',
            'f_mpa_kallikratis' => '91860101',
            'f_mea_eponimo' => 'ΔΟΚΙΜΗ',
            'f_mea_onoma' => 'ΓΕΩΡΓΙΟΣ',
            'f_mea_eponimo_patera' => 'ΔΟΚΙΜΗ',
            'f_mea_onoma_patera' => 'ΓΕΩΡΓΙΟΣ',
            'f_mea_eponimo_miteras' => 'ΔΟΚΙΜΗ',
            'f_mea_onoma_miteras' => 'ΓΕΩΡΓΙΟΣ',
            'f_mea_sex' => '0',
            'f_mea_birth_city' => 'ΔΟΚΙΜΗ',
            'f_mea_date_birth' => '08/09/2000',
            'f_mea_nationality' => '048',
            'f_mea_typos_taytotitas' => 'ΔAT',
            'f_mea_ar_taytotitas' => 'ΑΒ123456',
            'f_mea_ekdousa' => 'Α.Τ. ΑΘΗΝΩΝ',
            'f_mea_date_ekdosis' => '08/09/2020',
            'f_mea_date_ekdosis_lixi' => '08/09/2026',
            'f_mea_res_permit' => '',
            'f_mea_res_permit_type' => '',
            'f_mea_res_permit_ar' => '',
            'f_mea_res_permit_lixi' => '',
            'f_mea_marital_status' => '0',
            'f_mea_ar_teknon' => '0',
            'f_mea_afm' => '000000000',
            'f_mea_doy' => '1190',
            'f_mea_insurance_agency' => '01',
            'f_mea_am_ika' => '2024',
            'f_mea_amka' => '01010101010',
            'f_mea_code_anergias' => 'ΔΟΚΙΜΗ',
            'f_mea_dieythinsi' => 'ΔΟΚΙΜΗ',
            'f_mea_kallikratis' => '',
            'f_mea_tk' => '10434',
            'f_mea_til' => 'ΔΟΚΙΜΗ',
            'f_mea_fax' => 'ΔΟΚΙΜΗ',
            'f_mea_email' => 'test@example.gr',
            'f_mea_epipedo_morfosis' => '1',
            'f_mea_professional_education' => '',
            'f_mea_expertise_field' => '',
            'f_mea_subject_area' => '6',
            'f_mea_subject_group' => '61',
            'f_mea_education_agency' => '3',
            'f_mea_education_date_from' => '08/01/2024',
            'f_mea_education_date_to' => '08/09/2024',
            'f_mea_duration' => '160',
            'f_mea_education_year' => '2026',
            'f_mea_fl1' => '',
            'f_mea_fl2' => '',
            'f_mea_fl3' => '',
            'f_mea_fl4' => '',
            'f_mea_pc' => '',
            'f_mea_pc_other' => 'ΔΟΚΙΜΗ',
            'f_date_proslipsis' => '15/09/2025',
            'f_date_time_proslipsis' => '09:00',
            'f_orario' => 'ΔΟΚΙΜΗ',
            'f_week_hours' => '40',
            'f_orario_dialeima' => '30',
            'f_wres_external' => '0',
            'f_eidikothta_programma' => '00000',
            'f_eidikothta' => '212208',
            'f_proipiresia' => '0',
            'f_apodoxes' => '1.000,00',
            'f_hour_apodoxes' => '10,00',
            'f_proti_ergasia' => '0',
            'f_sxesh_apasxolhshs' => '1',
            'f_orismenou_apo' => '15/09/2025',
            'f_orismenou_ews' => '30/11/2025',
            'f_kathestos_apasxolhshs' => '0',
            'f_foreas_topothetisis' => 'ΔΟΚΙΜΗ',
            'f_topothetisi_epistoli' => '1',
            'f_replace_programa' => '0',
            'f_replace_programa_afm' => '',
            'f_replace_programa_amka' => '',
            'f_epidoma_anergeias' => '0',
            'f_ypiresia_oaed_anergeias' => '',
            'f_comments' => 'ΔΟΚΙΜΗ',
            'f_mek_afm' => '078776545',
            'f_eponymo' => 'ΠΑΠΑΔΟΠΟΥΛΟΣ',
            'f_onoma' => 'ΓΕΩΡΓΙΟΣ',
            'f_idiotita' => 'ΔΟΚΙΜΗ',
            'f_dieythinsi' => 'ΔΟΚΙΜΗ',
            'f_afm_bebaiwn' => '000000001',
            'f_file' => '',
            'f_foreign_file' => '',
        ];

        $this->assertSame($expected, $this->sampleDeclaration()->toSortedArray());
    }

    public function test_model_accessors(): void
    {
        $declaration = $this->sampleDeclaration()
            ->setSex(Sex::FEMALE)
            ->setMaritalStatus(MaritalStatus::MARRIED)
            ->setEmploymentType(EmploymentType::INDEFINITE)
            ->setEmploymentStatus(EmploymentStatus::PARTIAL)
            ->setForeignLanguages(['001', '002'])
            ->replacesBeneficiary('123456789', '01010101011')
            ->receivesUnemploymentBenefitFrom('101213');

        $this->assertSame('0', $declaration->getBranchCode());
        $this->assertSame('101213', $declaration->getDypaServiceCode());
        $this->assertSame('24010', $declaration->getLaborInspectionCode());
        $this->assertSame('363201-24', $declaration->getProgramCode());
        $this->assertSame('ΔΟΚΙΜΗ', $declaration->getEmployerOrganization());
        $this->assertSame('7022', $declaration->getMainActivityCode());
        $this->assertSame('0112', $declaration->getSecondaryActivityCode1());
        $this->assertSame('0119', $declaration->getSecondaryActivityCode4());
        $this->assertSame('7022', $declaration->getBranchActivityCode());
        $this->assertSame('91860101', $declaration->getMunicipalityCode());
        $this->assertSame('1', $declaration->getSex());
        $this->assertSame('1', $declaration->getMaritalStatus());
        $this->assertSame('08/09/2000', $declaration->getBirthDate());
        $this->assertSame('000000000', $declaration->getAfm());
        $this->assertSame('01010101010', $declaration->getAmka());
        $this->assertSame('2024', $declaration->getAmika());
        $this->assertSame('01', $declaration->getInsuranceAgency());
        $this->assertSame('test@example.gr', $declaration->getEmail());
        $this->assertSame(['001', '002'], $declaration->getForeignLanguages());
        $this->assertSame('', $declaration->getForeignLanguage3());
        $this->assertSame('15/09/2025', $declaration->getPlacementDate());
        $this->assertSame('09:00', $declaration->getPlacementTime());
        $this->assertSame('40', $declaration->getWeeklyHours());
        $this->assertSame('ΔΟΚΙΜΗ', $declaration->getScheduleDescription());
        $this->assertSame('30', $declaration->getBreakDescription());
        $this->assertSame(0, $declaration->getExperienceYears());
        $this->assertSame(1000.0, $declaration->getGrossSalary());
        $this->assertSame(10.0, $declaration->getHourlyWage());
        $this->assertSame('0', $declaration->getEmploymentType());
        $this->assertSame('1', $declaration->getEmploymentStatus());
        $this->assertSame('15/09/2025', $declaration->getStartDate());
        $this->assertSame('30/11/2025', $declaration->getEndDate());
        $this->assertSame('1', $declaration->getReplacement());
        $this->assertSame('123456789', $declaration->getReplacedAfm());
        $this->assertSame('01010101011', $declaration->getReplacedAmka());
        $this->assertSame('1', $declaration->getReceivesUnemploymentBenefit());
        $this->assertSame('101213', $declaration->getUnemploymentBenefitServiceCode());
        $this->assertSame('078776545', $declaration->getLegalRepresentativeAfm());
        $this->assertSame('ΠΑΠΑΔΟΠΟΥΛΟΣ', $declaration->getCertifierLastName());
        $this->assertSame('000000001', $declaration->getCertifierAfm());
    }

    public function test_weekly_hours_accepts_integers(): void
    {
        $declaration = CommunityProgramDeclaration::make()->setWeeklyHours(25);

        $this->assertSame('25', $declaration->toSortedArray()['f_week_hours']);
    }

    public function test_datetime_support(): void
    {
        $declaration = CommunityProgramDeclaration::make()
            ->setBirthDate(new DateTime('2000-09-08'))
            ->setIdIssueDate(new DateTime('2020-09-08'))
            ->setTrainingPeriod(new DateTime('2024-01-08'), new DateTime('2024-09-08'))
            ->setPlacementDate(new DateTime('2025-09-15'))
            ->setFixedTermPeriod(new DateTime('2025-09-15'), new DateTime('2025-11-30'))
            ->setRelatedDate(new DateTime('2026-10-15'));

        $this->assertSame('08/09/2000', $declaration->getBirthDate());
        $this->assertSame('08/09/2020', $declaration->getIdIssueDate());
        $this->assertSame('08/01/2024', $declaration->getTrainingDateFrom());
        $this->assertSame('08/09/2024', $declaration->getTrainingDateTo());
        $this->assertSame('15/09/2025', $declaration->getPlacementDate());
        $this->assertSame('15/09/2025', $declaration->getStartDate());
        $this->assertSame('30/11/2025', $declaration->getEndDate());
        $this->assertSame('15/10/2026', $declaration->getRelatedDate());
    }

    private function sampleDeclaration(): CommunityProgramDeclaration
    {
        return CommunityProgramDeclaration::make()
            ->setBranchCode('0')
            ->setRelatedProtocol('')
            ->setRelatedDate('')
            ->setDypaServiceCode('101213')
            ->setLaborInspectionCode('24010')
            ->setProgramCode('363201-24')
            ->setEmployerOrganization('ΔΟΚΙΜΗ')
            ->setMainActivityCode('7022')
            ->setSecondaryActivityCode1('0112')
            ->setSecondaryActivityCode2('0114')
            ->setSecondaryActivityCode3('0116')
            ->setSecondaryActivityCode4('0119')
            ->setBranchActivityCode('7022')
            ->setMunicipalityCode('91860101')
            ->setLastName('ΔΟΚΙΜΗ')
            ->setFirstName('ΓΕΩΡΓΙΟΣ')
            ->setFatherLastName('ΔΟΚΙΜΗ')
            ->setFatherName('ΓΕΩΡΓΙΟΣ')
            ->setMotherLastName('ΔΟΚΙΜΗ')
            ->setMotherName('ΓΕΩΡΓΙΟΣ')
            ->setSex(Sex::MALE)
            ->setBirthPlace('ΔΟΚΙΜΗ')
            ->setBirthDate('08/09/2000')
            ->setNationality('048')
            ->setIdType('ΔAT')
            ->setIdNumber('ΑΒ123456')
            ->setIdIssuingAuthority('Α.Τ. ΑΘΗΝΩΝ')
            ->setIdIssueDate('08/09/2020')
            ->setIdExpiryDate('08/09/2026')
            ->setResidencePermit('')
            ->setResidencePermitType('')
            ->setResidencePermitNumber('')
            ->setResidencePermitExpiry('')
            ->setMaritalStatus(MaritalStatus::SINGLE)
            ->setNumberOfChildren('0')
            ->setAfm('000000000')
            ->setTaxOffice('1190')
            ->setInsuranceAgency('01')
            ->setAmika('2024')
            ->setAmka('01010101010')
            ->setUnemploymentCardNumber('ΔΟΚΙΜΗ')
            ->setAddress('ΔΟΚΙΜΗ')
            ->setResidenceMunicipalityCode('')
            ->setPostalCode('10434')
            ->setPhone('ΔΟΚΙΜΗ')
            ->setFax('ΔΟΚΙΜΗ')
            ->setEmail('test@example.gr')
            ->setEducationLevel('1')
            ->setProfessionalTraining('')
            ->setTrainingSubject('')
            ->setSubjectArea('6')
            ->setSubjectGroup('61')
            ->setEducationAgency('3')
            ->setTrainingDateFrom('08/01/2024')
            ->setTrainingDateTo('08/09/2024')
            ->setTrainingDuration('160')
            ->setTrainingYear('2026')
            ->setForeignLanguage1('')
            ->setForeignLanguage2('')
            ->setForeignLanguage3('')
            ->setForeignLanguage4('')
            ->setComputerSkills('')
            ->setComputerSkillsOther('ΔΟΚΙΜΗ')
            ->setPlacementDate('15/09/2025')
            ->setPlacementTime('09:00')
            ->setScheduleDescription('ΔΟΚΙΜΗ')
            ->setWeeklyHours('40')
            ->setBreakDescription('30')
            ->setScheduleAttached(false)
            ->setProgramSpecialtyCode('00000')
            ->setSpecialtyCode('212208')
            ->setExperienceYears(0)
            ->setGrossSalary(1000.0)
            ->setHourlyWage(10.0)
            ->setFirstEmployment(false)
            ->setEmploymentType(EmploymentType::FIXED_TERM)
            ->setStartDate('15/09/2025')
            ->setEndDate('30/11/2025')
            ->setEmploymentStatus(EmploymentStatus::FULL)
            ->setPlacementAgency('ΔΟΚΙΜΗ')
            ->setDypaReferral(true)
            ->setReplacement(false)
            ->setReplacedAfm('')
            ->setReplacedAmka('')
            ->setReceivesUnemploymentBenefit(false)
            ->setUnemploymentBenefitServiceCode('')
            ->setComments('ΔΟΚΙΜΗ')
            ->setLegalRepresentativeAfm('078776545')
            ->setCertifierLastName('ΠΑΠΑΔΟΠΟΥΛΟΣ')
            ->setCertifierFirstName('ΓΕΩΡΓΙΟΣ')
            ->setCertifierCapacity('ΔΟΚΙΜΗ')
            ->setCertifierAddress('ΔΟΚΙΜΗ')
            ->setCertifierAfm('000000001')
            ->setFormFile('')
            ->setForeignFile('');
    }
}
