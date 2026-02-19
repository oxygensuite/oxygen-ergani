<?php

namespace OxygenSuite\OxygenErgani\Factories\Modification;

use DateTimeImmutable;
use OxygenSuite\OxygenErgani\Enums\EmploymentStatus;
use OxygenSuite\OxygenErgani\Enums\LoanType;
use OxygenSuite\OxygenErgani\Enums\MaritalStatus;
use OxygenSuite\OxygenErgani\Enums\ResponsiblePosition;
use OxygenSuite\OxygenErgani\Enums\SalaryPaymentSource;
use OxygenSuite\OxygenErgani\Enums\SettlementType;
use OxygenSuite\OxygenErgani\Enums\Sex;
use OxygenSuite\OxygenErgani\Enums\WeekDays;
use OxygenSuite\OxygenErgani\Enums\WorkerType;
use OxygenSuite\OxygenErgani\Enums\WorkLocation;
use OxygenSuite\OxygenErgani\Factories\Factory;
use OxygenSuite\OxygenErgani\Models\Modification\BorrowedModificationDeclaration;

/**
 * Factory for generating fake BorrowedModificationDeclaration (MAD) models.
 *
 * @extends Factory<BorrowedModificationDeclaration>
 */
class BorrowedModificationDeclarationFactory extends Factory
{
    /**
     * Define the default attribute values for the BorrowedModificationDeclaration model.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $fake = fake();
        $gender = $fake->randomElement(['male', 'female']);
        $birthDate = $fake->dateTimeBetween('-55 years', '-20 years');
        $changeDate = new DateTimeImmutable('today');
        $borrowFrom = new DateTimeImmutable('today');
        $borrowTo = new DateTimeImmutable('+3 months');

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

            // Change Details
            'f_date_metabolhs' => $changeDate->format('d/m/Y'),
            'f_eidos_dieuthethshs' => '',
            'f_eidos_dieuthethshs_comments' => '',
            'f_periodos_anaforas_from' => '',
            'f_periodos_anaforas_to' => '',

            // Borrow Details (REQUIRED for MAD)
            'f_borrow_type' => (string) LoanType::GENUINE->value,
            'f_borrow_date_from' => $borrowFrom->format('d/m/Y'),
            'f_borrow_date_to' => $borrowTo->format('d/m/Y'),
            'f_borrow_company_afm' => $fake->afm(),
            'f_borrow_company_eponimia' => 'ΕΤΑΙΡΕΙΑ ' . $fake->numerify('####'),

            // Specialty
            'f_eidikothta' => str_pad((string) $fake->numberBetween(1, 999999), 6, '0', STR_PAD_LEFT),
            'f_eidikothta_anal' => 'Υπάλληλος γραφείου',

            // Salary Payment Source (MAD-only, REQUIRED)
            'f_kataboli_apodoxon' => (string) SalaryPaymentSource::DIRECT_EMPLOYER->value,

            // Salary (Optional - empty by default when paid by direct employer)
            'f_apodoxes' => '',
            'f_hour_apodoxes' => '',
            'f_xronos_katabolhs' => '',

            // Collective Agreement
            'f_efarmostea_sillogiki_simbasi' => '',
            'f_efarmostea_sillogiki_simbasi_comments' => '',

            // Work Location
            'f_topos_ergasias' => (string) WorkLocation::EMPLOYER_BRANCH->value,
            'f_topos_ergasias_comments' => '',

            // Employment Status
            'f_kathestosapasxolisis' => (string) EmploymentStatus::FULL->value,
            'f_xaraktirismos' => (string) WorkerType::EMPLOYEE->value,
            'f_responsible_position' => ResponsiblePosition::NO->value,

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

            // Files
            'f_comments' => '',
            'f_foreign_file' => '',
            'f_young_file' => '',
        ];
    }

    // ==================== State Methods ====================

    /**
     * Configure the model as a genuine loan.
     *
     * @return $this
     */
    public function genuineLoan(): static
    {
        return $this->state([
            'f_borrow_type' => (string) LoanType::GENUINE->value,
        ]);
    }

    /**
     * Configure the model as an EPA (Temporary Employment Agency) loan.
     *
     * @return $this
     */
    public function epaLoan(): static
    {
        return $this->state([
            'f_borrow_type' => (string) LoanType::EPA->value,
        ]);
    }

    /**
     * Configure the model with salary paid by direct employer.
     *
     * @return $this
     */
    public function paidByDirectEmployer(): static
    {
        return $this->state([
            'f_kataboli_apodoxon' => (string) SalaryPaymentSource::DIRECT_EMPLOYER->value,
            'f_apodoxes' => '',
            'f_hour_apodoxes' => '',
            'f_xronos_katabolhs' => '',
        ]);
    }

    /**
     * Configure the model with salary paid by indirect employer.
     *
     * @return $this
     */
    public function paidByIndirectEmployer(float $salary = 1200.00, float $hourlyWage = 8.00): static
    {
        return $this->state([
            'f_kataboli_apodoxon' => (string) SalaryPaymentSource::INDIRECT_EMPLOYER->value,
            'f_apodoxes' => $salary,
            'f_hour_apodoxes' => $hourlyWage,
            'f_xronos_katabolhs' => 'Μηνιαία',
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
     * Configure the model for a foreign national with direct access permit.
     *
     * @return $this
     */
    public function foreignNationalDirectAccess(string $nationality = '002'): static
    {
        $fake = fake();

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
            'f_onoma' => fn() => fake()->greekFirstName($gender),
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
