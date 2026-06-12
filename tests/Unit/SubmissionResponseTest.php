<?php

namespace Tests\Unit;

use DateTimeZone;
use OxygenSuite\OxygenErgani\Responses\SubmissionResponse;
use PHPUnit\Framework\TestCase;

class SubmissionResponseTest extends TestCase
{
    public function test_parses_submission_date_with_athens_timezone(): void
    {
        $response = new SubmissionResponse([
            'id' => '12345',
            'protocol' => 'ΑΚ - ΑΠ47',
            'submitDate' => '12/01/2026 23:17',
        ]);

        $this->assertNotNull($response->submissionDate);
        $this->assertSame('Europe/Athens', $response->submissionDate->getTimezone()->getName());
        $this->assertSame('2026-01-12 23:17:00', $response->submissionDate->format('Y-m-d H:i:s'));
    }

    public function test_converts_to_utc_correctly(): void
    {
        $response = new SubmissionResponse([
            'id' => '12345',
            'protocol' => 'ΑΚ - ΑΠ47',
            'submitDate' => '12/01/2026 23:17',
        ]);

        $this->assertNotNull($response->submissionDate);

        // Europe/Athens is UTC+2 in winter, so 23:17 Athens = 21:17 UTC
        $utcDate = $response->submissionDate->setTimezone(new DateTimeZone('UTC'));
        $this->assertSame('2026-01-12 21:17:00', $utcDate->format('Y-m-d H:i:s'));
    }

    public function test_parses_id_and_protocol(): void
    {
        $response = new SubmissionResponse([
            'id' => '21987',
            'protocol' => 'ΑΚ - ΑΠ47',
            'submitDate' => '12/01/2026 23:17',
        ]);

        $this->assertSame('21987', $response->id);
        $this->assertSame('ΑΚ - ΑΠ47', $response->protocol);
    }

    public function test_handles_missing_date(): void
    {
        $response = new SubmissionResponse([
            'id' => '12345',
            'protocol' => 'ΑΚ - ΑΠ47',
        ]);

        $this->assertNull($response->submissionDate);
    }

    public function test_handles_empty_attributes(): void
    {
        $response = new SubmissionResponse([]);

        $this->assertNull($response->id);
        $this->assertNull($response->protocol);
        $this->assertNull($response->submissionDate);
    }
}
