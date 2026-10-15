<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace Tests\Documents\Internship;

use DateTime;
use OxygenSuite\OxygenErgani\Http\Documents\Internship\InternshipTermination;
use OxygenSuite\OxygenErgani\Models\Internship\InternshipTerminationDeclaration;
use Tests\TestCase;

class InternshipTerminationTest extends TestCase
{
    public function test_internship_termination_submit(): void
    {
        $document = new InternshipTermination('test-access-token');
        $mock = $this->mockResponse(200, 'internship-termination.json');
        $document->getConfig()->setHandler($mock);
        $response = $document->handle($this->sampleDeclaration());

        $this->assertStringEndsWith('Documents/WebE35D', $mock->getLastRequest()->getUri()->getPath());
        $this->assertSame('410', $response[0]->id);
        $this->assertSame('E35D-410', $response[0]->protocol);

        $body = json_decode((string) $mock->getLastRequest()->getBody(), true);
        $this->assertCount(1, $body['PraktikesE35D']['PraktikhE35D']);
    }

    /**
     * The payload must match the official ERGANI E3.5 stop sample (testE35D.json), field for field and in order.
     */
    public function test_matches_ergani_sample(): void
    {
        $expected = json_decode($this->readFile('internship-sample-e35d.json'), true);

        $this->assertSame($expected, $this->sampleDeclaration()->toSortedArray());
    }

    public function test_model_accessors(): void
    {
        $declaration = $this->sampleDeclaration();

        $this->assertSame('0', $declaration->getBranchCode());
        $this->assertSame('ΠΡ1', $declaration->getRelatedProtocol());
        $this->assertSame('10/04/2024', $declaration->getRelatedDate());
        $this->assertSame('000000000', $declaration->getAfm());
        $this->assertSame('08/09/2024', $declaration->getTerminationDate());
        $this->assertSame('001', $declaration->getTerminationReason());
        $this->assertSame('ΠΑΠΑΔΟΠΟΥΛΟΣ', $declaration->getCertifierLastName());
        $this->assertSame('ΓΕΩΡΓΙΟΣ', $declaration->getCertifierFirstName());
        $this->assertSame('ΔΟΚΙΜΗ', $declaration->getCertifierCapacity());
        $this->assertSame('ΔΟΚΙΜΗ', $declaration->getCertifierAddress());
        $this->assertSame('000000001', $declaration->getCertifierAfm());
        $this->assertSame('078776545', $declaration->getLegalRepresentativeAfm());
        $this->assertSame('', $declaration->getFormFile());
        $this->assertSame('', $declaration->getForeignFile());
        $this->assertSame('', $declaration->getMinorFile());
    }

    public function test_datetime_support(): void
    {
        $declaration = InternshipTerminationDeclaration::make()
            ->setRelatedDate(new DateTime('2024-04-10'))
            ->setTerminationDate(new DateTime('2024-09-08'));

        $this->assertSame('10/04/2024', $declaration->getRelatedDate());
        $this->assertSame('08/09/2024', $declaration->getTerminationDate());
    }

    private function sampleDeclaration(): InternshipTerminationDeclaration
    {
        return InternshipTerminationDeclaration::make()
            ->setBranchCode('0')
            ->setRelatedProtocol('ΠΡ1')
            ->setRelatedDate('10/04/2024')
            ->setAfm('000000000')
            ->setTerminationDate('08/09/2024')
            ->setTerminationReason('001')
            ->setCertifierLastName('ΠΑΠΑΔΟΠΟΥΛΟΣ')
            ->setCertifierFirstName('ΓΕΩΡΓΙΟΣ')
            ->setCertifierCapacity('ΔΟΚΙΜΗ')
            ->setCertifierAddress('ΔΟΚΙΜΗ')
            ->setCertifierAfm('000000001')
            ->setLegalRepresentativeAfm('078776545')
            ->setFormFile('')
            ->setForeignFile('')
            ->setMinorFile('');
    }
}
