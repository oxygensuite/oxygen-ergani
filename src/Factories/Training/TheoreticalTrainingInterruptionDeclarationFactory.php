<?php

namespace OxygenSuite\OxygenErgani\Factories\Training;

use OxygenSuite\OxygenErgani\Factories\Factory;

/**
 * Factory for creating TheoreticalTrainingInterruptionDeclaration instances with fake data.
 *
 * @extends Factory<\OxygenSuite\OxygenErgani\Models\Training\TheoreticalTrainingInterruptionDeclaration>
 */
class TheoreticalTrainingInterruptionDeclarationFactory extends Factory
{
    /**
     * Define the default attribute values for TheoreticalTrainingInterruptionDeclaration.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $fake = self::fake();

        return [
            'f_aa_pararthmatos' => '0',
            'f_protocol_sx' => 'ΠΠ' . $fake->numberBetween(1, 9999),
            'f_date_ypovolis_sx' => $fake->greekDate('-6 months', '-1 month'),
            'f_mea_afm' => $fake->afm(),
            'f_diakoph_date' => $fake->greekDate('-1 week', 'now'),
            'f_diakoph_total_hours_theory' => (string) $fake->numberBetween(10, 300),
            'f_diakoph_total_hours_practice' => '',
            'f_diakoph_total_insurance_days' => '',
            'f_diakoph_aitiologia_other' => 'ΑΠΟΧΩΡΗΣΗ ΩΦΕΛΟΥΜΕΝΟΥ',
            'f_mek_afm' => $fake->afm(),
            'f_file' => '',
            'f_foreign_file' => '',
            'f_young_file' => '',
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
     * Configure the original E3.2 declaration being interrupted.
     */
    public function forDeclaration(string $protocol, string $date): static
    {
        return $this->state([
            'f_protocol_sx' => $protocol,
            'f_date_ypovolis_sx' => $date,
        ]);
    }

    /**
     * Configure practical training hours and insurance days completed before the interruption.
     */
    public function withPractice(int $hours = 50, int $insuranceDays = 7): static
    {
        return $this->state([
            'f_diakoph_total_hours_practice' => (string) $hours,
            'f_diakoph_total_insurance_days' => (string) $insuranceDays,
        ]);
    }
}
