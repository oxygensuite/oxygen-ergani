<?php

namespace OxygenSuite\OxygenErgani\Models\Programs\Concerns;

/**
 * Legal representative AFM (`f_mek_afm`) and attachment fields shared by the E3.1 - E3.4 program forms.
 */
trait HasProgramAttachments
{
    use HasProgramFiles;

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
}
