<?php

namespace OxygenSuite\OxygenErgani\Responses;

use DateTimeInterface;

class AcceptanceStatusResponse extends Response
{
    public ?int $mainStatus;
    public ?int $answerStatus;
    public ?int $answerAccept;
    public ?string $answerProtocol;
    public ?DateTimeInterface $answerDate;

    protected function processData(): void
    {
        $this->mainStatus = $this->int('MainStatus');
        $this->answerStatus = $this->int('AnswerStatus');
        $this->answerAccept = $this->int('AnswerAccept');
        $this->answerProtocol = $this->string('AnswerProtocol');
        $this->answerDate = $this->date('AnswerDate');
    }

    public function isSubmitted(): bool
    {
        return $this->mainStatus === 1;
    }

    public function isRevoked(): bool
    {
        return $this->mainStatus === 2;
    }

    public function isAnswerPending(): bool
    {
        return $this->answerStatus === 0;
    }

    public function isAnswerSubmitted(): bool
    {
        return $this->answerStatus === 1;
    }

    public function isAccepted(): bool
    {
        return $this->answerAccept === 1;
    }

    public function isRejected(): bool
    {
        return $this->answerAccept === 0;
    }

    public function isAutoAccepted(): bool
    {
        return $this->answerAccept === 2;
    }
}
