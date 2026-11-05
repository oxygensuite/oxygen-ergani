<?php

namespace OxygenSuite\OxygenErgani\Factories\Apprenticeship;

use OxygenSuite\OxygenErgani\Factories\Factory;
use OxygenSuite\OxygenErgani\Factories\Programs\Concerns\HasBeneficiaryDefinition;

/**
 * Factory for creating ApprenticeshipDeclaration instances with fake data.
 *
 * @extends Factory<\OxygenSuite\OxygenErgani\Models\Apprenticeship\ApprenticeshipDeclaration>
 */
class ApprenticeshipDeclarationFactory extends Factory
{
    use HasBeneficiaryDefinition;

    /**
     * Define the default attribute values for ApprenticeshipDeclaration.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $fake = self::fake();

        return [
            'f_aa_pararthmatos' => '0',
            'f_protocol_sx' => '',
            'f_date_ypovolis_sx' => '',
            'f_ypiresia_oaed' => (string) $fake->numberBetween(100000, 999999),
            'f_ypiresia_sepe' => (string) $fake->numberBetween(10000, 99999),
            'f_programma_novitiate' => '001',
            'f_mer_organization' => 'ΕΠΑΣ ΜΑΘΗΤΕΙΑΣ',
            'f_mer_stakod1' => '4711',
            'f_mer_stakod2' => '',
            'f_mer_stakod3' => '',
            'f_mer_stakod4' => '',
            'f_mer_stakod5' => '',
            'f_mpa_stakod' => '4711',
            'f_mpa_kallikratis' => (string) $fake->numberBetween(10000000, 99999999),
            ...$this->beneficiaryDefinition(),
            'f_mea_date_birth' => $fake->greekDate('-25 years', '-18 years'),
            'f_mea_date_ekdosis' => $fake->greekDate('-5 years', '-1 year'),
            'f_mea_code_anergias' => '',
            'f_mea_epipedo_morfosis' => '1',
            'f_mea_noviciate_sxolh' => (string) $fake->numberBetween(1000, 9999),
            'f_mea_noviciate_sxolh_other' => '',
            'f_mea_noviciate_omada' => (string) $fake->numberBetween(1000, 9999),
            'f_mea_noviciate_omada_other' => '',
            'f_date_proslipsis' => date('d/m/Y', strtotime('+1 week')),
            'f_date_time_proslipsis' => '09:00',
            'f_orario' => 'ΔΕΥΤΕΡΑ - ΠΕΜΠΤΗ 09:00 - 16:00',
            'f_week_hours' => '28',
            'f_orario_dialeima' => '',
            'f_wres_external' => '0',
            'f_novitiate_education' => (string) $fake->numberBetween(1000, 9999),
            'f_apodoxes' => 650.0,
            'f_orismenou_ews' => date('d/m/Y', strtotime('+9 months')),
            'f_contract' => 'ΣΥΜΒ-' . $fake->numberBetween(1000, 9999),
            'f_comments' => '',
            'f_mek_afm' => $fake->afm(),
            'f_eponymo' => $fake->greekLastName(),
            'f_onoma' => $fake->greekFirstName(),
            'f_idiotita' => 'ΛΟΓΙΣΤΗΣ',
            'f_dieythinsi' => 'ΠΑΝΕΠΙΣΤΗΜΙΟΥ 1',
            'f_afm_bebaiwn' => $fake->afm(),
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
     * Configure as a correction to a previous submission.
     */
    public function asCorrection(string $protocol, string $date): static
    {
        return $this->state([
            'f_protocol_sx' => $protocol,
            'f_date_ypovolis_sx' => $date,
        ]);
    }

    /**
     * Configure the apprentice as a minor with a minor worker book.
     */
    public function asMinor(string $bookNumber = 'ΒΑ123456'): static
    {
        return $this->state([
            'f_mea_date_birth' => self::fake()->greekDate('-17 years', '-15 years'),
            'f_mea_ar_vivliou_anilikou' => $bookNumber,
        ]);
    }
}
