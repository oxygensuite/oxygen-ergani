<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace Tests\Documents\Training;

use DateTime;
use OxygenSuite\OxygenErgani\Enums\MaritalStatus;
use OxygenSuite\OxygenErgani\Enums\Sex;
use OxygenSuite\OxygenErgani\Http\Documents\Training\PracticalTraining;
use OxygenSuite\OxygenErgani\Http\Documents\Training\PracticalTrainingCorrection;
use OxygenSuite\OxygenErgani\Models\Training\PracticalTrainingDeclaration;
use Tests\TestCase;

class PracticalTrainingTest extends TestCase
{
    public function test_practical_training_submit(): void
    {
        $document = new PracticalTraining('test-access-token');
        $mock = $this->mockResponse(200, 'practical-training.json');
        $document->getConfig()->setHandler($mock);
        $response = $document->handle($this->sampleDeclaration());

        $this->assertStringEndsWith('Documents/WebE33', $mock->getLastRequest()->getUri()->getPath());
        $this->assertSame('730', $response[0]->id);
        $this->assertSame('E33-730', $response[0]->protocol);

        $body = json_decode((string) $mock->getLastRequest()->getBody(), true);
        $this->assertCount(1, $body['AnaggeliesE33']['AnaggeliaE33']);
    }

    public function test_practical_training_correction_submit(): void
    {
        $declaration = $this->sampleDeclaration()->setRelatedSubmission('E33-730', new DateTime('2026-11-05'));

        $document = new PracticalTrainingCorrection('test-access-token');
        $mock = $this->mockResponse(200, 'practical-training.json');
        $document->getConfig()->setHandler($mock);
        $document->handle([$declaration]);

        $this->assertStringEndsWith('Documents/WebE33C', $mock->getLastRequest()->getUri()->getPath());

        $body = json_decode((string) $mock->getLastRequest()->getBody(), true);
        $item = $body['AnaggeliesE33']['AnaggeliaE33'][0];
        $this->assertSame('E33-730', $item['f_protocol_sx']);
        $this->assertSame('05/11/2026', $item['f_date_ypovolis_sx']);
    }

    /**
     * A response's submission date (DateTimeImmutable) can be passed straight back as the related date.
     */
    public function test_correction_accepts_submission_response_date(): void
    {
        $document = new PracticalTraining('test-access-token');
        $document->getConfig()->setHandler($this->mockResponse(200, 'practical-training.json'));
        $original = $document->handle($this->sampleDeclaration());

        $declaration = $this->sampleDeclaration()
            ->setRelatedSubmission($original[0]->protocol, $original[0]->submissionDate);

        $this->assertSame('E33-730', $declaration->getRelatedProtocol());
        $this->assertSame('05/11/2026', $declaration->getRelatedDate());
    }

    /**
     * The payload must match the official ERGANI E3.3 sample (testE33.json), field for field and in order.
     */
    public function test_matches_ergani_sample(): void
    {
        $expected = json_decode($this->readFile('practical-training-sample-e33.json'), true);

        $this->assertSame($expected, $this->sampleDeclaration()->toSortedArray());
    }

