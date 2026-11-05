<?php

namespace OxygenSuite\OxygenErgani\Models\Programs\Concerns;

/**
 * Free-text schedule, comments, representative and attachment fields shared by the E3.1 - E3.4 program forms.
 */
trait HasProgramSignOff
{
    use HasProgramAttachments;

    // ==================== Schedule ====================

    public function getScheduleDescription(): ?string
    {
        return $this->get('f_orario');
    }

    /**
     * Working / training days and hours as free text (max 200 characters).
     */
    public function setScheduleDescription(string $value): static
    {
        return $this->set('f_orario', $value);
    }

    public function getBreakDescription(): ?string
    {
        return $this->get('f_orario_dialeima');
    }

    /**
     * Break hours as free text (max 200 characters).
     */
    public function setBreakDescription(string $value): static
    {
        return $this->set('f_orario_dialeima', $value);
    }

    // ==================== Comments ====================

    public function getComments(): ?string
    {
        return $this->get('f_comments');
    }

    public function setComments(string $value): static
    {
        return $this->set('f_comments', $value);
    }
}
