<?php

namespace OxygenSuite\OxygenErgani\Models\Programs\Concerns;

/**
 * Minor worker book attachment (`f_young_file`) used by the E3.2 - E3.4 program forms.
 */
trait HasMinorFile
{
    public function getMinorFile(): ?string
    {
        return $this->get('f_young_file');
    }

    /**
     * Minor worker book (base64-encoded PDF).
     */
    public function setMinorFile(string $value): static
    {
        return $this->set('f_young_file', $value);
    }
}
