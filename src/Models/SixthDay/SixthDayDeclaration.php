<?php

namespace OxygenSuite\OxygenErgani\Models\SixthDay;

use DateTime;
use OxygenSuite\OxygenErgani\Models\Concerns\HasFactory;
use OxygenSuite\OxygenErgani\Models\Model;

/**
 * Sixth day declaration model for SixthDay_v2 schema.
 *
 * Used for declaring employment on the 6th day of the week.
 *
 * @see xsd/SixthDay_v2.xsd
 */
class SixthDayDeclaration extends Model
{
    use HasFactory;
    /** @var array<int, string> */
    protected array $expectedOrder = [
        'f_aa_pararthmatos',
        'f_continuous_operation',
        'f_kad_kyria',
        'f_special_occasion_description',
        'f_date_special_from',
        'f_date_special_to',
        'f_comments',
    ];

    public function getBranchCode(): int|string|null
    {
        return $this->get('f_aa_pararthmatos');
    }

    public function setBranchCode(int|string $branchCode): static
    {
        return $this->set('f_aa_pararthmatos', $branchCode);
    }

    public function getContinuousOperation(): ?string
    {
        return $this->get('f_continuous_operation');
    }

    public function setContinuousOperation(string|bool $value): static
    {
        if (is_bool($value)) {
            $value = $value ? '1' : '0';
        }

        return $this->set('f_continuous_operation', $value);
    }

    public function getMainActivityCode(): ?string
    {
        return $this->get('f_kad_kyria');
    }

    public function setMainActivityCode(string $code): static
    {
        return $this->set('f_kad_kyria', $code);
    }

    public function getSpecialOccasionDescription(): ?string
    {
        return $this->get('f_special_occasion_description');
    }

    public function setSpecialOccasionDescription(string $description): static
    {
        return $this->set('f_special_occasion_description', $description);
    }

    public function getDateFrom(): ?string
    {
        return $this->get('f_date_special_from');
    }

    public function setDateFrom(DateTime|string $date): static
    {
        if ($date instanceof DateTime) {
            $date = $date->format('d/m/Y');
        }

        return $this->set('f_date_special_from', $date);
    }

    public function getDateTo(): ?string
    {
        return $this->get('f_date_special_to');
    }

    public function setDateTo(DateTime|string $date): static
    {
        if ($date instanceof DateTime) {
            $date = $date->format('d/m/Y');
        }

        return $this->set('f_date_special_to', $date);
    }

    public function getComments(): ?string
    {
        return $this->get('f_comments');
    }

    public function setComments(string $comments): static
    {
        return $this->set('f_comments', $comments);
    }
}
