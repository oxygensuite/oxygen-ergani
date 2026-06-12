<?php

namespace OxygenSuite\OxygenErgani\Models\Hiring\Concerns;

/**
 * DYPA/OAED employment program fields shared by E3N, E3M, and E3PD schemas.
 *
 * Provides methods for DYPA placement, program codes, and replacement tracking.
 */
trait HasDypaPrograms
{
    /**
     * Whether placement is through DYPA employment promotion program.
     */
    public function getDypaPlacement(): ?string
    {
        return $this->get('f_topothetisioaed');
    }

    /**
     * @param string|bool $value 0=No, 1=Yes (or boolean)
     */
    public function setDypaPlacement(string|bool $value): static
    {
        if (is_bool($value)) {
            $value = $value ? '1' : '0';
        }

        return $this->set('f_topothetisioaed', $value);
    }

    /**
     * Get the DYPA program code.
     */
    public function getDypaProgram(): ?string
    {
        return $this->get('f_programaoaed');
    }

    /**
     * @param string $program DYPA program code
     */
    public function setDypaProgram(string $program): static
    {
        return $this->set('f_programaoaed', $program);
    }

    /**
     * Whether this placement replaces another beneficiary.
     */
    public function getReplaceProgram(): ?string
    {
        return $this->get('f_replaceprograma');
    }

    /**
     * @param string|bool $value 0=No, 1=Yes (or boolean)
     */
    public function setReplaceProgram(string|bool $value): static
    {
        if (is_bool($value)) {
            $value = $value ? '1' : '0';
        }

        return $this->set('f_replaceprograma', $value);
    }

    /**
     * Get the TIN (AFM) of the employee being replaced.
     */
    public function getReplacedEmployeeAfm(): ?string
    {
        return $this->get('f_replaceprograma_afm');
    }

    /**
     * @param string $afm 9-digit AFM of replaced employee
     */
    public function setReplacedEmployeeAfm(string $afm): static
    {
        return $this->set('f_replaceprograma_afm', $afm);
    }

    /**
     * Get the AMKA of the employee being replaced.
     */
    public function getReplacedEmployeeAmka(): ?string
    {
        return $this->get('f_replaceprograma_amka');
    }

    /**
     * @param string $amka AMKA of replaced employee
     */
    public function setReplacedEmployeeAmka(string $amka): static
    {
        return $this->set('f_replaceprograma_amka', $amka);
    }
}
