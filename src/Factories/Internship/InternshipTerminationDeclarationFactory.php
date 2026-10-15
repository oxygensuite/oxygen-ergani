<?php

namespace OxygenSuite\OxygenErgani\Factories\Internship;

use OxygenSuite\OxygenErgani\Factories\Factory;

/**
 * Factory for creating InternshipTerminationDeclaration instances with fake data.
 *
 * @extends Factory<\OxygenSuite\OxygenErgani\Models\Internship\InternshipTerminationDeclaration>
 */
class InternshipTerminationDeclarationFactory extends Factory
{
    /**
     * Define the default attribute values for InternshipTerminationDeclaration.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $fake = self::fake();

        return [
            'f_aa_pararthmatos' => '0',
            'f_rel_protocol' => 'ΠΡ' . $fake->numberBetween(1, 9999),
            'f_rel_date' => $fake->greekDate('-6 months', '-1 month'),
            'f_afm' => $fake->afm(),
            'f_diakoph_date' => $fake->greekDate('-1 week', 'now'),
            'f_diakoph_aitiologia' => '001',
            'f_eponymo' => $fake->greekLastName(),
            'f_onoma' => $fake->greekFirstName(),
            'f_idiotita' => 'ΛΟΓΙΣΤΗΣ',
            'f_dieythinsi' => 'ΑΘΗΝΑ',
            'f_afm_bebaiwn' => $fake->afm(),
            'f_afm_proswpoy' => $fake->afm(),
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
     * Configure the original E3.5 declaration being terminated.
     */
    public function forDeclaration(string $protocol, string $date): static
    {
        return $this->state([
            'f_rel_protocol' => $protocol,
            'f_rel_date' => $date,
        ]);
    }

    /**
     * Configure the termination date and reason code.
     */
    public function terminatedOn(string $date, string $reasonCode = '001'): static
    {
        return $this->state([
            'f_diakoph_date' => $date,
            'f_diakoph_aitiologia' => $reasonCode,
        ]);
    }
}
