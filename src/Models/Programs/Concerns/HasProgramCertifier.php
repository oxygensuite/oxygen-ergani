<?php

namespace OxygenSuite\OxygenErgani\Models\Programs\Concerns;

/**
 * Certifier fields (person certifying the declaration: head of personnel / finance or accountant).
 *
 * Used in: E3.1 (WebE31/WebE31C), E3.1 extension (WebE31E), E3.1 interruption (WebE31D),
 * E3.4 (WebE34/WebE34C)
 */
trait HasProgramCertifier
{
    public function getCertifierLastName(): ?string
    {
        return $this->get('f_eponymo');
    }

    public function setCertifierLastName(string $value): static
    {
        return $this->set('f_eponymo', $value);
    }

    public function getCertifierFirstName(): ?string
    {
        return $this->get('f_onoma');
    }

    public function setCertifierFirstName(string $value): static
    {
        return $this->set('f_onoma', $value);
    }

    public function getCertifierCapacity(): ?string
    {
        return $this->get('f_idiotita');
    }

    public function setCertifierCapacity(string $value): static
    {
        return $this->set('f_idiotita', $value);
    }

    public function getCertifierAddress(): ?string
    {
        return $this->get('f_dieythinsi');
    }

    public function setCertifierAddress(string $value): static
    {
        return $this->set('f_dieythinsi', $value);
    }

    public function getCertifierAfm(): ?string
    {
        return $this->get('f_afm_bebaiwn');
    }

    public function setCertifierAfm(string $value): static
    {
        return $this->set('f_afm_bebaiwn', $value);
    }

    /**
     * Set all certifier details in one call.
     */
    public function setCertifier(string $lastName, string $firstName, string $capacity, string $address, string $afm): static
    {
        return $this->setCertifierLastName($lastName)
            ->setCertifierFirstName($firstName)
            ->setCertifierCapacity($capacity)
            ->setCertifierAddress($address)
            ->setCertifierAfm($afm);
    }
}
