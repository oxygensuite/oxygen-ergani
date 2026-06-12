<?php

namespace OxygenSuite\OxygenErgani\Factories\Hiring;

use DateTimeImmutable;
use OxygenSuite\OxygenErgani\Enums\EmploymentStatus;
use OxygenSuite\OxygenErgani\Enums\EmploymentType;
use OxygenSuite\OxygenErgani\Enums\MaritalStatus;
use OxygenSuite\OxygenErgani\Enums\ResponsiblePosition;
use OxygenSuite\OxygenErgani\Enums\Sex;
use OxygenSuite\OxygenErgani\Enums\WeekDays;
use OxygenSuite\OxygenErgani\Enums\WorkerType;
use OxygenSuite\OxygenErgani\Enums\WorkLocation;
use OxygenSuite\OxygenErgani\Factories\Factory;
use OxygenSuite\OxygenErgani\Models\Hiring\ModificationDeclaration;

/**
 * Factory for generating fake ModificationDeclaration (E3M) models.
 *
 * Note: This is for Hiring\ModificationDeclaration (E3M - employee transfer),
 * not Modification\ModificationDeclaration (WebMA - employment modification).
 *
 * @extends Factory<ModificationDeclaration>
 */
class ModificationDeclarationFactory extends Factory
{
    /**
     * Define the default attribute values for the ModificationDeclaration model.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $fake = self::fake();
        $gender = $fake->randomElement(['male', 'female']);
        $birthDate = $fake->dateTimeBetween('-55 years', '-20 years');
        $hiringDate = new DateTimeImmutable('today');
        $startTime = $fake->time24h();
        $transferDate = new DateTimeImmutable('-1 month');

        return [
            // Branch/Location
            'f_aa_pararthmatos' => (string) $fake->numberBetween(0, 99),
            'f_rel_protocol' => '',
            'f_rel_date' => '',
            'f_ypiresia_sepe' => str_pad((string) $fake->numberBetween(1, 99999), 5, '0', STR_PAD_LEFT),
            'f_ypiresia_oaed' => str_pad((string) $fake->numberBetween(1, 999999), 6, '0', STR_PAD_LEFT),
            'f_kad_pararthmatos' => str_pad((string) $fake->numberBetween(1, 9999), 4, '0', STR_PAD_LEFT),
            'f_kallikratis_pararthmatos' => str_pad((string) $fake->numberBetween(1, 99999999), 8, '0', STR_PAD_LEFT),

            // Personal Information
            'f_eponymo' => $fake->greekLastName(),
            'f_onoma' => $fake->greekFirstName($gender),
            'f_onoma_patros' => $fake->greekFirstName('male'),
            'f_onoma_mitros' => $fake->greekFirstName('female'),
            'f_birthdate' => $birthDate->format('d/m/Y'),
            'f_sex' => (string) ($gender === 'male' ? Sex::MALE->value : Sex::FEMALE->value),

            // Identity/Nationality (Greek national by default)
            'f_yphkoothta' => '001',
            'f_typos_taytothtas' => 'ΑΤ',
            'f_ar_taytothtas' => $fake->greekIdNumber(),
            'f_ekdousa_arxh' => 'Α.Τ. ΑΘΗΝΩΝ',
            'f_date_ekdosis' => $fake->greekDate('-20 years', '-1 year'),
            'f_date_ekdosis_lixi' => '',

            // Residence Permits (Direct Access) - empty for Greek nationals
            'f_res_permit_inst' => '0',
            'f_res_permit_inst_type' => '',
            'f_res_permit_inst_ar' => '',
            'f_res_permit_inst_lixi' => '',

            // Residence Permits (Requires Approval) - empty for Greek nationals
            'f_res_permit_ap' => '0',
            'f_res_permit_ap_type' => '',
            'f_res_permit_ap_ar' => '',
            'f_res_permit_ap_lixi' => '',

            // Visa for Seasonal Work - empty for Greek nationals
            'f_res_permit_visa' => '0',
            'f_res_permit_visa_ar' => '',
            'f_res_permit_visa_from' => '',
            'f_res_permit_visa_to' => '',

            // Family Status
            'f_marital_status' => (string) $fake->randomElement(MaritalStatus::cases())->value,
            'f_arithmos_teknon' => $fake->numberBetween(0, 3),

            // Tax/Insurance IDs
            'f_afm' => $fake->afm(),
            'f_doy' => str_pad((string) $fake->numberBetween(1, 9999), 4, '0', STR_PAD_LEFT),
            'f_amika' => $fake->amika(),
            'f_amka' => $fake->amka($birthDate),
            'f_code_anergias' => '',
            'f_ar_vivliou_anilikou' => '',

            // Education
            'f_epipedo_morfosis' => (string) $fake->numberBetween(1, 10),

            // Employment Details
            'f_proslipsidate' => $hiringDate->format('d/m/Y'),
            'f_proslipsitime' => $startTime,
            'f_apoxwrisitime' => $fake->workEndTime($startTime),
            'f_week_hours' => 40.0,
            'f_eidikothta' => str_pad((string) $fake->numberBetween(1, 999999), 6, '0', STR_PAD_LEFT),
            'f_eidikothta_anal' => 'Υπάλληλος γραφείου',
            'f_proipiresia' => $fake->numberBetween(0, 20),
            'f_apodoxes' => $fake->randomFloat(2, 800, 3000),
            'f_hour_apodoxes' => $fake->randomFloat(2, 5, 20),
            'f_sxeshapasxolisis' => (string) EmploymentType::INDEFINITE->value,
            'f_orismenou_apo' => '',
            'f_orismenou_ews' => '',
            'f_kathestosapasxolisis' => (string) EmploymentStatus::FULL->value,
            'f_xaraktirismos' => (string) WorkerType::EMPLOYEE->value,
            'f_special_case' => '',
            'f_responsible_position' => ResponsiblePosition::NO->value,

            // Work Organization (Digital)
            'f_working_time_digital_organization' => '1',
            'f_full_employment_hours' => 40.0,
            'f_week_days' => (string) WeekDays::FIVE_DAY->value,
            'f_euelikto_wrario_minutes' => 0,
            'f_working_card' => '1',
            'f_dialeimma_minutes' => 30,
            'f_dialeimma_entos_wrariou' => '1',

            // DYPA/OAED Programs
            'f_topothetisioaed' => '0',
            'f_programaoaed' => '',
            'f_replaceprograma' => '0',
            'f_replaceprograma_afm' => '',
            'f_replaceprograma_amka' => '',

            // Trial Period
            'f_trial_period' => '0',
            'f_trial_date_to' => '',

            // Transfer Fields (E3M specific)
            'f_date_metabibashs' => $transferDate->format('d/m/Y'),
            'f_transfer_company_afm' => $fake->afm(),
            'f_transfer_company_eponimia' => $fake->company(),

            // Files
            'f_comments' => '',
            'f_foreign_file' => '',
            'f_young_file' => '',

            // Wage Payment
            'f_xronos_katavolis_apodoxon' => 'Μηνιαία',
            'f_ipoxreotiki_katartisi' => '0',
            'f_efarmoste_sillogiki_simbasi' => '0',
            'f_efarmoste_sillogiki_simbasi_comments' => '',

            // Insurance
            'f_kyria_asfalisi' => '101',
            'SupplementaryInsuranceSelections' => [],
            'f_prosthetes_asfalistikes' => '',

            // Unpredictable Work Schedule
            'f_mh_provlepsimo_programma' => '0',
            'f_paraggelia_hmeres_hours' => '',
            'f_paraggelia_min_notification' => '',
            'f_paraggelia_notes' => '',

            // Work Location
            'f_topos_ergasias' => (string) WorkLocation::EMPLOYER_BRANCH->value,
            'f_topos_ergasias_comment' => '',
        ];
    }

    // ==================== State Methods ====================

    /**
     * Configure the transfer details.
     *
     * @return $this
     */
    public function fromTransferCompany(string $afm, string $name, string $date): static
    {
        return $this->state([
            'f_transfer_company_afm' => $afm,
            'f_transfer_company_eponimia' => $name,
            'f_date_metabibashs' => $date,
        ]);
    }