    public function test_model_accessors(): void
    {
        $declaration = $this->sampleDeclaration()
            ->setBranchWorkforce(12, 8, 1)
            ->setScheduleAttached(true)
            ->setReplacement(false);

        $this->assertSame('002', $declaration->getInvitationCode());
        $this->assertSame('12', $declaration->getBranchMenCount());
        $this->assertSame('8', $declaration->getBranchWomenCount());
        $this->assertSame('1', $declaration->getBranchMinorsCount());
        $this->assertSame('005', $declaration->getVocationalTrainingSubject());
        $this->assertSame('ΔΟΚΙΜΗ', $declaration->getKayasCode());
        $this->assertSame('15/09/2024', $declaration->getPlacementDate());
        $this->assertSame('09:00', $declaration->getPlacementTime());
        $this->assertSame('40,0', $declaration->getWeeklyHours());
        $this->assertSame('1', $declaration->getScheduleAttached());
        $this->assertSame('0', $declaration->getReplacement());
        $this->assertSame('212208', $declaration->getSpecialtyCode());
        $this->assertSame('15/09/2024', $declaration->getStartDate());
        $this->assertSame('30/09/2024', $declaration->getEndDate());
        $this->assertSame('', $declaration->getTheoryEopepCode());
        $this->assertSame('', $declaration->getTheoryProviderCategory());
        $this->assertSame('000000001', $declaration->getTheoryProviderAfm());
        $this->assertSame('0', $declaration->getTheoryProviderBranch());
        $this->assertSame('', $declaration->getTheoryProviderMunicipalityCode());
        $this->assertSame('078776545', $declaration->getLegalRepresentativeAfm());
        $this->assertSame('', $declaration->getMinorFile());
    }

    public function test_datetime_support(): void
    {
        $declaration = PracticalTrainingDeclaration::make()
            ->setPlacementDate(new DateTime('2024-09-15'))
            ->setFixedTermPeriod(new DateTime('2024-09-15'), new DateTime('2024-09-30'));

        $this->assertSame('15/09/2024', $declaration->getPlacementDate());
        $this->assertSame('15/09/2024', $declaration->getStartDate());
        $this->assertSame('30/09/2024', $declaration->getEndDate());
    }

    private function sampleDeclaration(): PracticalTrainingDeclaration
    {
        return PracticalTrainingDeclaration::make()
            ->setBranchCode('0')
            ->setRelatedProtocol('')
            ->setRelatedDate('')
            ->setDypaServiceCode('101213')
            ->setLaborInspectionCode('24010')
            ->setInvitationCode('002')
            ->setEmployerOrganization('ΔΟΚΙΜΗ')
            ->setMainActivityCode('7022')
            ->setSecondaryActivityCode1('0112')
            ->setSecondaryActivityCode2('0114')
            ->setSecondaryActivityCode3('0116')
            ->setSecondaryActivityCode4('0119')
            ->setBranchActivityCode('7022')
            ->setMunicipalityCode('91860101')
            ->setBranchWorkforce(0, 0)
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
            ->setNumberOfChildren(0)
            ->setAfm('000000000')
            ->setTaxOffice('1190')
            ->setInsuranceAgency('01')
            ->setAmika('2024')
            ->setAmka('01010101010')
            ->setUnemploymentCardNumber('ΔΟΚΙΜΗ')
            ->setMinorBookNumber('')
            ->setAddress('ΔΟΚΙΜΗ')
            ->setResidenceMunicipalityCode('')
            ->setPostalCode('10434')
            ->setPhone('ΔΟΚΙΜΗ')
            ->setFax('ΔΟΚΙΜΗ')
            ->setEmail('test@example.gr')
            ->setEducationLevel('1')
            ->setVocationalTrainingSubject('005')
            ->setVoucherProgram('')
            ->setKayasCode('ΔΟΚΙΜΗ')
            ->setPlacementDate('15/09/2024')
            ->setPlacementTime('09:00')
            ->setScheduleDescription('ΔΟΚΙΜΗ')
            ->setWeeklyHours('40,0')
            ->setBreakDescription('30')
            ->setScheduleAttached(false)
            ->setReplacement(false)
            ->setSpecialtyCode('212208')
            ->setFixedTermPeriod('15/09/2024', '30/09/2024')
            ->setComments('ΔΟΚΙΜΗ')
            ->setTheoryEopepCode('')
            ->setTheoryProviderCategory('')
            ->setTheoryProviderAfm('000000001')
            ->setTheoryProviderBranch(0)
            ->setTheoryProviderMunicipalityCode('')
            ->setLegalRepresentativeAfm('078776545')
            ->setFormFile('')
            ->setForeignFile('')
            ->setMinorFile('');
    }
}
