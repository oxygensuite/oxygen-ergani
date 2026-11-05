<?php

namespace OxygenSuite\OxygenErgani\Models\Training\Concerns;

use DateTime;

/**
 * Practical training business fields: name, tax details, branch contact and practice period.
 *
 * E3.2 (WebE32/WebE32C) uses the `f_practice_*` fields, E3.2 employer change (WebE32T) the
 * `f_cpractice_*` fields; the using model picks its prefix through {@see practiceField()}.
 */
trait HasPracticeBusiness
{
    public function getPracticeCompanyName(): ?string
    {
        return $this->get($this->practiceField('mer_eponimia'));
    }

    public function setPracticeCompanyName(string $value): static
    {
        return $this->set($this->practiceField('mer_eponimia'), $value);
    }

    public function getPracticeCompanyAfm(): ?string
    {
        return $this->get($this->practiceField('mer_afm'));
    }

    public function setPracticeCompanyAfm(string $value): static
    {
        return $this->set($this->practiceField('mer_afm'), $value);
    }

    public function getPracticeCompanyTaxOffice(): ?string
    {
        return $this->get($this->practiceField('mer_doy'));
    }

    public function setPracticeCompanyTaxOffice(string $value): static
    {
        return $this->set($this->practiceField('mer_doy'), $value);
    }

    public function getPracticeCompanyAme(): ?string
    {
        return $this->get($this->practiceField('mer_ame'));
    }

    /**
     * Employer registry number (AME) of the business.
     */
    public function setPracticeCompanyAme(string $value): static
    {
        return $this->set($this->practiceField('mer_ame'), $value);
    }

    public function getPracticeCompanyWithoutAme(): ?string
    {
        return $this->get($this->practiceField('mer_no_ame'));
    }

    /**
     * Whether the business has no AME.
     *
     * @param string|bool $value 0=No, 1=Yes (or boolean)
     */
    public function setPracticeCompanyWithoutAme(string|bool $value): static
    {
        if (is_bool($value)) {
            $value = $value ? '1' : '0';
        }

        return $this->set($this->practiceField('mer_no_ame'), $value);
    }

    public function getPracticeBranchAddress(): ?string
    {
        return $this->get($this->practiceField('mpa_address'));
    }

    public function setPracticeBranchAddress(string $value): static
    {
        return $this->set($this->practiceField('mpa_address'), $value);
    }

    public function getPracticeBranchMunicipalityCode(): ?string
    {
        return $this->get($this->practiceField('mpa_kallikratis'));
    }

    public function setPracticeBranchMunicipalityCode(string $value): static
    {
        return $this->set($this->practiceField('mpa_kallikratis'), $value);
    }

    public function getPracticeBranchPostalCode(): ?string
    {
        return $this->get($this->practiceField('mpa_tk'));
    }

    public function setPracticeBranchPostalCode(string $value): static
    {
        return $this->set($this->practiceField('mpa_tk'), $value);
    }

    public function getPracticeBranchPhone(): ?string
    {
        return $this->get($this->practiceField('mpa_til'));
    }

    public function setPracticeBranchPhone(string $value): static
    {
        return $this->set($this->practiceField('mpa_til'), $value);
    }

    public function getPracticeBranchFax(): ?string
    {
        return $this->get($this->practiceField('mpa_fax'));
    }

    public function setPracticeBranchFax(string $value): static
    {
        return $this->set($this->practiceField('mpa_fax'), $value);
    }

    public function getPracticeBranchEmail(): ?string
    {
        return $this->get($this->practiceField('mpa_email'));
    }

    public function setPracticeBranchEmail(string $value): static
    {
        return $this->set($this->practiceField('mpa_email'), $value);
    }

    public function getPracticeDateFrom(): ?string
    {
        return $this->get($this->practiceField('date_from'));
    }

    public function setPracticeDateFrom(DateTime|string $date): static
    {
        if ($date instanceof DateTime) {
            $date = $date->format('d/m/Y');
        }

        return $this->set($this->practiceField('date_from'), $date);
    }

    public function getPracticeDateTo(): ?string
    {
        return $this->get($this->practiceField('date_to'));
    }

    public function setPracticeDateTo(DateTime|string $date): static
    {
        if ($date instanceof DateTime) {
            $date = $date->format('d/m/Y');
        }

        return $this->set($this->practiceField('date_to'), $date);
    }

    /**
     * Set the practical training period in one call.
     */
    public function setPracticePeriod(DateTime|string $from, DateTime|string $to): static
    {
        return $this->setPracticeDateFrom($from)->setPracticeDateTo($to);
    }

    /**
     * Full API field name for a practice-business field suffix (e.g. `mer_afm`).
     */
    protected function practiceField(string $suffix): string
    {
        return 'f_practice_' . $suffix;
    }
}
