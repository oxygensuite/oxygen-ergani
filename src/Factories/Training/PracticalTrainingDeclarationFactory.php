<?php

namespace OxygenSuite\OxygenErgani\Factories\Training;

use OxygenSuite\OxygenErgani\Factories\Factory;
use OxygenSuite\OxygenErgani\Factories\Programs\Concerns\HasBeneficiaryDefinition;

/**
 * Factory for creating PracticalTrainingDeclaration instances with fake data.
 *
 * @extends Factory<\OxygenSuite\OxygenErgani\Models\Training\PracticalTrainingDeclaration>
 */
class PracticalTrainingDeclarationFactory extends Factory
{
    use HasBeneficiaryDefinition;

    /**
     * Define the default attribute values for PracticalTrainingDeclaration.
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
            'f_programma_voucher' => '001',
            'f_mer_organization' => 'ΕΠΙΧΕΙΡΗΣΗ ΠΡΑΚΤΙΚΗΣ Α.Ε.',
            'f_mer_stakod1' => '4711',
            'f_mer_stakod2' => '',
            'f_mer_stakod3' => '',
            'f_mer_stakod4' => '',
            'f_mer_stakod5' => '',
            'f_mpa_stakod' => '4711',
            'f_mpa_kallikratis' => (string) $fake->numberBetween(10000000, 99999999),
            'f_mpa_men' => (string) $fake->numberBetween(0, 50),
            'f_mpa_women' => (string) $fake->numberBetween(0, 50),
            'f_mpa_minors' => '0',
            ...$this->beneficiaryDefinition(),
            'f_mea_practical_education' => '',
            'f_mea_voucher' => '1',
            'f_mea_kayas' => '',
            'f_date_proslipsis' => date('d/m/Y', strtotime('+1 week')),
            'f_date_time_proslipsis' => '09:00',
            'f_orario' => 'ΔΕΥΤΕΡΑ - ΠΑΡΑΣΚΕΥΗ 09:00 - 17:00',
            'f_week_hours' => '40',
            'f_orario_dialeima' => '',
            'f_wres_external' => '0',
            'f_replace_programa' => '0',
            'f_eidikothta' => (string) $fake->numberBetween(100000, 999999),
            'f_orismenou_apo' => date('d/m/Y', strtotime('+1 week')),
            'f_orismenou_ews' => date('d/m/Y', strtotime('+3 months')),
            'f_comments' => '',
            'f_theory_eopep_code' => '',
            'f_theory_edu_agency_category' => '',
            'f_theory_mer_afm' => $fake->afm(),
            'f_theory_mpa_aa' => '0',
            'f_theory_mpa_kallikratis' => '',
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
     * Configure the theoretical training provider.
     */
    public function withTheoryProvider(string $afm, string $eopepCode = '', int|string $branch = '0'): static
    {
        return $this->state([
            'f_theory_mer_afm' => $afm,
            'f_theory_eopep_code' => $eopepCode,
            'f_theory_mpa_aa' => (string) $branch,
        ]);
    }
}
