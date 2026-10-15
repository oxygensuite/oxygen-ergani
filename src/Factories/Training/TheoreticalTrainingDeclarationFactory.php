<?php

namespace OxygenSuite\OxygenErgani\Factories\Training;

use OxygenSuite\OxygenErgani\Factories\Factory;

/**
 * Factory for creating TheoreticalTrainingDeclaration instances with fake data.
 *
 * Creates a theoretical-only training by default (no practical training business).
 *
 * @extends Factory<\OxygenSuite\OxygenErgani\Models\Training\TheoreticalTrainingDeclaration>
 */
class TheoreticalTrainingDeclarationFactory extends Factory
{
    /**
     * Define the default attribute values for TheoreticalTrainingDeclaration.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $fake = self::fake();
        $male = $fake->boolean();
        $gender = $male ? 'male' : 'female';

        return [
            'f_aa_pararthmatos' => '0',
            'f_protocol_sx' => '',
            'f_date_ypovolis_sx' => '',
            'f_ypiresia_oaed' => (string) $fake->numberBetween(100000, 999999),
            'f_ypiresia_sepe' => (string) $fake->numberBetween(10000, 99999),
            'f_programma_voucher_code' => (string) $fake->numberBetween(1, 99),
            'f_programma_voucher' => '001',
            'f_mer_organization' => 'ΚΕΝΤΡΟ ΔΙΑ ΒΙΟΥ ΜΑΘΗΣΗΣ',
            'f_mer_stakod1' => '8559',
            'f_mer_stakod2' => '',
            'f_mer_stakod3' => '',
            'f_mer_stakod4' => '',
            'f_mer_stakod5' => '',
            'f_mpa_stakod' => '8559',
            'f_mpa_kallikratis' => (string) $fake->numberBetween(10000000, 99999999),
            'f_eopep_code' => (string) $fake->numberBetween(1000000, 9999999),
            'f_edu_agency_category' => '001',
            'f_mea_eponimo' => $fake->greekLastName(),
            'f_mea_onoma' => $fake->greekFirstName($gender),
            'f_mea_eponimo_patera' => '',
            'f_mea_onoma_patera' => $fake->greekFirstName('male'),
            'f_mea_eponimo_miteras' => '',
            'f_mea_onoma_miteras' => $fake->greekFirstName('female'),
            'f_mea_sex' => $male ? '0' : '1',
            'f_mea_birth_city' => 'ΑΘΗΝΑ',
            'f_mea_date_birth' => $fake->greekDate('-60 years', '-20 years'),
            'f_mea_nationality' => '048',
            'f_mea_typos_taytotitas' => 'ΔΑΤ',
            'f_mea_ar_taytotitas' => $fake->greekIdNumber(),
            'f_mea_ekdousa' => 'Α.Τ. ΑΘΗΝΩΝ',
            'f_mea_date_ekdosis' => $fake->greekDate('-10 years', '-1 year'),
            'f_mea_date_ekdosis_lixi' => '',
            'f_mea_res_permit' => '',
            'f_mea_res_permit_type' => '',
            'f_mea_res_permit_ar' => '',
            'f_mea_res_permit_lixi' => '',
            'f_mea_marital_status' => '0',
            'f_mea_ar_teknon' => '0',
            'f_mea_afm' => $fake->afm(),
            'f_mea_doy' => '1101',
            'f_mea_insurance_agency' => '',
            'f_mea_am_ika' => $fake->amika(),
            'f_mea_amka' => $fake->amka(),
            'f_mea_code_anergias' => (string) $fake->numberBetween(1000000, 9999999),
            'f_mea_ar_vivliou_anilikou' => '',
            'f_mea_dieythinsi' => 'ΣΤΑΔΙΟΥ 10',
            'f_mea_kallikratis' => '',
            'f_mea_tk' => '10564',
            'f_mea_til' => '210' . $fake->numberBetween(1000000, 9999999),
            'f_mea_fax' => '',
            'f_mea_email' => '',
            'f_mea_epipedo_morfosis' => '5',
            'f_mea_practical_education' => '',
            'f_mea_voucher' => '1',
            'f_mea_kayas' => '',
            'f_date_proslipsis' => date('d/m/Y', strtotime('+1 week')),
            'f_week_hours' => 20.0,
            'f_orario' => 'ΔΕΥΤΕΡΑ - ΠΑΡΑΣΚΕΥΗ 17:00 - 21:00',
            'f_orario_dialeima' => '',
            'f_total_hours_theory' => '80',
            'f_total_hours_practice' => '',
            'f_total_insurance_days' => '',
            'f_daily_apodoxes' => '',
            'f_theory_education' => '001',
            'f_theory_description' => 'ΨΗΦΙΑΚΕΣ ΔΕΞΙΟΤΗΤΕΣ',
            'f_theory_code' => '',
            'f_orismenou_ews' => date('d/m/Y', strtotime('+2 months')),
            'f_benefiter_status_after' => '001',
            'f_months_unempoyment' => '0',
            'f_benefiter_status_before' => '001',
            'f_comments' => '',
            'f_practice_mer_eponimia' => '',
            'f_practice_mer_afm' => '',
            'f_practice_mer_doy' => '',
            'f_practice_mer_ame' => '',
            'f_practice_mer_no_ame' => '',
            'f_practice_mpa_address' => '',
            'f_practice_mpa_kallikratis' => '',
            'f_practice_mpa_tk' => '',
            'f_practice_mpa_til' => '',
            'f_practice_mpa_fax' => '',
            'f_practice_mpa_email' => '',
            'f_practice_date_from' => '',
            'f_practice_date_to' => '',
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
     * Configure with a practical training period at a business after the theoretical training.
     */
    public function withPractice(int $hours = 200, float $dailyAllowance = 30.0): static
    {
        $fake = self::fake();

        return $this->state([
            'f_total_hours_practice' => (string) $hours,
            'f_total_insurance_days' => (string) intdiv($hours, 8),
            'f_daily_apodoxes' => $dailyAllowance,
            'f_practice_mer_eponimia' => 'ΕΠΙΧΕΙΡΗΣΗ ΠΡΑΚΤΙΚΗΣ Α.Ε.',
            'f_practice_mer_afm' => $fake->afm(),
            'f_practice_mer_doy' => '1101',
            'f_practice_mer_ame' => (string) $fake->numberBetween(1000000, 9999999),
            'f_practice_mer_no_ame' => '0',
            'f_practice_mpa_address' => 'ΕΡΜΟΥ 1',
            'f_practice_mpa_kallikratis' => (string) $fake->numberBetween(10000000, 99999999),
            'f_practice_mpa_tk' => '10563',
            'f_practice_mpa_til' => '210' . $fake->numberBetween(1000000, 9999999),
            'f_practice_mpa_fax' => '',
            'f_practice_mpa_email' => '',
            'f_practice_date_from' => date('d/m/Y', strtotime('+3 months')),
            'f_practice_date_to' => date('d/m/Y', strtotime('+5 months')),
        ]);
    }
}
