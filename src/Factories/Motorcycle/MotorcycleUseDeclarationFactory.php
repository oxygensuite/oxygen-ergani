<?php

namespace OxygenSuite\OxygenErgani\Factories\Motorcycle;

use OxygenSuite\OxygenErgani\Factories\Factory;
use OxygenSuite\OxygenErgani\Models\Model;

/**
 * Factory for creating MotorcycleUseDeclaration instances with fake data.
 *
 * @extends Factory<\OxygenSuite\OxygenErgani\Models\Motorcycle\MotorcycleUseDeclaration>
 */
class MotorcycleUseDeclarationFactory extends Factory
{
    /**
     * Define the default attribute values for MotorcycleUseDeclaration.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $fake = self::fake();

        return [
            'f_aa_pararthmatos' => '0',
            'f_ypiresia_sepe' => (string) $fake->numberBetween(10000, 99999),
            'f_ypiresia_oaed' => (string) $fake->numberBetween(100000, 999999),
            'f_comments' => '',
            'f_afm_proswpoy' => $fake->afm(),
            'Mixanes' => fn() => ['MixanesE4' => [MotorcycleVehicleFactory::new()->make()]],
        ];
    }

    /**
     * Configure for a specific branch.
     */
    public function forBranch(int|string $branchCode): static
    {
        return $this->state([
            'f_aa_pararthmatos' => (string) $branchCode,
        ]);
    }

    /**
     * Configure with the given number of vehicles.
     */
    public function withVehicles(int $count = 1): static
    {
        return $this->state([
            'Mixanes' => fn() => ['MixanesE4' => self::many(MotorcycleVehicleFactory::new()->count($count)->make())],
        ]);
    }

    /**
     * Normalize a factory result to a list of models.
     *
     * @param Model|array<int, Model> $made
     *
     * @return array<int, Model>
     */
    private static function many(Model|array $made): array
    {
        return is_array($made) ? $made : [$made];
    }
}
