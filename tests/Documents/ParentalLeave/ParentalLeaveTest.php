<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace Tests\Documents\ParentalLeave;

use DateTime;
use OxygenSuite\OxygenErgani\Enums\EmploymentStatus;
use OxygenSuite\OxygenErgani\Enums\EmploymentType;
use OxygenSuite\OxygenErgani\Enums\LeaveGrantMethod;
use OxygenSuite\OxygenErgani\Enums\ParentalLeaveCategory;
use OxygenSuite\OxygenErgani\Enums\WeekDays;
use OxygenSuite\OxygenErgani\Http\Documents\ParentalLeave\ParentalLeave;
use OxygenSuite\OxygenErgani\Http\Documents\ParentalLeave\ParentalLeaveCorrection;
use OxygenSuite\OxygenErgani\Models\ParentalLeave\ParentalLeaveChild;
use OxygenSuite\OxygenErgani\Models\ParentalLeave\ParentalLeaveDate;
use OxygenSuite\OxygenErgani\Models\ParentalLeave\ParentalLeaveDeclaration;
use Tests\TestCase;

class ParentalLeaveTest extends TestCase
{
    public function test_parental_leave_submit(): void
    {
        $document = new ParentalLeave('test-access-token');
        $mock = $this->mockResponse(200, 'parental-leave.json');
        $document->getConfig()->setHandler($mock);
        $response = $document->handle($this->sampleDeclaration());

        $this->assertStringEndsWith('Documents/WebE14', $mock->getLastRequest()->getUri()->getPath());
        $this->assertSame('500', $response[0]->id);
        $this->assertSame('E14-500', $response[0]->protocol);

        $body = json_decode((string) $mock->getLastRequest()->getBody(), true);
        $this->assertCount(1, $body['ParentalLeavesE14']['ParentalLeaveE14']);
    }

    public function test_parental_leave_correction_submit(): void
    {
        $declaration = $this->sampleDeclaration()
            ->setRelatedProtocol('E14-500')
            ->setRelatedDate(new DateTime('2026-10-15'));

        $document = new ParentalLeaveCorrection('test-access-token');
        $mock = $this->mockResponse(200, 'parental-leave.json');
        $document->getConfig()->setHandler($mock);
        $document->handle([$declaration]);

        $this->assertStringEndsWith('Documents/WebE14C', $mock->getLastRequest()->getUri()->getPath());

        $body = json_decode((string) $mock->getLastRequest()->getBody(), true);
        $item = $body['ParentalLeavesE14']['ParentalLeaveE14'][0];
        $this->assertSame('E14-500', $item['f_rel_protocol']);
        $this->assertSame('15/10/2026', $item['f_rel_date']);
    }

