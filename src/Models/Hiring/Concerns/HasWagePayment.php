<?php

namespace OxygenSuite\OxygenErgani\Models\Hiring\Concerns;

/**
 * Wage payment fields shared by E3N, E3M, and E3PD schemas.
 *
 * Provides methods for wage payment timing, mandatory training,
 * and collective bargaining agreement handling.
 */
trait HasWagePayment
{
    /**
     * Get the wage payment schedule/timing.
     */
    public function getWagePaymentTime(): ?string
    {
        return $this->get('f_xronos_katavolis_apodoxon');
    }

    /**
     * @param string $time Payment schedule description (max 100 chars)
     */
    public function setWagePaymentTime(string $time): static
    {
        return $this->set('f_xronos_katavolis_apodoxon', $time);
    }

    /**
     * Whether mandatory statutory training is required.
     */
    public function getMandatoryTraining(): ?string
    {
        return $this->get('f_ipoxreotiki_katartisi');
    }

    /**
     * @param string|bool $value 0=No, 1=Yes (or boolean)
     */
    public function setMandatoryTraining(string|bool $value): static
    {
        if (is_bool($value)) {
            $value = $value ? '1' : '0';
        }

        return $this->set('f_ipoxreotiki_katartisi', $value);
    }

    /**
     * Whether a collective bargaining agreement applies.
     */
    public function getCollectiveAgreementApplicable(): ?string
    {
        return $this->get('f_efarmoste_sillogiki_simbasi');
    }

    /**
     * @param string|bool $value 0=No, 1=Yes (or boolean)
     */
    public function setCollectiveAgreementApplicable(string|bool $value): static
    {
        if (is_bool($value)) {
            $value = $value ? '1' : '0';
        }

        return $this->set('f_efarmoste_sillogiki_simbasi', $value);
    }

    /**
     * Get remarks about the applicable collective bargaining agreement.
     */
    public function getCollectiveAgreementComments(): ?string
    {
        return $this->get('f_efarmoste_sillogiki_simbasi_comments');
    }

    /**
     * @param string $comments Agreement details/comments (max 500 chars)
     */
    public function setCollectiveAgreementComments(string $comments): static
    {
        return $this->set('f_efarmoste_sillogiki_simbasi_comments', $comments);
    }
}
