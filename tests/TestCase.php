<?php

namespace Tests;

use DateTime;
use DateTimeInterface;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response as HttpResponse;
use PHPUnit\Framework\TestCase as BaseCaseAlias;

class TestCase extends BaseCaseAlias
{
    protected function readFile(string $filename): string
    {
        return file_get_contents(__DIR__ . '/responses/' . $filename);
    }

    protected function mockResponse(int $status, ?string $filename = null): MockHandler
    {
        return new MockHandler([
            new HttpResponse($status, body: $filename ? $this->readFile($filename) : null),
        ]);
    }

    /** @noinspection PhpUnhandledExceptionInspection */
    protected function assertDates(DateTimeInterface|string $date1, DateTimeInterface|string $date2): void
    {
        $date1 = $date1 instanceof DateTimeInterface ? $date1 : new DateTime($date1);
        $date2 = $date2 instanceof DateTimeInterface ? $date2 : new DateTime($date2);

        $this->assertEquals($date1, $date2);
    }
}
