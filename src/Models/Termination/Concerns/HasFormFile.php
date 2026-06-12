<?php

namespace OxygenSuite\OxygenErgani\Models\Termination\Concerns;

/**
 * Form file (signed PDF) for termination forms.
 *
 * Used in: E5N, E5AO, E5D, E5E, E5S, E5DS
 * NOT used in: E5O (notification only)
 */
trait HasFormFile
{
    /**
     * Get the signed form file (PDF).
     */
    public function getFormFile(): ?string
    {
        return $this->get('f_file');
    }

    /**
     * Set the signed form file (PDF).
     *
     * @param string $base64 Base64-encoded PDF file content
     */
    public function setFormFile(string $base64): static
    {
        return $this->set('f_file', $base64);
    }
}
