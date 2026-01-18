<?php

namespace OxygenSuite\OxygenErgani\Factories\WorkCard;

use OxygenSuite\OxygenErgani\Factories\Factory;

/**
 * Factory for creating Card instances with fake data.
 *
 * @extends Factory<\OxygenSuite\OxygenErgani\Models\WorkCard\Card>
 */
class CardFactory extends Factory
{
    /**
     * Define the default attribute values for Card.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'f_afm_ergodoti' => fake()->afm(),
            'f_aa' => fake()->numberBetween(0, 99),
            'f_comments' => null,
            'Details' => fn() => ['CardDetails' => [CardDetailFactory::new()->make()]],
        ];
    }

    /**
     * Configure the card with specific comments.
     */
    public function withComments(string $comments): static
    {
        return $this->state([
            'f_comments' => $comments,
        ]);
    }

    /**
     * Configure the card with a specific branch code.
     */
    public function forBranch(int $branchCode): static
    {
        return $this->state([
            'f_aa' => $branchCode,
        ]);
    }

    /**
     * Configure the card for the main branch (code 0).
     */
    public function mainBranch(): static
    {
        return $this->forBranch(0);
    }

    /**
     * Configure the card with multiple card details.
     *
     * @param int $count Number of card details to generate
     */
    public function withDetails(int $count = 1): static
    {
        return $this->state([
            'Details' => fn() => ['CardDetails' => CardDetailFactory::new()->count($count)->make()],
        ]);
    }

    /**
     * Configure the card without any details.
     */
    public function withoutDetails(): static
    {
        return $this->state([
            'Details' => ['CardDetails' => []],
        ]);
    }

    /**
     * Configure the card with specific card details.
     *
     * @param array<int, \OxygenSuite\OxygenErgani\Models\WorkCard\CardDetail> $details
     */
    public function withCardDetails(array $details): static
    {
        return $this->state([
            'Details' => ['CardDetails' => $details],
        ]);
    }
}
