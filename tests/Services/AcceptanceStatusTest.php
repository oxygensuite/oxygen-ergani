<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace Tests\Services;

use DateTime;
use OxygenSuite\OxygenErgani\Http\Services\AcceptanceStatus;
use OxygenSuite\OxygenErgani\Responses\AcceptanceStatusResponse;
use Tests\TestCase;

class AcceptanceStatusTest extends TestCase
{
    public function test_acceptance_status(): void
    {
        $service = new AcceptanceStatus('test-access-token');
        $service->getConfig()->setHandler($this->mockResponse(200, 'ex-base-06.json'));

        $response = $service->handle('123456789', '12345', '01/01/2025');

        $this->assertInstanceOf(AcceptanceStatusResponse::class, $response);
        $this->assertTrue($service->isSuccessful());
    }

    public function test_acceptance_status_response(): void
    {
        $service = new AcceptanceStatus('test-access-token');
        $service->getConfig()->setHandler($this->mockResponse(200, 'ex-base-06.json'));

        $response = $service->handle('123456789', '12345', '01/01/2025');

        $this->assertSame(1, $response->mainStatus);
        $this->assertSame(1, $response->answerStatus);
        $this->assertSame(1, $response->answerAccept);
        $this->assertSame('67890', $response->answerProtocol);
        $this->assertNotNull($response->answerDate);
        $this->assertSame('2025-01-15', $response->answerDate->format('Y-m-d'));

        // Helper methods
        $this->assertTrue($response->isSubmitted());
        $this->assertFalse($response->isRevoked());
        $this->assertFalse($response->isAnswerPending());
        $this->assertTrue($response->isAnswerSubmitted());
        $this->assertTrue($response->isAccepted());
        $this->assertFalse($response->isRejected());
        $this->assertFalse($response->isAutoAccepted());
    }

    public function test_acceptance_status_null_response(): void
    {
        $service = new AcceptanceStatus('test-access-token');
        $service->getConfig()->setHandler($this->mockResponse(200, 'ex-base-06-null.json'));

        $response = $service->handle('123456789', '12345', '01/01/2025');

        $this->assertNull($response);
    }

    public function test_acceptance_status_with_datetime(): void
    {
        $service = new AcceptanceStatus('test-access-token');
        $service->getConfig()->setHandler($this->mockResponse(200, 'ex-base-06.json'));

        $response = $service->handle('123456789', '12345', new DateTime('2025-01-01'));

        $this->assertInstanceOf(AcceptanceStatusResponse::class, $response);
        $this->assertSame(1, $response->mainStatus);
    }
}
