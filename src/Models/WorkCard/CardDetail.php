<?php

namespace OxygenSuite\OxygenErgani\Models\WorkCard;

use DateTime;
use OxygenSuite\OxygenErgani\Enums\CardDetailType;
use OxygenSuite\OxygenErgani\Factories\WorkCard\CardDetailFactory;
use OxygenSuite\OxygenErgani\Models\Concerns\HasFactory;
use OxygenSuite\OxygenErgani\Models\Model;

/**
 * @method static CardDetailFactory factory(int $count = 1)
 */
class CardDetail extends Model
{
    use HasFactory;
    /** @var array<int, string> */
    protected array $expectedOrder = [
        'f_afm',
        'f_eponymo',
        'f_onoma',
        'f_type',
        'f_reference_date',
        'f_date',
        'f_aitiologia',
    ];

    public function getTin(): ?string
    {
        return $this->get('f_afm');
    }

    public function setTin(string $tin): static
    {
        return $this->set('f_afm', $tin);
    }

    public function getLastName(): ?string
    {
        return $this->get('f_eponymo');
    }

    public function setLastName(string $lastName): static
    {
        return $this->set('f_eponymo', $lastName);
    }

    public function getFirstName(): ?string
    {
        return $this->get('f_onoma');
    }

    public function setFirstName(string $firstName): static
    {
        return $this->set('f_onoma', $firstName);
    }

    public function getType(): ?CardDetailType
    {
        $value = $this->get('f_type');
        if (is_string($value)) {
            return CardDetailType::tryFrom($value);
        }

        return $this->get('f_type');
    }

    public function setType(CardDetailType $type): static
    {
        return $this->set('f_type', $type->value);
    }

    public function getReferenceDate(): ?string
    {
        return $this->get('f_reference_date');
    }

    /**
     * @param DateTime|string $referenceDate Format: YYYY-MM-DD
     *
     * @return $this
     */
    public function setReferenceDate(DateTime|string $referenceDate): static
    {
        if ($referenceDate instanceof DateTime) {
            $referenceDate = $referenceDate->format('Y-m-d');
        }

        return $this->set('f_reference_date', $referenceDate);
    }

    public function getDate(): ?string
    {
        return $this->get('f_date');
    }

    /**
     * @param DateTime|string $date Format: YYYY-MM-DD\THH:MM:SS.uP
     *
     * @return $this
     */
    public function setDate(DateTime|string $date): static
    {
        if ($date instanceof DateTime) {
            $date = $date->format('Y-m-d\TH:i:s.uP');
        }

        return $this->set('f_date', $date);
    }

    public function getReasonCode(): ?string
    {
        return $this->get('f_aitiologia');
    }

    public function setReasonCode(?string $reasonCode): static
    {
        return $this->set('f_aitiologia', $reasonCode);
    }
}
