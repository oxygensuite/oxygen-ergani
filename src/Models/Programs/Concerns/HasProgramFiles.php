<?php

namespace OxygenSuite\OxygenErgani\Models\Programs\Concerns;

/**
 * Signed form (`f_file`) and third-country documents (`f_foreign_file`) attachments,
 * shared by the E3.1 - E3.5 program forms.
 */
trait HasProgramFiles
{
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
