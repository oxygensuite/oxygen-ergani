<?php

namespace OxygenSuite\OxygenErgani\Models\Internship\Concerns;

/**
 * Legal representative AFM (f_afm_proswpoy).
 *
 * Used in: E3.5 (WebE35/WebE35C/WebE35D/WebE35T)
 */
trait HasLegalRepresentative
{
    public function getLegalRepresentativeAfm(): ?string
    {
        return $this->get('f_afm_proswpoy');
    }

    public function setLegalRepresentativeAfm(string $afm): static
    {
        return $this->set('f_afm_proswpoy', $afm);
    }
}
