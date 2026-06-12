<?php

namespace OxygenSuite\OxygenErgani\Models\WorkCard;

use OxygenSuite\OxygenErgani\Factories\WorkCard\CardFactory;
use OxygenSuite\OxygenErgani\Models\Concerns\HasFactory;
use OxygenSuite\OxygenErgani\Models\Model;

/**
 * @method static CardFactory factory(int $count = 1)
 */
class Card extends Model
{
    use HasFactory;
    /** @var array<int, string> */
    protected array $expectedOrder = [
        'f_afm_ergodoti',
        'f_aa',
        'f_comments',
        'Details',
    ];

    public function getEmployerTin(): ?string
    {
        return $this->get('f_afm_ergodoti');
    }

    public function setEmployerTin(string $employerTin): static
    {
        return $this->set('f_afm_ergodoti', $employerTin);
    }

    public function getBranchCode(): int|string|null
    {
        return $this->get('f_aa');
    }

    public function setBranchCode(int|string $branchCode): static
    {
        return $this->set('f_aa', $branchCode);
    }

    public function getComments(): ?string
    {
        return $this->get('f_comments');
    }

    public function setComments(?string $comments): static
    {
        return $this->set('f_comments', $comments);
    }

    /**
     * Returns all card details.
     *
     * @return array<int, CardDetail>
     */
    public function getDetails(): array
    {
        return $this->get('Details')['CardDetails'] ?? [];
    }

    /**
     * Returns a card detail at the given index.
     */
    public function getDetail(int $index): ?CardDetail
    {
        return $this->getDetails()[$index] ?? null;
    }

    /**
     * Sets the list of card details.
     *
     * @param array<int, CardDetail> $cardDetails
     */
    public function setDetails(array $cardDetails): static
    {
        return $this->set('Details', ['CardDetails' => $cardDetails]);
    }

    /**
     * Adds one or more card details to the current list.
     *
     * @param CardDetail|array<int, CardDetail> $cardDetail
     */
    public function addDetails(CardDetail|array $cardDetail): static
    {
        $details = $this->getDetails();

        if ($cardDetail instanceof CardDetail) {
            $details[] = $cardDetail;
        } else {
            $details = array_merge($details, $cardDetail);
        }

        return $this->setDetails($details);
    }
}
