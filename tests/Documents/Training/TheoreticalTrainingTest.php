<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace Tests\Documents\Training;

use DateTime;
use OxygenSuite\OxygenErgani\Enums\MaritalStatus;
use OxygenSuite\OxygenErgani\Enums\Sex;
use OxygenSuite\OxygenErgani\Http\Documents\Training\TheoreticalTraining;
use OxygenSuite\OxygenErgani\Http\Documents\Training\TheoreticalTrainingCorrection;
use OxygenSuite\OxygenErgani\Models\Training\TheoreticalTrainingDeclaration;
use Tests\TestCase;

class TheoreticalTrainingTest extends TestCase
{
    public function test_theoretical_training_submit(): void
    {
        $document = new TheoreticalTraining('test-access-token');
        $mock = $this->mockResponse(200, 'theoretical-training.json');
        $document->getConfig()->setHandler($mock);
        $response = $document->handle($this->sampleDeclaration());

        $this->assertStringEndsWith('Documents/WebE32', $mock->getLastRequest()->getUri()->getPath());
        $this->assertSame('700', $response[0]->id);
        $this->assertSame('E32-700', $response[0]->protocol);

        $body = json_decode((string) $mock->getLastRequest()->getBody(), true);
        $this->assertCount(1, $body['AnaggeliesE32']['AnaggeliaE32']);
    }

    public function test_theoretical_training_correction_submit(): void
    {
        $declaration = $this->sampleDeclaration()
            ->setRelatedProtocol('E32-700')
            ->setRelatedDate(new DateTime('2026-10-15'));

        $document = new TheoreticalTrainingCorrection('test-access-token');
        $mock = $this->mockResponse(200, 'theoretical-training.json');
        $document->getConfig()->setHandler($mock);
        $document->handle([$declaration]);

        $this->assertStringEndsWith('Documents/WebE32C', $mock->getLastRequest()->getUri()->getPath());

        $body = json_decode((string) $mock->getLastRequest()->getBody(), true);
        $item = $body['AnaggeliesE32']['AnaggeliaE32'][0];
        $this->assertSame('E32-700', $item['f_protocol_sx']);
        $this->assertSame('15/10/2026', $item['f_date_ypovolis_sx']);
    }

    /**
     * The payload must match the official ERGANI E3.2 sample (testE32.json), field for field and in order.
     */
    public function test_matches_ergani_sample(): void
    {
        $expected = [
            'f_aa_pararthmatos' => '0',
            'f_protocol_sx' => '',
            'f_date_ypovolis_sx' => '',
            'f_ypiresia_oaed' => '101213',
            'f_ypiresia_sepe' => '24010',
            'f_programma_voucher_code' => '34',
            'f_programma_voucher' => '002',
            'f_mer_organization' => 'ΔΟΚΙΜΗ',
            'f_mer_stakod1' => '7022',
            'f_mer_stakod2' => '0112',
            'f_mer_stakod3' => '0114',
            'f_mer_stakod4' => '0116',
            'f_mer_stakod5' => '0119',
            'f_mpa_stakod' => '7022',
            'f_mpa_kallikratis' => '91860101',
            'f_eopep_code' => 'ΔΟΚΙΜΗ',
            'f_edu_agency_category' => '001',
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
            'f_mea_ar_vivliou_anilikou' => '',
            'f_mea_dieythinsi' => 'ΔΟΚΙΜΗ',
            'f_mea_kallikratis' => '91860101',
            'f_mea_tk' => '10434',
            'f_mea_til' => 'ΔΟΚΙΜΗ',
            'f_mea_fax' => 'ΔΟΚΙΜΗ',
            'f_mea_email' => 'test@example.gr',
            'f_mea_epipedo_morfosis' => '1',
            'f_mea_practical_education' => '005',
            'f_mea_voucher' => '',
            'f_mea_kayas' => 'ΔΟΚΙΜΗ',
            'f_date_proslipsis' => '15/09/2025',
            'f_week_hours' => '40,0',
            'f_orario' => 'ΔΟΚΙΜΗ',
            'f_orario_dialeima' => '30',
            'f_total_hours_theory' => '10',
            'f_total_hours_practice' => '',
            'f_total_insurance_days' => '',
            'f_daily_apodoxes' => '',
            'f_theory_education' => '001',
            'f_theory_description' => 'ΔΟΚΙΜΗ',
            'f_theory_code' => 'ΔΟΚΙΜΗ',
            'f_orismenou_ews' => '31/10/2025',
            'f_benefiter_status_after' => '001',
            'f_months_unempoyment' => '2',
            'f_benefiter_status_before' => '002',
            'f_comments' => 'ΔΟΚΙΜΗ',
            'f_practice_mer_eponimia' => '',
            'f_practice_mer_afm' => '',
            'f_practice_mer_doy' => '',
            'f_practice_mer_ame' => '',
            'f_practice_mer_no_ame' => '',
            'f_practice_mpa_address' => '',
            'f_practice_mpa_kallikratis' => '',
            'f_practice_mpa_tk' => '',
            'f_practice_mpa_til' => '',
            'f_practice_mpa_fax' => '',
            'f_practice_mpa_email' => '',
            'f_practice_date_from' => '',
            'f_practice_date_to' => '',
            'f_mek_afm' => '078776545',
            'f_file' => '',
            'f_foreign_file' => '',
            'f_young_file' => '',
        ];

        $this->assertSame($expected, $this->sampleDeclaration()->toSortedArray());
    }

