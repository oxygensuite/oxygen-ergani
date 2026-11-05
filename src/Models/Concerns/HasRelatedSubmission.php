<?php

namespace OxygenSuite\OxygenErgani\Models\Concerns;

use DateTimeInterface;

/**
 * Reference to a previous submission (`f_rel_protocol` / `f_rel_date`), used by corrections
 * and follow-up declarations of the hiring, termination, dismissal, modification, work time,
 * overtime, construction, internship, parental leave and urgent hiring forms.
 */
trait HasRelatedSubmission
{
    public function getRelatedProtocol(): ?string
    {
        return $this->get('f_rel_protocol');
    }

    /**
     * Protocol number of the related (original) submission.
     */
    public function setRelatedProtocol(string $protocol): static
    {
        return $this->set('f_rel_protocol', $protocol);
    }

    public function getRelatedDate(): ?string
    {
        return $this->get('f_rel_date');
    }

    /**
     * Submission date of the related (original) submission, e.g. a response's `submissionDate`.
     */
    public function setRelatedDate(DateTimeInterface|string $date): static
    {
        return $this->setDateAttribute('f_rel_date', $date);
    }

    /**
     * Reference the related (original) submission in one call.
     */
    public function setRelatedSubmission(string $protocol, DateTimeInterface|string $date): static
    {
        return $this->setRelatedProtocol($protocol)->setRelatedDate($date);
    }
}
