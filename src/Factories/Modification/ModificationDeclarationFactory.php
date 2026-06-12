<?php

namespace OxygenSuite\OxygenErgani\Factories\Modification;

use DateTimeImmutable;
use OxygenSuite\OxygenErgani\Enums\BasicsAcceptance;
use OxygenSuite\OxygenErgani\Enums\EmploymentStatus;
use OxygenSuite\OxygenErgani\Enums\EmploymentType;
use OxygenSuite\OxygenErgani\Enums\MaritalStatus;
use OxygenSuite\OxygenErgani\Enums\ResponsiblePosition;
use OxygenSuite\OxygenErgani\Enums\SettlementType;
use OxygenSuite\OxygenErgani\Enums\Sex;
use OxygenSuite\OxygenErgani\Enums\WeekDays;
use OxygenSuite\OxygenErgani\Enums\WorkerType;
use OxygenSuite\OxygenErgani\Enums\WorkLocation;
use OxygenSuite\OxygenErgani\Factories\Factory;
use OxygenSuite\OxygenErgani\Factories\Hiring\SupplementaryInsuranceSelectionFactory;
use OxygenSuite\OxygenErgani\Models\Modification\ModificationDeclaration;

/**
 * Factory for generating fake ModificationDeclaration (MA) models.
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
        $changeDate = new DateTimeImmutable('today');
        $referencePeriodFrom = new DateTimeImmutable('first day of this month');
        $referencePeriodTo = new DateTimeImmutable('last day of this month');

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
            'f_yphkoothta' => '001', // Greece
            'f_typos_taytothtas' => 'ΑΤ', // Police ID
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

            // Change Details (MA specific)
            'f_date_metabolhs' => $changeDate->format('d/m/Y'),
            'f_eidos_dieuthethshs' => '',
            'f_eidos_dieuthethshs_comments' => '',
            'f_periodos_anaforas_from' => $referencePeriodFrom->format('d/m/Y'),
            'f_periodos_anaforas_to' => $referencePeriodTo->format('d/m/Y'),

            // Specialty/Employment
            'f_eidikothta' => str_pad((string) $fake->numberBetween(1, 999999), 6, '0', STR_PAD_LEFT),
            'f_eidikothta_anal' => 'Υπάλληλος γραφείου',
            'f_proipiresia' => $fake->numberBetween(0, 20),
            'f_apodoxes' => $fake->randomFloat(2, 800, 3000),
            'f_hour_apodoxes' => $fake->randomFloat(2, 5, 20),
            'f_xronos_katabolhs' => 'Μηνιαία',

            // Work Location
            'f_topos_ergasias' => (string) WorkLocation::EMPLOYER_BRANCH->value,
            'f_topos_ergasias_comments' => '',

            // Employment Type
            'f_sxeshapasxolisis' => (string) EmploymentType::INDEFINITE->value,
            'f_orismenou_apo' => '',
            'f_orismenou_ews' => '',

            // Employment Status
            'f_kathestosapasxolisis' => (string) EmploymentStatus::FULL->value,
            'f_xaraktirismos' => (string) WorkerType::EMPLOYEE->value,
            'f_special_case' => '',
            'f_responsible_position' => ResponsiblePosition::NO->value,

            // Collective Agreement
            'f_efarmostea_sillogiki_simbasi' => '0',
            'f_efarmostea_sillogiki_simbasi_comments' => '',

            // Insurance
            'f_kyria_asfalish' => '101', // IKA/EFKA
            'f_prosthetes_asfalistikes_paroxes' => '',
            'f_ipoxreotiki_katartisi' => '0',

            // Work Organization (Digital)
            'f_working_time_digital_organization' => '1',
            'f_mh_problepsimo_programma' => '0',
            'f_paraggelia_hmeres_hours' => '',
            'f_paraggelia_min_notification' => '',
            'f_paraggelia_notes' => '',
            'f_week_hours' => 40.0,
            'f_full_employment_hours' => 40.0,
            'f_week_days' => (string) WeekDays::FIVE_DAY->value,
            'f_euelikto_wrario_minutes' => 0,
            'f_working_card' => '1',
            'f_dialeimma_minutes' => 30,
            'f_dialeimma_entos_wrariou' => '1',

            // DYPA/OAED Programs
            'f_topothetisioaed' => '0',
            'f_programaoaed' => '',

            // Trial Period
            'f_trial_period' => '0',
            'f_trial_date_to' => '',

            // Borrow Details (Optional for MA)
            'f_borrow_type' => '',
            'f_borrow_date_from' => '',
            'f_borrow_date_to' => '',
            'f_borrow_company_afm' => '',
            'f_borrow_company_eponimia' => '',

            // Acceptance and Files
            'f_basics_acceptance' => (string) BasicsAcceptance::NOT_REQUIRED->value,
            'f_file' => '',
            'f_comments' => '',
            'f_foreign_file' => '',
            'f_young_file' => '',
            'f_epibolh_file' => '',

            // Nested Arrays
            'ModificationTypeSelections' => [],
            'SupplementaryInsuranceSelections' => [],
        ];
    }

    // ==================== State Methods ====================

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
     * Configure the model as a borrowed employee.
     *
     * @return $this
     */
    public function borrowed(): static
    {
        $fake = self::fake();
        $from = new DateTimeImmutable('today');
        $to = new DateTimeImmutable('+3 months');

        return $this->state([
            'f_sxeshapasxolisis' => (string) EmploymentType::BORROWED->value,
            'f_borrow_type' => '0', // Genuine loan
            'f_borrow_date_from' => $from->format('d/m/Y'),
            'f_borrow_date_to' => $to->format('d/m/Y'),
            'f_borrow_company_afm' => $fake->afm(),
            'f_borrow_company_eponimia' => 'ΕΤΑΙΡΕΙΑ ' . $fake->numerify('####'),
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
     * Configure the model as rotation employment.
     *
     * @return $this
     */
    public function rotation(): static
    {
        return $this->state([
            'f_kathestosapasxolisis' => (string) EmploymentStatus::ROTATION->value,
            'f_epibolh_file' => 'JVBERi0xLjQK...', // Base64 PDF placeholder
        ]);
    }

    /**
     * Configure the model with modification types.
     *
     * @param array<int, string> $codes Modification type codes
     *
     * @return $this
     */
    public function withModificationTypes(array $codes): static
    {
        $selections = array_map(
            fn(string $code) => ModificationTypeSelectionFactory::new()->code($code)->make(),
            $codes,
        );

        return $this->state([
            'ModificationTypeSelections' => $selections,
        ]);
    }

    /**
     * Configure the model with supplementary insurance.
     *
     * @param array<int, string> $codes Supplementary insurance codes
     *
     * @return $this
     */
    public function withSupplementaryInsurance(array $codes = ['201']): static
    {
        $selections = array_map(
            fn(string $code) => SupplementaryInsuranceSelectionFactory::new()->code($code)->make(),
            $codes,
        );

        return $this->state([
            'SupplementaryInsuranceSelections' => $selections,
        ]);
    }

    /**
     * Configure the model with settlement type.
     *
     * @return $this
     */
    public function withSettlement(SettlementType $type, string $comment = ''): static
    {
        return $this->state([
            'f_eidos_dieuthethshs' => (string) $type->value,
            'f_eidos_dieuthethshs_comments' => $comment,
        ]);
    }

    /**
     * Configure the model with collective agreement.
     *
     * @return $this
     */
    public function withCollectiveAgreement(string $comments = 'ΕΓΣΣΕ'): static
    {
        return $this->state([
            'f_efarmostea_sillogiki_simbasi' => '1',
            'f_efarmostea_sillogiki_simbasi_comments' => $comments,
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
     * Configure the model with DYPA placement program.
     *
     * @return $this
     */
    public function withDypaPlacement(string $programCode = '001'): static
    {
        return $this->state([
            'f_topothetisioaed' => '1',
            'f_programaoaed' => $programCode,
        ]);
    }

    /**
     * Configure the model for a foreign national with direct access permit.
     *
     * @return $this
     */
    public function foreignNationalDirectAccess(string $nationality = '002'): static
    {
        $fake = self::fake();

        return $this->state([
            'f_yphkoothta' => $nationality,
            'f_typos_taytothtas' => 'ΔΙΑΒ', // Passport
            'f_res_permit_inst' => '1',
            'f_res_permit_inst_type' => str_pad((string) $fake->numberBetween(1, 99999), 5, '0', STR_PAD_LEFT),
            'f_res_permit_inst_ar' => $fake->bothify('??######'),
            'f_res_permit_inst_lixi' => (new DateTimeImmutable('+2 years'))->format('d/m/Y'),
        ]);
    }

    /**
     * Configure the model for a manager position.
     *
     * @return $this
     */
    public function asManager(): static
    {
        return $this->state([
            'f_responsible_position' => ResponsiblePosition::MANAGERIAL_AUTHORITY->value,
            'f_apodoxes' => fn() => self::fake()->randomFloat(2, 3000, 8000),
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
     * Configure the model with remote work location.
     *
     * @return $this
     */
    public function remoteWork(string $locationComment = 'Τηλεργασία από κατοικία'): static
    {
        return $this->state([
            'f_topos_ergasias' => (string) WorkLocation::OTHER->value,
            'f_topos_ergasias_comments' => $locationComment,
        ]);
    }

    /**
     * Configure the model for a specific gender.
     *
     * @return $this
     */
    public function gender(string $gender): static
    {
        return $this->state([
            'f_sex' => (string) ($gender === 'male' ? Sex::MALE->value : Sex::FEMALE->value),
            'f_onoma' => fn() => self::fake()->greekFirstName($gender),
        ]);
    }

    /**
     * Configure the model as male.
     *
     * @return $this
     */
    public function male(): static
    {
        return $this->gender('male');
    }

    /**
     * Configure the model as female.
     *
     * @return $this
     */
    public function female(): static
    {
        return $this->gender('female');
    }

    /**
     * Configure the model with essential terms acceptance via file.
     *
     * @return $this
     */
    public function withAcceptanceFile(string $base64Content = 'JVBERi0xLjQK...'): static
    {
        return $this->state([
            'f_basics_acceptance' => (string) BasicsAcceptance::WITH_FILE->value,
            'f_file' => $base64Content,
        ]);
    }

    /**
     * Configure the model with a related protocol (for corrections).
     *
     * @return $this
     */
    public function withRelatedProtocol(string $protocol, string $date): static
    {
        return $this->state([
            'f_rel_protocol' => $protocol,
            'f_rel_date' => $date,
        ]);
    }
}
