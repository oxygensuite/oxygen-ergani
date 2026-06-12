<?php

namespace OxygenSuite\OxygenErgani\Http\Documents;

use DateTime;
use OxygenSuite\OxygenErgani\Exceptions\ErganiException;
use OxygenSuite\OxygenErgani\Http\Client;

abstract class Documents extends Client
{
    private const URI = 'Documents';

    /**
     * @param array<string, mixed> $body
     *
     * @throws ErganiException
     */
    protected function submit(array $body): static
    {
        return $this->post($this->uri(), $body);
    }

    /**
     * @return array<string, mixed>
     * @throws ErganiException
     */
    public function schema(): array
    {
        return $this->get($this->uri())->json();
    }

    /**
     * Retrieve the PDF of a submitted document.
     *
     * @param string $protocol Protocol number from submission response
     * @param DateTime|int|string $submittedDate Submission date (DateTime, Ymd integer, or Ymd string)
     *
     * @return string Base64-encoded PDF content
     * @throws ErganiException
     */
    public function pdf(string $protocol, DateTime|int|string $submittedDate): string
    {
        return $this->get($this->uri(), [
            'protocol' => $protocol,
            'submittedDate' => $submittedDate instanceof DateTime ? $submittedDate->format('Ymd') : $submittedDate,
        ])->contents();
    }

    protected function uri(): string
    {
        return self::URI . '/' . $this->action();
    }

    abstract protected function action(): string;
}
