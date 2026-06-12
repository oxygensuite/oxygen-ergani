<?php

namespace OxygenSuite\OxygenErgani\Models\PreAnnouncement;

use OxygenSuite\OxygenErgani\Models\Concerns\HasFactory;
use OxygenSuite\OxygenErgani\Models\Model;

/**
 * Pre-announcement exemption declaration model for ExProan schema.
 *
 * Used for declaring exemption from the pre-announcement requirement.
 *
 * @see xsd/ExProan.xsd
 */
class ExemptionDeclaration extends Model
{
    use HasFactory;
    /** @var array<int, string> */
    protected array $expectedOrder = [
        'f_aa_pararthmatos',
        'f_is_excluded',
        'f_month',
        'f_year',
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

    public function getIsExcluded(): ?string
    {
        return $this->get('f_is_excluded');
    }

    public function setIsExcluded(string|bool $value): static
    {
        if (is_bool($value)) {
            $value = $value ? '1' : '0';
        }

        return $this->set('f_is_excluded', $value);
    }

    public function getMonth(): ?string
    {
        return $this->get('f_month');
    }

    public function setMonth(string|int $month): static
    {
        return $this->set('f_month', str_pad((string) $month, 2, '0', STR_PAD_LEFT));
    }

    public function getYear(): ?string
    {
        return $this->get('f_year');
    }

    public function setYear(string|int $year): static
    {
        return $this->set('f_year', (string) $year);
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
