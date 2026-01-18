<?php

namespace OxygenSuite\OxygenErgani\Factories\WorkCard;

use OxygenSuite\OxygenErgani\Enums\CardDetailType;
use OxygenSuite\OxygenErgani\Factories\Factory;

/**
 * Factory for creating CardDetail instances with fake data.
 *
 * @extends Factory<\OxygenSuite\OxygenErgani\Models\WorkCard\CardDetail>
 */
class CardDetailFactory extends Factory
{
    /**
     * Define the default attribute values for CardDetail.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $date = fake()->dateTimeBetween('-1 week', 'now');

        return [
            'f_afm' => fake()->afm(),
            'f_eponymo' => fake()->greekLastName(),
            'f_onoma' => fake()->greekFirstName(),
            'f_type' => fake()->randomElement([CardDetailType::CHECK_IN, CardDetailType::CHECK_OUT])->value,
            'f_reference_date' => $date->format('Y-m-d'),
            'f_date' => $date->format('Y-m-d\TH:i:s.000000+00:00'),
            'f_aitiologia' => null,
        ];
    }

    /**
     * Configure the card detail as a check-in.
     */
    public function checkIn(): static
    {
        return $this->state([
            'f_type' => CardDetailType::CHECK_IN->value,
        ]);
    }

    /**
     * Configure the card detail as a check-out.
     */
    public function checkOut(): static
    {
        return $this->state([
            'f_type' => CardDetailType::CHECK_OUT->value,
        ]);
    }

    /**
     * Configure the card detail with a late submission reason.
     */
    public function withReasonCode(string $reasonCode): static
    {
        return $this->state([
            'f_aitiologia' => $reasonCode,
        ]);
    }

    /**
     * Configure the card detail for today.
     */
    public function today(): static
    {
        $now = new \DateTime();

        return $this->state([
            'f_reference_date' => $now->format('Y-m-d'),
            'f_date' => $now->format('Y-m-d\TH:i:s.000000+00:00'),
        ]);
    }

    /**
     * Configure the card detail for a specific date.
     */
    public function forDate(\DateTimeInterface $date): static
    {
        return $this->state([
            'f_reference_date' => $date->format('Y-m-d'),
            'f_date' => $date->format('Y-m-d\TH:i:s.000000+00:00'),
        ]);
    }
}
