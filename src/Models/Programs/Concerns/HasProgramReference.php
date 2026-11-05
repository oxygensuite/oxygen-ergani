<?php

namespace OxygenSuite\OxygenErgani\Models\Programs\Concerns;

use DateTimeInterface;

/**
 * Branch and related-submission fields shared by the E3.1 - E3.4 program forms.
 *
 * `f_protocol_sx` / `f_date_ypovolis_sx` reference the original declaration: they are filled for
 * corrections (E3.1C, E3.2C, E3.3C, E3.4C) and for follow-up forms (E3.1 extension/interruption,
 * E3.2 employer change/interruption), and left empty for a first submission.
 */
trait HasProgramReference
{
    public function getBranchCode(): int|string|null
    {
        return $this->get('f_aa_pararthmatos');
    }

    public function setBranchCode(int|string $branchCode): static
    {
        return $this->set('f_aa_pararthmatos', $branchCode);
    }

    public function getRelatedProtocol(): ?string
    {
        return $this->get('f_protocol_sx');
    }

    /**
     * Protocol of the original declaration (corrections and follow-up forms).
     */
    public function setRelatedProtocol(string $value): static
    {
        return $this->set('f_protocol_sx', $value);
    }

    public function getRelatedDate(): ?string
    {
        return $this->get('f_date_ypovolis_sx');
    }

    /**
     * Submission date of the original declaration (corrections and follow-up forms).
     */
    public function setRelatedDate(DateTimeInterface|string $date): static
    {
        return $this->setDateAttribute('f_date_ypovolis_sx', $date);
    }

    /**
     * Reference the original declaration in one call.
     */
    public function setRelatedSubmission(string $protocol, DateTimeInterface|string $date): static
    {
        return $this->setRelatedProtocol($protocol)->setRelatedDate($date);
    }
}
