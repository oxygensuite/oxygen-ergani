<?php

namespace OxygenSuite\OxygenErgani\Models\Internship\Concerns;

/**
 * Attached files: signed form, third-country nationals documents, minor's work booklet (base64 PDFs).
 *
 * Used in: E3.5 (WebE35/WebE35C/WebE35D/WebE35T)
 */
trait HasInternshipFiles
{
    public function getFormFile(): ?string
    {
        return $this->get('f_file');
    }

    public function setFormFile(string $base64): static
    {
        return $this->set('f_file', $base64);
    }

    public function getForeignFile(): ?string
    {
        return $this->get('f_foreign_file');
    }

    public function setForeignFile(string $base64): static
    {
        return $this->set('f_foreign_file', $base64);
    }

    public function getMinorFile(): ?string
    {
        return $this->get('f_young_file');
    }

    public function setMinorFile(string $base64): static
    {
        return $this->set('f_young_file', $base64);
    }
}