    /**
     * Configure the transfer date.
     *
     * @return $this
     */
    public function transferDate(string $date): static
    {
        return $this->state(['f_date_metabibashs' => $date]);
    }

    /**
     * Configure the model as a fixed-term employment.
     *
     * @return $this
     */
    public function fixedTerm(?string $from = null, ?string $to = null): static
    {
        $startDate = $from ?? (new DateTimeImmutable('today'))->format('d/m/Y');
        $endDate = $to ?? (new DateTimeImmutable('+6 months'))->format('d/m/Y');

        return $this->state([
            'f_sxeshapasxolisis' => (string) EmploymentType::FIXED_TERM->value,
            'f_orismenou_apo' => $startDate,
            'f_orismenou_ews' => $endDate,
        ]);
    }

    /**
     * Configure the model as part-time employment.
     *
     * @return $this
     */
    public function partTime(float $weeklyHours = 20.0): static
    {
        return $this->state([
            'f_kathestosapasxolisis' => (string) EmploymentStatus::PARTIAL->value,
            'f_week_hours' => $weeklyHours,
            'f_apodoxes' => fn() => self::fake()->randomFloat(2, 400, 1500),
        ]);
    }

    /**
     * Configure the model with trial period.
     *
     * @return $this
     */
    public function withTrialPeriod(?string $endDate = null): static
    {
        $trialEnd = $endDate ?? (new DateTimeImmutable('+6 months'))->format('d/m/Y');

        return $this->state([
            'f_trial_period' => '1',
            'f_trial_date_to' => $trialEnd,
        ]);
    }

    /**
     * Configure the model for a blue-collar worker.
     *
     * @return $this
     */
    public function asWorker(): static
    {
        return $this->state([
            'f_xaraktirismos' => (string) WorkerType::WORKER->value,
            'f_eidikothta_anal' => 'Εργάτης παραγωγής',
        ]);
    }

    /**
     * Configure with collective agreement.
     *
     * @return $this
     */
    public function withCollectiveAgreement(string $comments = 'ΕΓΣΣΕ'): static
    {
        return $this->state([
            'f_efarmoste_sillogiki_simbasi' => '1',
            'f_efarmoste_sillogiki_simbasi_comments' => $comments,
        ]);
    }
}
