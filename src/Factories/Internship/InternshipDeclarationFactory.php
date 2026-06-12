<?php

namespace OxygenSuite\OxygenErgani\Factories\Internship;

use OxygenSuite\OxygenErgani\Factories\Factory;

/**
 * Factory for creating InternshipDeclaration instances with fake data.
 *
 * @extends Factory<\OxygenSuite\OxygenErgani\Models\Internship\InternshipDeclaration>
 */
class InternshipDeclarationFactory extends Factory
{
    /**
     * Define the default attribute values for InternshipDeclaration.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $fake = self::fake();
        $birthDate = $fake->dateTimeBetween('-25 years', '-18 years');
        $hireDate = $fake->greekDate('-3 months', 'now');

        return [
            // Branch/business fields
            'f_aa_pararthmatos' => (string) $fake->numberBetween(0, 99),
            'f_rel_protocol' => '',
            'f_rel_date' => '',
            'f_ypiresia_sepe' => (string) $fake->numberBetween(10000, 99999),
            'f_ypiresia_oaed' => (string) $fake->numberBetween(10000, 99999),
            'f_ergodotikh_organwsh' => '',
            'f_kad_kyria' => (string) $fake->numberBetween(10000, 99999),
            'f_kad_deyt_1' => '',
            'f_kad_deyt_2' => '',
            'f_kad_deyt_3' => '',
            'f_kad_deyt_4' => '',
            'f_kad_pararthmatos' => '',
            'f_kallikratis_pararthmatos' => (string) $fake->numberBetween(10000000, 99999999),

            // Personal info
            'f_eponymo' => $fake->greekLastName(),
            'f_onoma' => $fake->greekFirstName(),
            'f_onoma_patros' => $fake->greekFirstName('male'),
            'f_onoma_mitros' => $fake->greekFirstName('female'),
            'f_topos_gennhshs' => 'ΑΘΗΝΑ',
            'f_birthdate' => $birthDate->format('d/m/Y'),
            'f_sex' => (string) $fake->randomElement([0, 1]),
            'f_yphkoothta' => '000',
            'f_typos_taytothtas' => '1',
            'f_ar_taytothtas' => $fake->greekIdNumber(),
            'f_ekdousa_arxh' => 'Α.Τ. ΑΘΗΝΩΝ',
            'f_date_ekdosis' => $fake->greekDate('-10 years', '-1 year'),
            'f_date_ekdosis_lixi' => '',
            'f_res_permit_int' => '0',
            'f_res_permit_int_type' => '',
            'f_res_permit_int_ar' => '',
            'f_res_permit_int_lixi' => '',
            'f_marital_status' => '0',
            'f_arithmos_teknon' => '0',
            'f_afm' => $fake->afm(),
            'f_doy' => 'Α ΑΘΗΝΩΝ',
            'f_amika' => $fake->amika(),
            'f_amka' => $fake->amka($birthDate),
            'f_ar_vivliou_anilikou' => '',
            'f_til' => '210' . $fake->numberBetween(1000000, 9999999),
            'f_email' => 'test@example.com',

            // Education
            'f_epipedo_morfosis' => (string) $fake->numberBetween(0, 5),
            'f_educational_institute_nationality' => '000',
            'f_educational_institute_name' => 'ΕΚΠΑ',
            'f_sxolh' => 'ΣΧΟΛΗ ΘΕΤΙΚΩΝ ΕΠΙΣΤΗΜΩΝ',
            'f_department' => 'ΤΜΗΜΑ ΠΛΗΡΟΦΟΡΙΚΗΣ',

            // Internship details
            'f_approval_number' => (string) $fake->numberBetween(100000, 999999),
            'f_date_proslipsis' => $hireDate,
            'f_date_time_proslipsis' => '08:00',
            'f_week_hours' => 40.0,
            'f_total_hours' => 960.0,
            'f_eidikothta' => (string) $fake->numberBetween(100000, 999999),
            'f_apodoxes' => (float) $fake->numberBetween(400, 800),
            'f_hour_apodoxes' => 0.0,
            'f_orismenou_apo' => $hireDate,
            'f_orismenou_ews' => $fake->greekDate('+1 month', '+6 months'),
            'f_topothetisioaed' => '0',
            'f_comments' => '',

            // Weekly schedule (Mon-Fri 08:00-16:00)
            'f_time_from_1' => '08:00',
            'f_time_to_1' => '16:00',
            'f_time_from_2' => '08:00',
            'f_time_to_2' => '16:00',
            'f_time_from_3' => '08:00',
            'f_time_to_3' => '16:00',
            'f_time_from_4' => '08:00',
            'f_time_to_4' => '16:00',
            'f_time_from_5' => '08:00',
            'f_time_to_5' => '16:00',
            'f_time_from_6' => '',
            'f_time_to_6' => '',
            'f_time_from_7' => '',
            'f_time_to_7' => '',

            // Split shift (empty by default)
            'f_second_time_from_1' => '',
            'f_second_time_to_1' => '',
            'f_second_time_from_2' => '',
            'f_second_time_to_2' => '',
            'f_second_time_from_3' => '',
            'f_second_time_to_3' => '',
            'f_second_time_from_4' => '',
            'f_second_time_to_4' => '',
            'f_second_time_from_5' => '',
            'f_second_time_to_5' => '',
            'f_second_time_from_6' => '',
            'f_second_time_to_6' => '',
            'f_second_time_from_7' => '',
            'f_second_time_to_7' => '',

            // Breaks (empty by default)
            'f_break_time_from_1' => '',
            'f_break_time_to_1' => '',
            'f_break_time_from_2' => '',
            'f_break_time_to_2' => '',
            'f_break_time_from_3' => '',
            'f_break_time_to_3' => '',
            'f_break_time_from_4' => '',
            'f_break_time_to_4' => '',
            'f_break_time_from_5' => '',
            'f_break_time_to_5' => '',
            'f_break_time_from_6' => '',
            'f_break_time_to_6' => '',
            'f_break_time_from_7' => '',
            'f_break_time_to_7' => '',

            // Certifier
            'f_eponymo_idiotitas' => $fake->greekLastName(),
            'f_onoma_idiotitas' => $fake->greekFirstName(),
            'f_idiotita_idiotitas' => 'ΔΙΕΥΘΥΝΤΗΣ',
            'f_dieythinsi_idiotitas' => 'ΑΘΗΝΑ',
            'f_afm_idiotitas' => $fake->afm(),

            // Legal/files
            'f_afm_proswpoy' => '',
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
     * Configure for the main branch (code 0).
     */
    public function mainBranch(): static
    {
        return $this->forBranch(0);
    }

