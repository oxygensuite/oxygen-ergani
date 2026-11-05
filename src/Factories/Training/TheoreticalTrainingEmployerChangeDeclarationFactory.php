<?php

namespace OxygenSuite\OxygenErgani\Factories\Training;

use OxygenSuite\OxygenErgani\Factories\Factory;

/**
 * Factory for creating TheoreticalTrainingEmployerChangeDeclaration instances with fake data.
 *
 * @extends Factory<\OxygenSuite\OxygenErgani\Models\Training\TheoreticalTrainingEmployerChangeDeclaration>
 */
class TheoreticalTrainingEmployerChangeDeclarationFactory extends Factory
{
    /**
     * Define the default attribute values for TheoreticalTrainingEmployerChangeDeclaration.
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
            'f_cpractice_date' => date('d/m/Y', strtotime('+1 week')),
            'f_cpractice_mer_eponimia' => 'ΝΕΑ ΕΠΙΧΕΙΡΗΣΗ ΠΡΑΚΤΙΚΗΣ Α.Ε.',
            'f_cpractice_mer_afm' => $fake->afm(),
            'f_cpractice_mer_doy' => '1101',
            'f_cpractice_mer_ame' => (string) $fake->numberBetween(1000000, 9999999),
            'f_cpractice_mer_no_ame' => '0',
            'f_cpractice_mpa_address' => 'ΕΡΜΟΥ 1',
            'f_cpractice_mpa_kallikratis' => (string) $fake->numberBetween(10000000, 99999999),
            'f_cpractice_mpa_tk' => '10563',
            'f_cpractice_mpa_til' => '210' . $fake->numberBetween(1000000, 9999999),
            'f_cpractice_mpa_fax' => '',
            'f_cpractice_mpa_email' => '',
            'f_cpractice_date_from' => date('d/m/Y', strtotime('+1 week')),
            'f_cpractice_date_to' => date('d/m/Y', strtotime('+3 months')),
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
     * Configure the original E3.2 declaration whose practical training business changes.
     */
    public function forDeclaration(string $protocol, string $date): static
    {
        return $this->state([
            'f_protocol_sx' => $protocol,
            'f_date_ypovolis_sx' => $date,
        ]);
    }

    /**
     * Configure the new business as one without an employer registry number (AME).
     */
    public function withoutAme(): static
    {
        return $this->state([
            'f_cpractice_mer_ame' => '',
            'f_cpractice_mer_no_ame' => '1',
        ]);
    }
}
