<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace Tests\Documents\Internship;

use OxygenSuite\OxygenErgani\Http\Documents\Internship\Internship;
use OxygenSuite\OxygenErgani\Models\Internship\InternshipDeclaration;
use Tests\TestCase;

class InternshipDeclarationTest extends TestCase
{
    public function test_internship_declaration_submit(): void
    {
        $declaration = $this->createMinimalDeclaration();

        $document = new Internship('test-access-token');
        $document->getConfig()->setHandler($this->mockResponse(200, 'internship-declaration.json'));
        $response = $document->handle($declaration);

        $this->assertIsArray($response);
        $this->assertSame('400', $response[0]->id);
        $this->assertSame('E35-400', $response[0]->protocol);
        $this->assertSame('19/02/2026 12:00', $response[0]->submissionDate->format('d/m/Y H:i'));
    }

    public function test_internship_model_personal_info(): void
    {
        $declaration = InternshipDeclaration::make()
            ->setLastName('ΠΑΠΑΔΟΠΟΥΛΟΣ')
            ->setFirstName('ΙΩΑΝΝΗΣ')
            ->setFatherName('ΓΕΩΡΓΙΟΣ')
            ->setMotherName('ΜΑΡΙΑ')
            ->setBirthPlace('ΑΘΗΝΑ')
            ->setBirthDate('15/01/2000')
            ->setSex('0')
            ->setNationality('048')
            ->setAfm('999999999')
            ->setAmka('15012000123456')
            ->setPhone('2101234567')
            ->setEmail('test@example.com');

        $this->assertSame('ΠΑΠΑΔΟΠΟΥΛΟΣ', $declaration->getLastName());
        $this->assertSame('ΙΩΑΝΝΗΣ', $declaration->getFirstName());
        $this->assertSame('ΓΕΩΡΓΙΟΣ', $declaration->getFatherName());
        $this->assertSame('ΜΑΡΙΑ', $declaration->getMotherName());
        $this->assertSame('ΑΘΗΝΑ', $declaration->getBirthPlace());
        $this->assertSame('15/01/2000', $declaration->getBirthDate());
        $this->assertSame('0', $declaration->getSex());
        $this->assertSame('048', $declaration->getNationality());
        $this->assertSame('999999999', $declaration->getAfm());
        $this->assertSame('15012000123456', $declaration->getAmka());
        $this->assertSame('2101234567', $declaration->getPhone());
        $this->assertSame('test@example.com', $declaration->getEmail());
    }

    public function test_internship_model_education(): void
    {
        $declaration = InternshipDeclaration::make()
            ->setEducationLevel('0')
            ->setInstituteNationality('048')
            ->setInstituteName('ΕΚΠΑ')
            ->setSchool('ΠΛΗΡΟΦΟΡΙΚΗΣ')
            ->setDepartment('ΠΛΗΡΟΦΟΡΙΚΗ');

        $this->assertSame('0', $declaration->getEducationLevel());
        $this->assertSame('048', $declaration->getInstituteNationality());
        $this->assertSame('ΕΚΠΑ', $declaration->getInstituteName());
        $this->assertSame('ΠΛΗΡΟΦΟΡΙΚΗΣ', $declaration->getSchool());
        $this->assertSame('ΠΛΗΡΟΦΟΡΙΚΗ', $declaration->getDepartment());
    }

    public function test_internship_model_placement(): void
    {
        $declaration = InternshipDeclaration::make()
            ->setApprovalNumber('APR-001')
            ->setPlacementDate('01/02/2026')
            ->setPlacementTime('09:00')
            ->setWeeklyHours(30.0)
            ->setTotalHours(960.0)
            ->setSpecialtyCode('411090')
            ->setCompensation(600.00)
            ->setHourlyCompensation(5.00)
            ->setStartDate('01/02/2026')
            ->setEndDate('31/07/2026')
            ->setDypaPlacement(false);

        $this->assertSame('APR-001', $declaration->getApprovalNumber());
        $this->assertSame('01/02/2026', $declaration->getPlacementDate());
        $this->assertSame('09:00', $declaration->getPlacementTime());
        $this->assertSame(30.0, $declaration->getWeeklyHours());
        $this->assertSame(960.0, $declaration->getTotalHours());
        $this->assertSame('411090', $declaration->getSpecialtyCode());
        $this->assertSame(600.0, $declaration->getCompensation());
        $this->assertSame(5.0, $declaration->getHourlyCompensation());
        $this->assertSame('01/02/2026', $declaration->getStartDate());
        $this->assertSame('31/07/2026', $declaration->getEndDate());
        $this->assertSame('0', $declaration->getDypaPlacement());
    }

