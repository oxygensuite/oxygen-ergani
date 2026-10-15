<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace Tests\Documents\UrgentHiring;

use DateTime;
use OxygenSuite\OxygenErgani\Enums\WeekDays;
use OxygenSuite\OxygenErgani\Enums\WorkerType;
use OxygenSuite\OxygenErgani\Enums\WorkLocation;
use OxygenSuite\OxygenErgani\Http\Documents\UrgentHiring\UrgentHiring;
use OxygenSuite\OxygenErgani\Http\Documents\UrgentHiring\UrgentHiringCorrection;
use OxygenSuite\OxygenErgani\Models\Hiring\SupplementaryInsuranceSelection;
use OxygenSuite\OxygenErgani\Models\UrgentHiring\UrgentHiringDeclaration;
use Tests\TestCase;

class UrgentHiringTest extends TestCase
{
    public function test_urgent_hiring_submit(): void
    {
        $document = new UrgentHiring('test-access-token');
        $mock = $this->mockResponse(200, 'urgent-hiring.json');
        $document->getConfig()->setHandler($mock);
        $response = $document->handle($this->sampleDeclaration());

        $this->assertStringEndsWith('Documents/FastAna', $mock->getLastRequest()->getUri()->getPath());
        $this->assertSame('600', $response[0]->id);
        $this->assertSame('FA-600', $response[0]->protocol);

        $body = json_decode((string) $mock->getLastRequest()->getBody(), true);
        $this->assertCount(1, $body['FastAnaggelies']['FastAnaggelia']);
    }

    public function test_urgent_hiring_correction_submit(): void
    {
        $declaration = $this->sampleDeclaration()
            ->setRelatedProtocol('FA-600')
            ->setRelatedDate(new DateTime('2026-10-15'));

        $document = new UrgentHiringCorrection('test-access-token');
        $mock = $this->mockResponse(200, 'urgent-hiring.json');
        $document->getConfig()->setHandler($mock);
        $document->handle([$declaration]);

        $this->assertStringEndsWith('Documents/FastAnaC', $mock->getLastRequest()->getUri()->getPath());

        $body = json_decode((string) $mock->getLastRequest()->getBody(), true);
        $item = $body['FastAnaggelies']['FastAnaggelia'][0];
        $this->assertSame('FA-600', $item['f_rel_protocol']);
        $this->assertSame('15/10/2026', $item['f_rel_date']);
    }

    /**
     * The payload must match the official ERGANI FastAna sample (testFastAn.json), field for field and in order.
     *
     * Differences from the sample, on purpose:
     * - The sample misspells `f_efarmoste_sillogiki_simbasi(_comments)`; FastAna_v1.xsd and the live
     *   schema use `f_efarmostea_sillogiki_simbasi(_comments)` (the sample XML fails XSD validation).
     * - Minute fields are integers (the sample sends zero-padded strings "030" / "015").
     */
    public function test_matches_ergani_sample(): void
    {
        $expected = [
            'f_aa_pararthmatos' => '0',
            'f_rel_protocol' => '',
            'f_rel_date' => '',
            'f_kad_pararthmatos' => '4673',
            'f_kallikratis_pararthmatos' => '91790101',
            'f_afm' => '151619217',
            'f_til' => '1145',
            'f_proslipsidate' => '06/04/2026',
            'f_proslipsitime' => '09:00',
            'f_apoxwrisitime' => '11:00',
            'f_second_working_date' => '07/04/2026',
            'f_second_working_datetime' => '09:00',
            'second_working_datetime_apoxwrisis' => '12:00',
            'f_eidikothta' => '313200',
            'f_eidikothta_anal' => '',
            'f_proipiresia' => 1,
            'f_apodoxes' => '1.500,00',
            'f_xronos_katavolis_apodoxon' => '1',
            'f_topos_ergasias' => '0',
            'f_topos_ergasias_comment' => '',
            'f_xaraktirismos' => '1',
            'f_efarmostea_sillogiki_simbasi' => '0',
            'f_efarmostea_sillogiki_simbasi_comments' => '',
            'f_kyria_asfalisi' => '001',
            'EpikourikiSelections' => [
                'EpikourikiSelectionsFastAna' => [
                    ['f_kod_epikourikis' => '001'],
                ],
            ],
            'f_week_days' => '5',
            'f_dialeimma_minutes' => 30,
            'f_dialeimma_entos_wrariou' => '1',
            'f_euelikto_wrario_minutes' => 15,
            'f_comments' => 'No additional comments.',
        ];

        $this->assertSame($expected, $this->sampleDeclaration()->toSortedArray());
    }