    /**
     * The payload must match the official ERGANI E14 sample (testE14.json), field for field and in order.
     */
    public function test_matches_ergani_sample(): void
    {
        $expected = [
            'f_aa_pararthmatos' => '0',
            'f_rel_protocol' => '',
            'f_rel_date' => '',
            'f_ypiresia_sepe' => '11050',
            'f_ypiresia_oaed' => '101213',
            'f_payment_from_forea' => '1',
            'f_category' => '',
            'f_afm_ergazom' => '888888888',
            'f_amka_ergazom' => '01010101010',
            'f_am_ika_ergazom' => '53454486',
            'f_eponimo' => 'ΕΠΩΝΥΜΟ',
            'f_onoma' => 'ΟΝΟΜΑ',
            'f_date_proslipsis' => '12/09/2019',
            'f_better_setting_exists' => '0',
            'f_better_setting_descr' => '',
            'f_kathestos_apasxolisis' => '0',
            'f_sxesh_apasxolisis' => '0',
            'f_week_days' => '6',
            'f_week_hours' => '30,0',
            'f_foreas_kyrias_asfalisis' => 'ΕΝΑΣ ΦΟΡΕΑΣ',
            'f_foreas_epikourikhs_asfalisis' => 'ΑΛΛΟΣ ΦΟΡΕΑΣ',
            'f_one_parent' => '1',
            'f_one_parent_case' => '001',
            'f_tekno_from_polydymh_kyhsh' => '0',
            'f_tropos_xorhghshs_adeias' => '1',
            'f_date_enarxis_adeias' => '23/10/2022',
            'f_date_lhxhs_adeias' => '27/10/2022',
            'f_comments' => 'ΔΣΦΔΣΦ',
            'f_afm_proswpoy' => '078776545',
            'Amkas' => [
                'AmkasE14' => [
                    ['f_amka' => '01010101010', 'f_xorhghsh_adeias' => '1', 'f_polydymh_kyhsh' => '0'],
                ],
            ],
            'Dates' => [
                'DatesE14' => [
                    ['f_date_leave' => '23/10/2022', 'f_hour_from' => '12:00', 'f_hour_to' => '17:00', 'f_orario_from' => '10:00', 'f_orario_to' => '18:00'],
                    ['f_date_leave' => '26/10/2022', 'f_plires_orario' => '1'],
                    ['f_date_leave' => '27/10/2022', 'f_plires_orario' => '1'],
                ],
            ],
        ];

        $this->assertSame($expected, $this->sampleDeclaration()->toSortedArray());
    }

    public function test_model_accessors(): void
    {
        $declaration = $this->sampleDeclaration()
            ->setCategory(ParentalLeaveCategory::PRIVATE_SECTOR)
            ->setBetterArrangement('Επιπλέον ημέρες')
            ->setEmploymentStatus(EmploymentStatus::PARTIAL)
            ->setEmploymentType(EmploymentType::FIXED_TERM)
            ->setGrantMethod(LeaveGrantMethod::CONTINUOUS);

        $this->assertSame('3', $declaration->getCategory());
        $this->assertSame('1', $declaration->getBetterArrangementExists());
        $this->assertSame('Επιπλέον ημέρες', $declaration->getBetterArrangementDescription());
        $this->assertSame('1', $declaration->getEmploymentStatus());
        $this->assertSame('1', $declaration->getEmploymentType());
        $this->assertSame('0', $declaration->getGrantMethod());
        $this->assertSame('6', $declaration->getWeekDays());
        $this->assertSame(30.0, $declaration->getWeeklyHours());
        $this->assertSame('1', $declaration->getPaidByEntity());
        $this->assertSame('1', $declaration->getSingleParent());
        $this->assertSame('001', $declaration->getSingleParentCase());
        $this->assertSame('0', $declaration->getMultipleBirth());
        $this->assertSame('888888888', $declaration->getAfm());
        $this->assertSame('01010101010', $declaration->getAmka());
        $this->assertSame('53454486', $declaration->getAma());
        $this->assertSame('ΕΠΩΝΥΜΟ', $declaration->getLastName());
        $this->assertSame('ΟΝΟΜΑ', $declaration->getFirstName());
        $this->assertSame('12/09/2019', $declaration->getHireDate());
        $this->assertSame('23/10/2022', $declaration->getLeaveStartDate());
        $this->assertSame('27/10/2022', $declaration->getLeaveEndDate());
        $this->assertSame('ΕΝΑΣ ΦΟΡΕΑΣ', $declaration->getMainInsuranceAgency());
        $this->assertSame('ΑΛΛΟΣ ΦΟΡΕΑΣ', $declaration->getSupplementaryInsuranceAgency());
        $this->assertSame('078776545', $declaration->getLegalRepresentativeAfm());
        $this->assertSame('11050', $declaration->getLaborInspectionCode());
        $this->assertSame('101213', $declaration->getDypaServiceCode());
        $this->assertSame('ΔΣΦΔΣΦ', $declaration->getComments());
        $this->assertCount(1, $declaration->getChildren());
        $this->assertCount(3, $declaration->getDates());

        $child = $declaration->getChildren()[0];
        $this->assertSame('01010101010', $child->getAmka());
        $this->assertSame('1', $child->getLeaveGranted());
        $this->assertSame('0', $child->getMultipleBirth());

        $hourly = $declaration->getDates()[0];
        $this->assertSame('23/10/2022', $hourly->getDate());
        $this->assertSame('12:00', $hourly->getHourFrom());
        $this->assertSame('17:00', $hourly->getHourTo());
        $this->assertSame('10:00', $hourly->getScheduleFrom());
        $this->assertSame('18:00', $hourly->getScheduleTo());
        $this->assertNull($hourly->getFullDay());
        $this->assertSame('1', $declaration->getDates()[1]->getFullDay());
    }