    public function test_model_accessors(): void
    {
        $declaration = $this->sampleDeclaration()
            ->setDailyAllowance(32.5)
            ->setPracticeCompanyName('ΕΠΙΧΕΙΡΗΣΗ Α.Ε.')
            ->setPracticeCompanyWithoutAme(true)
            ->setPracticePeriod('01/11/2025', '31/12/2025');

        $this->assertSame('34', $declaration->getProgramCode());
        $this->assertSame('002', $declaration->getInvitationCode());
        $this->assertSame('ΔΟΚΙΜΗ', $declaration->getEopepCode());
        $this->assertSame('001', $declaration->getTrainingProviderCategory());
        $this->assertSame('0', $declaration->getSex());
        $this->assertSame('0', $declaration->getMaritalStatus());
        $this->assertSame('', $declaration->getMinorBookNumber());
        $this->assertSame('005', $declaration->getVocationalTrainingSubject());
        $this->assertSame('ΔΟΚΙΜΗ', $declaration->getKayasCode());
        $this->assertSame('15/09/2025', $declaration->getTrainingStartDate());
        $this->assertSame('31/10/2025', $declaration->getTrainingEndDate());
        $this->assertSame(40.0, $declaration->getWeeklyHours());
        $this->assertSame('10', $declaration->getTheoryHours());
        $this->assertSame('001', $declaration->getTheoryProgram());
        $this->assertSame('001', $declaration->getStatusAtApplication());
        $this->assertSame('2', $declaration->getUnemploymentMonths());
        $this->assertSame('002', $declaration->getStatusBeforeApplication());
        $this->assertSame(32.5, $declaration->getDailyAllowance());
        $this->assertSame('32,50', $declaration->toSortedArray()['f_daily_apodoxes']);
        $this->assertSame('ΕΠΙΧΕΙΡΗΣΗ Α.Ε.', $declaration->getPracticeCompanyName());
        $this->assertSame('1', $declaration->getPracticeCompanyWithoutAme());
        $this->assertSame('01/11/2025', $declaration->getPracticeDateFrom());
        $this->assertSame('31/12/2025', $declaration->getPracticeDateTo());
        $this->assertSame('078776545', $declaration->getLegalRepresentativeAfm());
        $this->assertSame('', $declaration->getMinorFile());
    }

    public function test_datetime_support(): void
    {
        $declaration = TheoreticalTrainingDeclaration::make()
            ->setBirthDate(new DateTime('2000-09-08'))
            ->setTrainingPeriod(new DateTime('2025-09-15'), new DateTime('2025-10-31'))
            ->setPracticePeriod(new DateTime('2025-11-01'), new DateTime('2025-12-31'));

        $this->assertSame('08/09/2000', $declaration->getBirthDate());
        $this->assertSame('15/09/2025', $declaration->getTrainingStartDate());
        $this->assertSame('31/10/2025', $declaration->getTrainingEndDate());
        $this->assertSame('01/11/2025', $declaration->getPracticeDateFrom());
        $this->assertSame('31/12/2025', $declaration->getPracticeDateTo());
    }

    private function sampleDeclaration(): TheoreticalTrainingDeclaration
    {
        return TheoreticalTrainingDeclaration::make()
            ->setBranchCode('0')
            ->setRelatedProtocol('')
            ->setRelatedDate('')
            ->setDypaServiceCode('101213')
            ->setLaborInspectionCode('24010')
            ->setProgramCode('34')
            ->setInvitationCode('002')
            ->setEmployerOrganization('ΔΟΚΙΜΗ')
            ->setMainActivityCode('7022')
            ->setSecondaryActivityCode1('0112')
            ->setSecondaryActivityCode2('0114')
            ->setSecondaryActivityCode3('0116')
            ->setSecondaryActivityCode4('0119')
            ->setBranchActivityCode('7022')
            ->setMunicipalityCode('91860101')
            ->setEopepCode('ΔΟΚΙΜΗ')
            ->setTrainingProviderCategory('001')
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
            ->setMinorBookNumber('')
            ->setAddress('ΔΟΚΙΜΗ')
            ->setResidenceMunicipalityCode('91860101')
            ->setPostalCode('10434')
            ->setPhone('ΔΟΚΙΜΗ')
            ->setFax('ΔΟΚΙΜΗ')
            ->setEmail('test@example.gr')
            ->setEducationLevel('1')
            ->setVocationalTrainingSubject('005')
            ->setVoucherProgram('')
            ->setKayasCode('ΔΟΚΙΜΗ')
            ->setTrainingStartDate('15/09/2025')
            ->setWeeklyHours(40.0)
            ->setScheduleDescription('ΔΟΚΙΜΗ')
            ->setBreakDescription('30')
            ->setTheoryHours('10')
            ->setPracticeHours('')
            ->setInsuranceDays('')
            ->set('f_daily_apodoxes', '')
            ->setTheoryProgram('001')
            ->setTheoryDescription('ΔΟΚΙΜΗ')
            ->setTheoryCode('ΔΟΚΙΜΗ')
            ->setTrainingEndDate('31/10/2025')
            ->setStatusAtApplication('001')
            ->setUnemploymentMonths('2')
            ->setStatusBeforeApplication('002')
            ->setComments('ΔΟΚΙΜΗ')
            ->setPracticeCompanyName('')
            ->setPracticeCompanyAfm('')
            ->setPracticeCompanyTaxOffice('')
            ->setPracticeCompanyAme('')
            ->setPracticeCompanyWithoutAme('')
            ->setPracticeBranchAddress('')
            ->setPracticeBranchMunicipalityCode('')
            ->setPracticeBranchPostalCode('')
            ->setPracticeBranchPhone('')
            ->setPracticeBranchFax('')
            ->setPracticeBranchEmail('')
            ->setPracticeDateFrom('')
            ->setPracticeDateTo('')
            ->setLegalRepresentativeAfm('078776545')
            ->setFormFile('')
            ->setForeignFile('')
            ->setMinorFile('');
    }
}
