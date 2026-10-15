<?php

namespace OxygenSuite\OxygenErgani\Factories\CommunityProgram;

use OxygenSuite\OxygenErgani\Factories\Factory;

/**
 * Factory for creating CommunityProgramDeclaration instances with fake data.
 *
 * Creates a fixed-term, full-time placement by default.
 *
 * @extends Factory<\OxygenSuite\OxygenErgani\Models\CommunityProgram\CommunityProgramDeclaration>
 */
class CommunityProgramDeclarationFactory extends Factory
{
    /**
     * Define the default attribute values for CommunityProgramDeclaration.
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
            'f_programma_benefit' => '363201-24',
            'f_mer_organization' => 'ΔΗΜΟΣ',
            'f_mer_stakod1' => '8411',
            'f_mer_stakod2' => '',
            'f_mer_stakod3' => '',
            'f_mer_stakod4' => '',
            'f_mer_stakod5' => '',
            'f_mpa_stakod' => '8411',
            'f_mpa_kallikratis' => (string) $fake->numberBetween(10000000, 99999999),
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
            'f_mea_dieythinsi' => 'ΣΤΑΔΙΟΥ 10',
            'f_mea_kallikratis' => '',
            'f_mea_tk' => '10564',
            'f_mea_til' => '210' . $fake->numberBetween(1000000, 9999999),
            'f_mea_fax' => '',
            'f_mea_email' => '',
            'f_mea_epipedo_morfosis' => '5',
            'f_mea_professional_education' => '0',
            'f_mea_expertise_field' => '',
            'f_mea_subject_area' => '',
            'f_mea_subject_group' => '',
            'f_mea_education_agency' => '',
            'f_mea_education_date_from' => '',
            'f_mea_education_date_to' => '',
            'f_mea_duration' => '',
            'f_mea_education_year' => '',
            'f_mea_fl1' => '',
            'f_mea_fl2' => '',
            'f_mea_fl3' => '',
            'f_mea_fl4' => '',
            'f_mea_pc' => '0',
            'f_mea_pc_other' => '',
            'f_date_proslipsis' => date('d/m/Y', strtotime('+1 week')),
            'f_date_time_proslipsis' => '08:00',
            'f_orario' => 'ΔΕΥΤΕΡΑ - ΠΑΡΑΣΚΕΥΗ 08:00 - 16:00',
            'f_week_hours' => '40',
            'f_orario_dialeima' => '',
            'f_wres_external' => '0',
            'f_eidikothta_programma' => '101ΥΕ16',
            'f_eidikothta' => (string) $fake->numberBetween(100000, 999999),
            'f_proipiresia' => '0',
            'f_apodoxes' => (float) $fake->numberBetween(830, 1200),
            'f_hour_apodoxes' => (float) $fake->numberBetween(5, 9),
            'f_proti_ergasia' => '0',
            'f_sxesh_apasxolhshs' => '1',
            'f_orismenou_apo' => date('d/m/Y', strtotime('+1 week')),
            'f_orismenou_ews' => date('d/m/Y', strtotime('+8 months')),
            'f_kathestos_apasxolhshs' => '0',
            'f_foreas_topothetisis' => 'ΔΗΜΟΣ ΑΘΗΝΑΙΩΝ',
            'f_topothetisi_epistoli' => '1',
            'f_replace_programa' => '0',
            'f_replace_programa_afm' => '',
            'f_replace_programa_amka' => '',
            'f_epidoma_anergeias' => '0',
            'f_ypiresia_oaed_anergeias' => '',
            'f_comments' => '',
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
     * Configure as an indefinite-term placement (no fixed-term dates).
     */
    public function indefinite(): static
    {
        return $this->state([
            'f_sxesh_apasxolhshs' => '0',
            'f_orismenou_apo' => '',
            'f_orismenou_ews' => '',
        ]);
    }

    /**
     * Configure as part-time employment.
     */
    public function partTime(string $weeklyHours = '20'): static
    {
        return $this->state([
            'f_kathestos_apasxolhshs' => '1',
            'f_week_hours' => $weeklyHours,
        ]);
    }

    /**
     * Configure as a replacement of another beneficiary of the same program.
     */
    public function replacing(string $afm, string $amka): static
    {
        return $this->state([
            'f_replace_programa' => '1',
            'f_replace_programa_afm' => $afm,
            'f_replace_programa_amka' => $amka,
        ]);
    }

    /**
     * Configure as receiving unemployment benefit from a DYPA service.
     */
    public function withUnemploymentBenefit(string $dypaServiceCode = '101213'): static
    {
        return $this->state([
            'f_epidoma_anergeias' => '1',
            'f_ypiresia_oaed_anergeias' => $dypaServiceCode,
        ]);
    }
}
