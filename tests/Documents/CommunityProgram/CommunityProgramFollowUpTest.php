<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace Tests\Documents\CommunityProgram;

use DateTime;
use OxygenSuite\OxygenErgani\Http\Documents\CommunityProgram\CommunityProgramExtension;
use OxygenSuite\OxygenErgani\Http\Documents\CommunityProgram\CommunityProgramInterruption;
use OxygenSuite\OxygenErgani\Models\CommunityProgram\CommunityProgramExtensionDeclaration;
use OxygenSuite\OxygenErgani\Models\CommunityProgram\CommunityProgramInterruptionDeclaration;
use Tests\TestCase;

/**
 * E3.1 follow-up forms: extension (WebE31E) and interruption (WebE31D).
 */
class CommunityProgramFollowUpTest extends TestCase
{
    public function test_extension_submit(): void
    {
        $document = new CommunityProgramExtension('test-access-token');
        $mock = $this->mockResponse(200, 'community-program-extension.json');
        $document->getConfig()->setHandler($mock);
        $response = $document->handle($this->extensionSample());

        $this->assertStringEndsWith('Documents/WebE31E', $mock->getLastRequest()->getUri()->getPath());
        $this->assertSame('711', $response[0]->id);
        $this->assertSame('E31E-711', $response[0]->protocol);

        $body = json_decode((string) $mock->getLastRequest()->getBody(), true);
        $this->assertCount(1, $body['AnaggeliesE31E']['AnaggeliaE31E']);
    }

    /**
     * The payload must match the official ERGANI sample (testE31E.json), field for field and in order.
     */
    public function test_extension_matches_ergani_sample(): void
    {
        $expected = json_decode($this->readFile('community-program-sample-e31e.json'), true);

        $this->assertSame($expected, $this->extensionSample()->toSortedArray());
    }

    public function test_extension_accessors(): void
    {
        $declaration = $this->extensionSample()
            ->setRelatedSubmission('ΠΠ60', new DateTime('2024-09-10'))
            ->setExtensionDate(new DateTime('2025-12-31'));

        $this->assertSame('0', $declaration->getBranchCode());
        $this->assertSame('ΠΠ60', $declaration->getRelatedProtocol());
        $this->assertSame('10/09/2024', $declaration->getRelatedDate());
        $this->assertSame('000000000', $declaration->getAfm());
        $this->assertSame('31/12/2025', $declaration->getExtensionDate());
        $this->assertSame('078776545', $declaration->getLegalRepresentativeAfm());
        $this->assertSame('ΠΑΠΑΔΟΠΟΥΛΟΣ', $declaration->getCertifierLastName());
        $this->assertSame('ΓΕΩΡΓΙΟΣ', $declaration->getCertifierFirstName());
        $this->assertSame('ΔΟΚΙΜΗ', $declaration->getCertifierCapacity());
        $this->assertSame('ΔΟΚΙΜΗ', $declaration->getCertifierAddress());
        $this->assertSame('000000001', $declaration->getCertifierAfm());
        $this->assertSame('', $declaration->getFormFile());
        $this->assertSame('', $declaration->getForeignFile());
    }

    public function test_interruption_submit(): void
    {
        $document = new CommunityProgramInterruption('test-access-token');
        $mock = $this->mockResponse(200, 'community-program-interruption.json');
        $document->getConfig()->setHandler($mock);
        $response = $document->handle([$this->interruptionSample(), $this->interruptionSample()]);

        $this->assertStringEndsWith('Documents/WebE31D', $mock->getLastRequest()->getUri()->getPath());
        $this->assertSame('E31D-712', $response[0]->protocol);

        $body = json_decode((string) $mock->getLastRequest()->getBody(), true);
        $this->assertCount(2, $body['AnaggeliesE31D']['AnaggeliaE31D']);
    }

    /**
     * The payload must match the official ERGANI sample (testE31D.json), field for field and in order.
     */
    public function test_interruption_matches_ergani_sample(): void
    {
        $expected = json_decode($this->readFile('community-program-sample-e31d.json'), true);

        $this->assertSame($expected, $this->interruptionSample()->toSortedArray());
    }

    public function test_interruption_accessors(): void
    {
        $declaration = $this->interruptionSample()->setInterruptionDate(new DateTime('2025-09-30'));

        $this->assertSame('ΠΠ59', $declaration->getRelatedProtocol());
        $this->assertSame('09/09/2025', $declaration->getRelatedDate());
        $this->assertSame('000000000', $declaration->getAfm());
        $this->assertSame('30/09/2025', $declaration->getInterruptionDate());
        $this->assertSame('001', $declaration->getInterruptionReason());
        $this->assertSame('000000000', $declaration->getCertifierAfm());
    }

    private function extensionSample(): CommunityProgramExtensionDeclaration
    {
        return CommunityProgramExtensionDeclaration::make()
            ->setBranchCode('0')
            ->setRelatedProtocol('ΠΠ59')
            ->setRelatedDate('09/09/2024')
            ->setAfm('000000000')
            ->setExtensionDate('14/09/2025')
            ->setLegalRepresentativeAfm('078776545')
            ->setCertifier('ΠΑΠΑΔΟΠΟΥΛΟΣ', 'ΓΕΩΡΓΙΟΣ', 'ΔΟΚΙΜΗ', 'ΔΟΚΙΜΗ', '000000001')
            ->setFormFile('')
            ->setForeignFile('');
    }

    private function interruptionSample(): CommunityProgramInterruptionDeclaration
    {
        return CommunityProgramInterruptionDeclaration::make()
            ->setBranchCode('0')
            ->setRelatedSubmission('ΠΠ59', '09/09/2025')
            ->setAfm('000000000')
            ->setInterruptionDate('08/09/2025')
            ->setInterruptionReason('001')
            ->setLegalRepresentativeAfm('078776545')
            ->setCertifierLastName('ΠΑΠΑΔΟΠΟΥΛΟΣ')
            ->setCertifierFirstName('ΓΕΩΡΓΙΟΣ')
            ->setCertifierCapacity('ΔΟΚΙΜΗ')
            ->setCertifierAddress('ΔΟΚΙΜΗ')
            ->setCertifierAfm('000000000')
            ->setFormFile('')
            ->setForeignFile('');
    }
}
