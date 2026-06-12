<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace Tests\Documents;

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
}
