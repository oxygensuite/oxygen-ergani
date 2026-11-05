<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace Tests\Documents\Apprenticeship;

use DateTime;
use OxygenSuite\OxygenErgani\Enums\MaritalStatus;
use OxygenSuite\OxygenErgani\Enums\Sex;
use OxygenSuite\OxygenErgani\Http\Documents\Apprenticeship\Apprenticeship;
use OxygenSuite\OxygenErgani\Http\Documents\Apprenticeship\ApprenticeshipCorrection;
use OxygenSuite\OxygenErgani\Models\Apprenticeship\ApprenticeshipDeclaration;
use Tests\TestCase;

class ApprenticeshipTest extends TestCase
{
    public function test_apprenticeship_submit(): void
    {
        $document = new Apprenticeship('test-access-token');
        $mock = $this->mockResponse(200, 'apprenticeship.json');
        $document->getConfig()->setHandler($mock);
        $response = $document->handle($this->sampleDeclaration());

        $this->assertStringEndsWith('Documents/WebE34', $mock->getLastRequest()->getUri()->getPath());
        $this->assertSame('740', $response[0]->id);
        $this->assertSame('E34-740', $response[0]->protocol);

        $body = json_decode((string) $mock->getLastRequest()->getBody(), true);
        $this->assertCount(1, $body['AnaggeliesE34']['AnaggeliaE34']);
    }

    public function test_apprenticeship_correction_submit(): void
    {
        $declaration = $this->sampleDeclaration()->setRelatedSubmission('E34-740', new DateTime('2026-11-05'));

        $document = new ApprenticeshipCorrection('test-access-token');
        $mock = $this->mockResponse(200, 'apprenticeship.json');
        $document->getConfig()->setHandler($mock);
        $document->handle([$declaration]);

        $this->assertStringEndsWith('Documents/WebE34C', $mock->getLastRequest()->getUri()->getPath());

        $body = json_decode((string) $mock->getLastRequest()->getBody(), true);
        $item = $body['AnaggeliesE34']['AnaggeliaE34'][0];
        $this->assertSame('E34-740', $item['f_protocol_sx']);
        $this->assertSame('05/11/2026', $item['f_date_ypovolis_sx']);
    }

    /**
     * The payload must match the official ERGANI E3.4 sample (testE34.json), field for field and in order.
     */
    public function test_matches_ergani_sample(): void
    {
        $expected = json_decode($this->readFile('apprenticeship-sample-e34.json'), true);

        $this->assertSame($expected, $this->sampleDeclaration()->toSortedArray());
    }

    public function test_model_accessors(): void
    {
        $declaration = $this->sampleDeclaration()
            ->setGrossSalary(725.5)
            ->setScheduleAttached(true)
            ->setSchoolOther('ΕΠΑΣ ΔΟΚΙΜΗΣ')
            ->setSpecialtyGroupOther('ΤΟΜΕΑΣ ΔΟΚΙΜΗΣ');

        $this->assertSame('001', $declaration->getProgramCode());
        $this->assertSame('', $declaration->getMinorBookNumber());
        $this->assertSame('4002', $declaration->getSchoolCode());
        $this->assertSame('ΕΠΑΣ ΔΟΚΙΜΗΣ', $declaration->getSchoolOther());
        $this->assertSame('4004', $declaration->getSpecialtyGroupCode());
        $this->assertSame('ΤΟΜΕΑΣ ΔΟΚΙΜΗΣ', $declaration->getSpecialtyGroupOther());
        $this->assertSame('14/09/2025', $declaration->getPlacementDate());
        $this->assertSame('09:00', $declaration->getPlacementTime());
        $this->assertSame('40', $declaration->getWeeklyHours());
        $this->assertSame('1', $declaration->getScheduleAttached());
        $this->assertSame('4013', $declaration->getSpecialtyCode());
        $this->assertSame(725.5, $declaration->getGrossSalary());
        $this->assertSame('725,50', $declaration->toSortedArray()['f_apodoxes']);
        $this->assertSame('30/09/2025', $declaration->getEndDate());
        $this->assertSame('ΔΟΚΙΜΗ', $declaration->getContractNumber());
        $this->assertSame('ΠΑΠΑΔΟΠΟΥΛΟΣ', $declaration->getCertifierLastName());
        $this->assertSame('000000001', $declaration->getCertifierAfm());
        $this->assertSame('078776545', $declaration->getLegalRepresentativeAfm());
        $this->assertSame('', $declaration->getMinorFile());
    }

    public function test_large_salary_uses_thousands_separator(): void
    {
        $this->assertSame('1.234,56', $this->sampleDeclaration()->setGrossSalary(1234.56)->toSortedArray()['f_apodoxes']);
    }

    public function test_datetime_support(): void
    {
        $declaration = ApprenticeshipDeclaration::make()
            ->setApprenticeshipPeriod(new DateTime('2025-09-14'), new DateTime('2026-06-30'));

        $this->assertSame('14/09/2025', $declaration->getPlacementDate());
        $this->assertSame('30/06/2026', $declaration->getEndDate());
    }

    private function sampleDeclaration(): ApprenticeshipDeclaration
    {
        return ApprenticeshipDeclaration::make()
            ->setBranchCode('0')
            ->setRelatedProtocol('')
            ->setRelatedDate('')
            ->setDypaServiceCode('101213')
            ->setLaborInspectionCode('24010')
            ->setProgramCode('001')
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
            ->setIdIssueDate('08/09/2022')
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
            ->setUnemploymentCardNumber('345')
            ->setMinorBookNumber('')
            ->setAddress('ΔΟΚΙΜΗ')
            ->setResidenceMunicipalityCode('')
            ->setPostalCode('10434')
            ->setPhone('ΔΟΚΙΜΗ')
            ->setFax('ΔΟΚΙΜΗ')
            ->setEmail('test@example.gr')
            ->setEducationLevel('1')
            ->setSchoolCode('4002')
            ->setSchoolOther('')
            ->setSpecialtyGroupCode('4004')
            ->setSpecialtyGroupOther('')
            ->setPlacementDate('14/09/2025')
            ->setPlacementTime('09:00')
            ->setScheduleDescription('ΔΟΚΙΜΗ')
            ->setWeeklyHours(40)
            ->setBreakDescription('30')
            ->setScheduleAttached(false)
            ->setSpecialtyCode('4013')
            ->setGrossSalary(1000.0)
            ->setEndDate('30/09/2025')
            ->setContractNumber('ΔΟΚΙΜΗ')
            ->setComments('ΔΟΚΙΜΗ')
            ->setLegalRepresentativeAfm('078776545')
            ->setCertifier('ΠΑΠΑΔΟΠΟΥΛΟΣ', 'ΓΕΩΡΓΙΟΣ', 'ΔΟΚΙΜΗ', 'ΔΟΚΙΜΗ', '000000001')
            ->setFormFile('')
            ->setForeignFile('')
            ->setMinorFile('');
    }
}
