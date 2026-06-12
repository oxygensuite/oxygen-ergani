<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace Tests\Documents;

use OxygenSuite\OxygenErgani\Http\Documents\LookupSubmissions;
use Tests\TestCase;

class LookupSubmissionsTest extends TestCase
{
    public function test_lookup_submissions(): void
    {
        $lookup = new LookupSubmissions('test-access-token');
        $lookup->getConfig()->setHandler($this->mockResponse(200, 'lookup-submissions.json'));
        $response = $lookup->handle();

        $this->assertIsArray($response);
        $this->assertCount(35, $response);

        $firstRow = $response[array_key_first($response)];
        $this->assertSame(64, $firstRow['id']);
        $this->assertSame('E12', $firstRow['code']);
        $this->assertSame('ΑΝΑΓΓΕΛΙΑ ΤΟΥ ΑΠΑΣΧΟΛΟΥΜΕΝΟΥ ΠΡΟΣΩΠΙΚΟΥ ΕΠΙ ΕΚΤΕΛΕΣΗΣ ΟΙΚΟΔΟΜΙΚΗΣ ΕΡΓΑΣΙΑΣ Η ΤΕΧΝΙΚΟΥ ΕΡΓΟΥ', $firstRow['description']);

        $lastRow = $response[array_key_last($response)];
        $this->assertSame(129, $lastRow['id']);
        $this->assertSame('WebMAD', $lastRow['code']);
        $this->assertSame('ΨΗΦΙΑΚΗ ΔΗΛΩΣΗ ΜΕΤΑΒΟΛΗΣ ΣΤΟΙΧΕΙΩΝ ΕΡΓΑΣΙΑΚΗΣ ΣΧΕΣΗΣ - Δανειζόμενου Προσωπικού', $lastRow['description']);
    }
}