    /**
     * Configure as male intern.
     */
    public function male(): static
    {
        return $this->state([
            'f_sex' => '0',
            'f_onoma' => self::fake()->greekFirstName('male'),
        ]);
    }

    /**
     * Configure as female intern.
     */
    public function female(): static
    {
        return $this->state([
            'f_sex' => '1',
            'f_onoma' => self::fake()->greekFirstName('female'),
        ]);
    }

    /**
     * Configure as foreign national with residence permit.
     */
    public function foreignNational(string $nationality, string $permitType, string $permitNumber, string $permitExpiry): static
    {
        return $this->state([
            'f_yphkoothta' => $nationality,
            'f_res_permit_int' => '1',
            'f_res_permit_int_type' => $permitType,
            'f_res_permit_int_ar' => $permitNumber,
            'f_res_permit_int_lixi' => $permitExpiry,
        ]);
    }

    /**
     * Configure with OAED/DYPA placement.
     */
    public function withOaedPlacement(): static
    {
        return $this->state([
            'f_topothetisioaed' => '1',
        ]);
    }

    /**
     * Configure as a correction to a previous submission.
     */
    public function asCorrection(string $protocol, string $date): static
    {
        return $this->state([
            'f_rel_protocol' => $protocol,
            'f_rel_date' => $date,
        ]);
    }

    /**
     * Configure with part-time schedule.
     */
    public function partTime(float $weekHours = 20.0): static
    {
        return $this->state([
            'f_week_hours' => $weekHours,
            'f_time_from_1' => '08:00',
            'f_time_to_1' => '12:00',
            'f_time_from_2' => '08:00',
            'f_time_to_2' => '12:00',
            'f_time_from_3' => '08:00',
            'f_time_to_3' => '12:00',
            'f_time_from_4' => '08:00',
            'f_time_to_4' => '12:00',
            'f_time_from_5' => '08:00',
            'f_time_to_5' => '12:00',
        ]);
    }

    /**
     * Configure as minor worker.
     */
    public function asMinor(string $bookNumber = ''): static
    {
        return $this->state([
            'f_ar_vivliou_anilikou' => $bookNumber,
        ]);
    }
}
