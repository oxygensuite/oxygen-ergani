<?php

namespace OxygenSuite\OxygenErgani\Models\Hiring\Concerns;

use OxygenSuite\OxygenErgani\Enums\BasicsAcceptance;
use OxygenSuite\OxygenErgani\Enums\IndividualContract;

/**
 * Acceptance and contract file fields shared by E3N and E3PD schemas.
 *
 * Provides methods for essential terms acceptance, employment contract status,
 * and related file uploads.
 */
trait HasAcceptanceFiles
{
    /**
     * Get the essential employment terms acceptance method.
     */
    public function getBasicsAcceptance(): ?string
    {
        return $this->get('f_basics_acceptance');
    }

    /**
     * @param BasicsAcceptance|string|bool $value 0=With attached file, 1=Awaiting acceptance via MyErgani (or use BasicsAcceptance enum)
     */
    public function setBasicsAcceptance(BasicsAcceptance|string|bool $value): static
    {
        if ($value instanceof BasicsAcceptance) {
            $value = (string) $value->value;
        } elseif (is_bool($value)) {
            $value = $value ? '1' : '0';
        }

        return $this->set('f_basics_acceptance', $value);
    }

    /**
     * Get the essential terms acceptance file.
     */
    public function getFile(): ?string
    {
        return $this->get('f_file');
    }

    /**
     * @param string $base64 Base64-encoded PDF file content
     */
    public function setFile(string $base64): static
    {
        return $this->set('f_file', $base64);
    }

    /**
     * Get the individual employment contract status.
     */
    public function getIndividualContract(): ?string
    {
        return $this->get('f_atomikh_symbash');
    }

    /**
     * @param IndividualContract|string|int $value 0=No, 1=Acceptance with attached file, 2=Pending (or use IndividualContract enum)
     */
    public function setIndividualContract(IndividualContract|string|int $value): static
    {
        if ($value instanceof IndividualContract) {
            $value = $value->value;
        }

        return $this->set('f_atomikh_symbash', (string) $value);
    }

    /**
     * Get the individual employment contract file.
     */
    public function getContractFile(): ?string
    {
        return $this->get('f_file_symbash');
    }

    /**
     * @param string $base64 Base64-encoded PDF file content
     */
    public function setContractFile(string $base64): static
    {
        return $this->set('f_file_symbash', $base64);
    }
}