    public function test_internship_schedule_helpers(): void
    {
        $declaration = InternshipDeclaration::make()
            ->setSchedule(1, '09:00', '15:00')
            ->setSchedule(2, '09:00', '15:00')
            ->setSplitSchedule(1, '17:00', '19:00')
            ->setBreakSchedule(1, '12:00', '12:30');

        $array = $declaration->toArray();

        $this->assertSame('09:00', $array['f_time_from_1']);
        $this->assertSame('15:00', $array['f_time_to_1']);
        $this->assertSame('09:00', $array['f_time_from_2']);
        $this->assertSame('15:00', $array['f_time_to_2']);
        $this->assertSame('17:00', $array['f_second_time_from_1']);
        $this->assertSame('19:00', $array['f_second_time_to_1']);
        $this->assertSame('12:00', $array['f_break_time_from_1']);
        $this->assertSame('12:30', $array['f_break_time_to_1']);
    }

    public function test_internship_certifier_fields(): void
    {
        $declaration = InternshipDeclaration::make()
            ->setCertifierLastName('ΛΟΓΙΣΤΗΣ')
            ->setCertifierFirstName('ΜΑΡΙΑ')
            ->setCertifierCapacity('ΛΟΓΙΣΤΗΣ')
            ->setCertifierAddress('ΣΤΑΔΙΟΥ 10')
            ->setCertifierAfm('888888888');

        $this->assertSame('ΛΟΓΙΣΤΗΣ', $declaration->getCertifierLastName());
        $this->assertSame('ΜΑΡΙΑ', $declaration->getCertifierFirstName());
        $this->assertSame('ΛΟΓΙΣΤΗΣ', $declaration->getCertifierCapacity());
        $this->assertSame('ΣΤΑΔΙΟΥ 10', $declaration->getCertifierAddress());
        $this->assertSame('888888888', $declaration->getCertifierAfm());
    }

    public function test_internship_to_sorted_array_casts(): void
    {
        $declaration = InternshipDeclaration::make()
            ->setBranchCode(0)
            ->setWeeklyHours(30.0)
            ->setTotalHours(960.0)
            ->setCompensation(600.00)
            ->setHourlyCompensation(5.00);

        $array = $declaration->toSortedArray();

        $this->assertSame('30,0', $array['f_week_hours']);
        $this->assertSame('960,0', $array['f_total_hours']);
        $this->assertSame('600,00', $array['f_apodoxes']);
        $this->assertSame('5,00', $array['f_hour_apodoxes']);
    }

    public function test_internship_datetime_support(): void
    {
        $declaration = InternshipDeclaration::make()
            ->setBirthDate(new \DateTime('2000-01-15'))
            ->setPlacementDate(new \DateTime('2026-02-01'))
            ->setStartDate(new \DateTime('2026-02-01'))
            ->setEndDate(new \DateTime('2026-07-31'));

        $this->assertSame('15/01/2000', $declaration->getBirthDate());
        $this->assertSame('01/02/2026', $declaration->getPlacementDate());
        $this->assertSame('01/02/2026', $declaration->getStartDate());
        $this->assertSame('31/07/2026', $declaration->getEndDate());
    }

    private function createMinimalDeclaration(): InternshipDeclaration
    {
        return InternshipDeclaration::make()
            ->setBranchCode(0)
            ->setRelatedProtocol('')
            ->setRelatedDate('')
            ->setLaborInspectionCode('12345')
            ->setDypaServiceCode('123456')
            ->setEmployerOrganization('')
            ->setMainActivityCode('6201')
            ->setBranchActivityCode('6201')
            ->setMunicipalityCode('12345678')
            ->setLastName('ΠΑΠΑΔΟΠΟΥΛΟΣ')
            ->setFirstName('ΙΩΑΝΝΗΣ')
            ->setFatherName('ΓΕΩΡΓΙΟΣ')
            ->setMotherName('ΜΑΡΙΑ')
            ->setBirthDate('15/01/2000')
            ->setSex('0')
            ->setNationality('048')
            ->setIdType('ΔΑΤ')
            ->setIdNumber('ΑΒ123456')
            ->setAfm('999999999')
            ->setAmka('15012000123456')
            ->setEducationLevel('0')
            ->setInstituteNationality('048')
            ->setInstituteName('ΕΚΠΑ')
            ->setSchool('ΠΛΗΡΟΦΟΡΙΚΗΣ')
            ->setDepartment('ΠΛΗΡΟΦΟΡΙΚΗ')
            ->setApprovalNumber('APR-001')
            ->setPlacementDate('01/02/2026')
            ->setPlacementTime('09:00')
            ->setWeeklyHours(30.0)
            ->setSpecialtyCode('411090')
            ->setCompensation(600.00)
            ->setHourlyCompensation(5.00)
            ->setStartDate('01/02/2026')
            ->setEndDate('31/07/2026')
            ->setDypaPlacement(false)
            ->setCertifierLastName('ΛΟΓΙΣΤΗΣ')
            ->setCertifierFirstName('ΜΑΡΙΑ')
            ->setCertifierCapacity('ΛΟΓΙΣΤΗΣ')
            ->setCertifierAddress('ΣΤΑΔΙΟΥ 10')
            ->setCertifierAfm('888888888')
            ->setLegalRepresentativeAfm('777777777');
    }
}
