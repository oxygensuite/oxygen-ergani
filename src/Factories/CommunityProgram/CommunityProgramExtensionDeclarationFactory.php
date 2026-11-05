<?php

namespace OxygenSuite\OxygenErgani\Factories\CommunityProgram;

use OxygenSuite\OxygenErgani\Factories\Factory;

/**
 * Factory for creating CommunityProgramExtensionDeclaration instances with fake data.
 *
 * @extends Factory<\OxygenSuite\OxygenErgani\Models\CommunityProgram\CommunityProgramExtensionDeclaration>
 */
class CommunityProgramExtensionDeclarationFactory extends Factory
{
    /**
     * Define the default attribute values for CommunityProgramExtensionDeclaration.
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
            'f_enlong_date' => date('d/m/Y', strtotime('+6 months')),
            'f_mek_afm' => $fake->afm(),
            'f_eponymo' => $fake->greekLastName(),
            'f_onoma' => $fake->greekFirstName(),
            'f_idiotita' => 'ΛΟΓΙΣΤΗΣ',
            'f_dieythinsi' => 'ΠΑΝΕΠΙΣΤΗΜΙΟΥ 1',
            'f_afm_bebaiwn' => $fake->afm(),
            'f_file' => '',
            'f_foreign_file' => '',
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
     * Configure the original E3.1 declaration being extended.
     */
    public function forDeclaration(string $protocol, string $date): static
    {
        return $this->state([
            'f_protocol_sx' => $protocol,
            'f_date_ypovolis_sx' => $date,
        ]);
    }

    /**
     * Configure the date until which the employment is extended.
     */
    public function extendedUntil(string $date): static
    {
        return $this->state([
            'f_enlong_date' => $date,
        ]);
    }
}