    public function test_model_accessors(): void
    {
        $declaration = $this->sampleDeclaration()
            ->setWorkLocation(WorkLocation::OTHER)
            ->setWorkLocationComment('Εργοτάξιο')
            ->setWorkerType(WorkerType::WORKER)
            ->setCollectiveAgreementApplies(true)
            ->setCollectiveAgreementComment('ΕΓΣΣΕ')
            ->addSupplementaryInsurance(SupplementaryInsuranceSelection::make()->setSupplementaryInsuranceCode('002'));

        $this->assertSame('0', $declaration->getBranchCode());
        $this->assertSame('4673', $declaration->getBranchActivityCode());
        $this->assertSame('91790101', $declaration->getMunicipalityCode());
        $this->assertSame('151619217', $declaration->getAfm());
        $this->assertSame('1145', $declaration->getPhone());
        $this->assertSame('06/04/2026', $declaration->getHiringDate());
        $this->assertSame('09:00', $declaration->getStartTime());
        $this->assertSame('11:00', $declaration->getEndTime());
        $this->assertSame('07/04/2026', $declaration->getSecondWorkingDate());
        $this->assertSame('09:00', $declaration->getSecondStartTime());
        $this->assertSame('12:00', $declaration->getSecondEndTime());
        $this->assertSame('313200', $declaration->getSpecialtyCode());
        $this->assertSame('', $declaration->getSpecialtyDescription());
        $this->assertSame(1, $declaration->getExperienceYears());
        $this->assertSame(1500.0, $declaration->getGrossSalary());
        $this->assertSame('1', $declaration->getWagePaymentTime());
        $this->assertSame('1', $declaration->getWorkLocation());
        $this->assertSame('Εργοτάξιο', $declaration->getWorkLocationComment());
        $this->assertSame('0', $declaration->getWorkerType());
        $this->assertSame('1', $declaration->getCollectiveAgreementApplies());
        $this->assertSame('ΕΓΣΣΕ', $declaration->getCollectiveAgreementComment());
        $this->assertSame('001', $declaration->getMainInsurance());
        $this->assertCount(2, $declaration->getSupplementaryInsuranceSelections());
        $this->assertSame('002', $declaration->getSupplementaryInsuranceSelections()[1]->getSupplementaryInsuranceCode());
        $this->assertSame('5', $declaration->getWeekDays());
        $this->assertSame(30, $declaration->getBreakMinutes());
        $this->assertSame('1', $declaration->getBreakWithinSchedule());
        $this->assertSame(15, $declaration->getFlexibleArrivalMinutes());
        $this->assertSame('No additional comments.', $declaration->getComments());
    }

    public function test_datetime_support(): void
    {
        $declaration = UrgentHiringDeclaration::make()
            ->setHiringDate(new DateTime('2026-04-06'))
            ->setSecondWorkingDate(new DateTime('2026-04-07'))
            ->setRelatedDate(new DateTime('2026-04-01'));

        $this->assertSame('06/04/2026', $declaration->getHiringDate());
        $this->assertSame('07/04/2026', $declaration->getSecondWorkingDate());
        $this->assertSame('01/04/2026', $declaration->getRelatedDate());
    }

    private function sampleDeclaration(): UrgentHiringDeclaration
    {
        return UrgentHiringDeclaration::make()
            ->setBranchCode('0')
            ->setRelatedProtocol('')
            ->setRelatedDate('')
            ->setBranchActivityCode('4673')
            ->setMunicipalityCode('91790101')
            ->setAfm('151619217')
            ->setPhone('1145')
            ->setHiringDate('06/04/2026')
            ->setStartTime('09:00')
            ->setEndTime('11:00')
            ->setSecondWorkingDay('07/04/2026', '09:00', '12:00')
            ->setSpecialtyCode('313200')
            ->setSpecialtyDescription('')
            ->setExperienceYears(1)
            ->setGrossSalary(1500.00)
            ->setWagePaymentTime('1')
            ->setWorkLocation(WorkLocation::EMPLOYER_BRANCH)
            ->setWorkLocationComment('')
            ->setWorkerType(WorkerType::EMPLOYEE)
            ->setCollectiveAgreementApplies(false)
            ->setCollectiveAgreementComment('')
            ->setMainInsurance('001')
            ->addSupplementaryInsurance('001')
            ->setWeekDays(WeekDays::FIVE_DAY)
            ->setBreakMinutes(30)
            ->setBreakWithinSchedule(true)
            ->setFlexibleArrivalMinutes(15)
            ->setComments('No additional comments.');
    }

    public function test_with_defaults_keeps_supplementary_insurance_as_wrapper(): void
    {
        $array = UrgentHiringDeclaration::make()->withDefaults()->toSortedArray();

        $this->assertSame(['EpikourikiSelectionsFastAna' => []], $array['EpikourikiSelections']);
    }
}
