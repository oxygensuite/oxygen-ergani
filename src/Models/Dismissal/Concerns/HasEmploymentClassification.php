<?php

namespace OxygenSuite\OxygenErgani\Models\Dismissal\Concerns;

use OxygenSuite\OxygenErgani\Enums\EmploymentStatus;
use OxygenSuite\OxygenErgani\Enums\WorkerType;

/**
 * Employment classification fields for dismissal forms.
 *
 * Used in: E6NXP, E6NMP, E6SXP
 */
trait HasEmploymentClassification
{
    /**
     * Get the employment status/regime.
     */
    public function getEmploymentStatus(): ?string
    {
        return $this->get('f_kathestosapasxolisis');
    }

    /**
     * @param EmploymentStatus|string|int $status 0=Full-time, 1=Part-time, 2=Rotational
     */
    public function setEmploymentStatus(EmploymentStatus|string|int $status): static
    {
        if ($status instanceof EmploymentStatus) {
            $status = $status->value;
        }

        return $this->set('f_kathestosapasxolisis', (string) $status);
    }

    /**
     * Get the worker classification type.
     */
    public function getWorkerType(): ?string
    {
        return $this->get('f_xaraktirismos');
    }

    /**
     * @param WorkerType|string|int $type 0=Blue-collar (worker), 1=White-collar (employee)
     */
    public function setWorkerType(WorkerType|string|int $type): static
    {
        if ($type instanceof WorkerType) {
            $type = $type->value;
        }

        return $this->set('f_xaraktirismos', (string) $type);
    }

    /**
     * Get the employee's specialty/occupation code.
     */
    public function getSpecialtyCode(): ?string
    {
        return $this->get('f_eidikothta');
    }

    /**
     * @param string $code Specialty code (1-6 digits)
     */
    public function setSpecialtyCode(string $code): static
    {
        return $this->set('f_eidikothta', $code);
    }
}
