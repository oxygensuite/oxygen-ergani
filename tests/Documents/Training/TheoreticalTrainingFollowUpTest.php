<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace Tests\Documents\Training;

use DateTime;
use OxygenSuite\OxygenErgani\Http\Documents\Training\TheoreticalTrainingEmployerChange;
use OxygenSuite\OxygenErgani\Http\Documents\Training\TheoreticalTrainingInterruption;
use OxygenSuite\OxygenErgani\Models\Training\TheoreticalTrainingEmployerChangeDeclaration;
use OxygenSuite\OxygenErgani\Models\Training\TheoreticalTrainingInterruptionDeclaration;
use Tests\TestCase;

/**
 * E3.2 follow-up forms: employer change (WebE32T) and interruption (WebE32D).
 */
class TheoreticalTrainingFollowUpTest extends TestCase
{
    public function test_employer_change_submit(): void
    {
        $document = new TheoreticalTrainingEmployerChange('test-access-token');
        $mock = $this->mockResponse(200, 'theoretical-training-employer-change.json');
        $document->getConfig()->setHandler($mock);
        $response = $document->handle($this->employerChangeSample());

        $this->assertStringEndsWith('Documents/WebE32T', $mock->getLastRequest()->getUri()->getPath());
        $this->assertSame('721', $response[0]->id);
        $this->assertSame('E32T-721', $response[0]->protocol);

        $body = json_decode((string) $mock->getLastRequest()->getBody(), true);
        $this->assertCount(1, $body['AnaggeliesE32T']['AnaggeliaE32T']);
    }

    /**
     * The payload must match the official ERGANI sample (testE32T.json), field for field and in order.
     * This also proves the shared setPractice*() setters write f_cpractice_* here, not E3.2's f_practice_*.
     */
    public function test_employer_change_matches_ergani_sample(): void
    {
        $expected = json_decode($this->readFile('theoretical-training-sample-e32t.json'), true);

        $this->assertSame($expected, $this->employerChangeSample()->toSortedArray());
    }

    public function test_employer_change_accessors(): void
    {
        $declaration = $this->employerChangeSample()
            ->setChangeDate(new DateTime('2022-10-01'))
            ->setPracticePeriod(new DateTime('2022-10-01'), new DateTime('2022-12-31'))
            ->setPracticeCompanyWithoutAme(false);

        $this->assertSame('ΠΠ65', $declaration->getRelatedProtocol());
        $this->assertSame('000000000', $declaration->getAfm());
        $this->assertSame('01/10/2022', $declaration->getChangeDate());
        $this->assertSame('ΔΟΚΙΜΗ', $declaration->getPracticeCompanyName());
        $this->assertSame('000000001', $declaration->getPracticeCompanyAfm());
        $this->assertSame('1190', $declaration->getPracticeCompanyTaxOffice());
        $this->assertSame('', $declaration->getPracticeCompanyAme());
        $this->assertSame('0', $declaration->getPracticeCompanyWithoutAme());
        $this->assertSame('ΠΑΤΗΣΙΩΝ 12', $declaration->getPracticeBranchAddress());
        $this->assertSame('91860101', $declaration->getPracticeBranchMunicipalityCode());
        $this->assertSame('10434', $declaration->getPracticeBranchPostalCode());
        $this->assertSame('test@example.gr', $declaration->getPracticeBranchEmail());
        $this->assertSame('01/10/2022', $declaration->getPracticeDateFrom());
        $this->assertSame('31/12/2022', $declaration->getPracticeDateTo());
        $this->assertSame('078776545', $declaration->getLegalRepresentativeAfm());
        $this->assertSame('', $declaration->getMinorFile());
    }

    public function test_interruption_submit(): void
    {
        $document = new TheoreticalTrainingInterruption('test-access-token');
        $mock = $this->mockResponse(200, 'theoretical-training-interruption.json');
        $document->getConfig()->setHandler($mock);
        $response = $document->handle([$this->interruptionSample()]);

        $this->assertStringEndsWith('Documents/WebE32D', $mock->getLastRequest()->getUri()->getPath());
        $this->assertSame('E32D-722', $response[0]->protocol);

        $body = json_decode((string) $mock->getLastRequest()->getBody(), true);
        $this->assertCount(1, $body['AnaggeliesE32D']['AnaggeliaE32D']);
    }

    /**
     * The payload must match the official ERGANI sample (testE32D.json), field for field and in order.
     */
    public function test_interruption_matches_ergani_sample(): void
    {
        $expected = json_decode($this->readFile('theoretical-training-sample-e32d.json'), true);

        $this->assertSame($expected, $this->interruptionSample()->toSortedArray());
    }

    public function test_interruption_accessors(): void
    {
        $declaration = $this->interruptionSample()->setInterruptionDate(new DateTime('2022-09-30'));

        $this->assertSame('30/09/2022', $declaration->getInterruptionDate());
        $this->assertSame('140', $declaration->getTheoryHours());
        $this->assertSame('50', $declaration->getPracticeHours());
        $this->assertSame('30', $declaration->getInsuranceDays());
        $this->assertSame('ΔΟΚΙΜΗ', $declaration->getInterruptionReason());
        $this->assertSame('000000000', $declaration->getAfm());
    }

    private function employerChangeSample(): TheoreticalTrainingEmployerChangeDeclaration
    {
        return TheoreticalTrainingEmployerChangeDeclaration::make()
            ->setBranchCode('0')
            ->setRelatedSubmission('ΠΠ65', '09/09/2022')
            ->setAfm('000000000')
            ->setChangeDate('08/09/2022')
            ->setPracticeCompanyName('ΔΟΚΙΜΗ')
            ->setPracticeCompanyAfm('000000001')
            ->setPracticeCompanyTaxOffice('1190')
            ->setPracticeCompanyAme('')
            ->setPracticeCompanyWithoutAme(true)
            ->setPracticeBranchAddress('ΠΑΤΗΣΙΩΝ 12')
            ->setPracticeBranchMunicipalityCode('91860101')
            ->setPracticeBranchPostalCode('10434')
            ->setPracticeBranchPhone('ΔΟΚΙΜΗ')
            ->setPracticeBranchFax('ΔΟΚΙΜΗ')
            ->setPracticeBranchEmail('test@example.gr')
            ->setPracticePeriod('08/09/2022', '08/10/2022')
            ->setLegalRepresentativeAfm('078776545')
            ->setFormFile('')
            ->setForeignFile('')
            ->setMinorFile('');
    }

    private function interruptionSample(): TheoreticalTrainingInterruptionDeclaration
    {
        return TheoreticalTrainingInterruptionDeclaration::make()
            ->setBranchCode('0')
            ->setRelatedProtocol('ΠΠ65')
            ->setRelatedDate('09/09/2022')
            ->setAfm('000000000')
            ->setInterruptionDate('08/09/2022')
            ->setTheoryHours(140)
            ->setPracticeHours('50')
            ->setInsuranceDays(30)
            ->setInterruptionReason('ΔΟΚΙΜΗ')
            ->setLegalRepresentativeAfm('078776545')
            ->setFormFile('')
            ->setForeignFile('')
            ->setMinorFile('');
    }
}
