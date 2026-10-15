<?php

namespace OxygenSuite\OxygenErgani\Models\Programs\Concerns;

/**
 * Free-text schedule, comments, representative and attachment fields shared by the E3.1 and E3.2 forms.
 */
trait HasProgramSignOff
{
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

    // ==================== Sign-off ====================

    public function getComments(): ?string
    {
        return $this->get('f_comments');
    }

    public function setComments(string $value): static
    {
        return $this->set('f_comments', $value);
    }

    public function getLegalRepresentativeAfm(): ?string
    {
        return $this->get('f_mek_afm');
    }

    /**
     * AFM of the responsible provider / legal representative.
     */
    public function setLegalRepresentativeAfm(string $value): static
    {
        return $this->set('f_mek_afm', $value);
    }

    // ==================== Attachments ====================

    public function getFormFile(): ?string
    {
        return $this->get('f_file');
    }

    /**
     * Signed form (base64-encoded PDF).
     */
    public function setFormFile(string $value): static
    {
        return $this->set('f_file', $value);
    }

    public function getForeignFile(): ?string
    {
        return $this->get('f_foreign_file');
    }

    /**
     * Third-country national labour-market access documents (base64-encoded PDF).
     */
    public function setForeignFile(string $value): static
    {
        return $this->set('f_foreign_file', $value);
    }
}
