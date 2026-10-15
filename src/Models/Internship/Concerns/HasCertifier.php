<?php

namespace OxygenSuite\OxygenErgani\Models\Internship\Concerns;

/**
 * Certifier fields (head of personnel / finance or accountant certifying the declaration).
 *
 * Used in: E3.5 (WebE35/WebE35C), E3.5 schedule change (WebE35T)
 */
trait HasCertifier
{
    public function getCertifierLastName(): ?string
    {
        return $this->get('f_eponymo_idiotitas');
    }

    public function setCertifierLastName(string $lastName): static
    {
        return $this->set('f_eponymo_idiotitas', $lastName);
    }

    public function getCertifierFirstName(): ?string
    {
        return $this->get('f_onoma_idiotitas');
    }

    public function setCertifierFirstName(string $firstName): static
    {
        return $this->set('f_onoma_idiotitas', $firstName);
    }

    public function getCertifierCapacity(): ?string
    {
        return $this->get('f_idiotita_idiotitas');
    }

    public function setCertifierCapacity(string $capacity): static
    {
        return $this->set('f_idiotita_idiotitas', $capacity);
    }

    public function getCertifierAddress(): ?string
    {
        return $this->get('f_dieythinsi_idiotitas');
    }

    public function setCertifierAddress(string $address): static
    {
        return $this->set('f_dieythinsi_idiotitas', $address);
    }

    public function getCertifierAfm(): ?string
    {
        return $this->get('f_afm_idiotitas');
    }

    public function setCertifierAfm(string $afm): static
    {
        return $this->set('f_afm_idiotitas', $afm);
    }
}