    public function test_datetime_support(): void
    {
        $declaration = ParentalLeaveDeclaration::make()
            ->setHireDate(new DateTime('2019-09-12'))
            ->setLeavePeriod(new DateTime('2022-10-23'), new DateTime('2022-10-27'))
            ->addDate(ParentalLeaveDate::make()->setDate(new DateTime('2022-10-26')));

        $this->assertSame('12/09/2019', $declaration->getHireDate());
        $this->assertSame('23/10/2022', $declaration->getLeaveStartDate());
        $this->assertSame('27/10/2022', $declaration->getLeaveEndDate());
        $this->assertSame('26/10/2022', $declaration->getDates()[0]->getDate());
    }

    private function sampleDeclaration(): ParentalLeaveDeclaration
    {
        return ParentalLeaveDeclaration::make()
            ->setBranchCode('0')
            ->setRelatedProtocol('')
            ->setRelatedDate('')
            ->setLaborInspectionCode('11050')
            ->setDypaServiceCode('101213')
            ->setPaidByEntity(true)
            ->setCategory('')
            ->setAfm('888888888')
            ->setAmka('01010101010')
            ->setAma('53454486')
            ->setLastName('ΕΠΩΝΥΜΟ')
            ->setFirstName('ΟΝΟΜΑ')
            ->setHireDate('12/09/2019')
            ->setBetterArrangementExists(false)
            ->setBetterArrangementDescription('')
            ->setEmploymentStatus(EmploymentStatus::FULL)
            ->setEmploymentType(EmploymentType::INDEFINITE)
            ->setWeekDays(WeekDays::SIX_DAY)
            ->setWeeklyHours(30.0)
            ->setMainInsuranceAgency('ΕΝΑΣ ΦΟΡΕΑΣ')
            ->setSupplementaryInsuranceAgency('ΑΛΛΟΣ ΦΟΡΕΑΣ')
            ->setSingleParent(true)
            ->setSingleParentCase('001')
            ->setMultipleBirth(false)
            ->setGrantMethod(LeaveGrantMethod::FLEXIBLE)
            ->setLeavePeriod('23/10/2022', '27/10/2022')
            ->setComments('ΔΣΦΔΣΦ')
            ->setLegalRepresentativeAfm('078776545')
            ->addChild(
                ParentalLeaveChild::make()
                    ->setAmka('01010101010')
                    ->setLeaveGranted(true)
                    ->setMultipleBirth(false),
            )
            ->addDate(ParentalLeaveDate::make()->setDate('23/10/2022')->setHours('12:00', '17:00')->setSchedule('10:00', '18:00'))
            ->addDate(ParentalLeaveDate::make()->setDate('26/10/2022')->setFullDay())
            ->addDate(ParentalLeaveDate::make()->setDate('27/10/2022')->setFullDay());
    }

    public function test_with_defaults_keeps_nested_collections_as_wrappers(): void
    {
        $array = ParentalLeaveDeclaration::make()->withDefaults()->toSortedArray();

        $this->assertSame(['AmkasE14' => []], $array['Amkas']);
        $this->assertSame(['DatesE14' => []], $array['Dates']);
        $this->assertSame('', $array['f_comments']);
    }
}
