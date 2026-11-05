<?php

namespace OxygenSuite\OxygenErgani\Models\Programs\Concerns;

/**
 * Minor worker book number (`f_mea_ar_vivliou_anilikou`), used by E3.2, E3.3 and E3.4.
 */
trait HasMinorBookNumber
{
    public function getMinorBookNumber(): ?string
    {
        return $this->get('f_mea_ar_vivliou_anilikou');
    }

    public function setMinorBookNumber(string $value): static
    {
        return $this->set('f_mea_ar_vivliou_anilikou', $value);
    }
}
