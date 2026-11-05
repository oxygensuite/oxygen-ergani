<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace Tests\Documents;

use DateTimeImmutable;
use OxygenSuite\OxygenErgani\Http\Documents\CancelSubmittedDocument;
use Tests\TestCase;

class CancelSubmissionsTest extends TestCase
{
    public function test_lookup_submissions(): void
    {
        $cancel = new CancelSubmittedDocument('test-access-token');
        $cancel->getConfig()->setHandler($this->mockResponse(200, 'empty.json'));
        $cancel->handle('00009', 'TA123', '19800410');

        $this->assertTrue($cancel->isSuccessful());
    }

    /**
     * The DateTimeImmutable in SubmissionResponse::$submissionDate can be passed straight in.
     */
    public function test_cancel_accepts_immutable_submission_date(): void
    {
        $cancel = new CancelSubmittedDocument('test-access-token');
        $mock = $this->mockResponse(200, 'empty.json');
        $cancel->getConfig()->setHandler($mock);
        $cancel->handle('WebE33', 'E33-730', new DateTimeImmutable('2026-11-05 12:00'));

        $body = json_decode((string) $mock->getLastRequest()->getBody(), true);
        $this->assertSame('20261105', $body['SubmittedDate']);
    }
}
