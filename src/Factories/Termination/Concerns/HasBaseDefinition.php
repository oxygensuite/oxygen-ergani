<?php

namespace OxygenSuite\OxygenErgani\Factories\Termination\Concerns;

use DateTimeImmutable;
use OxygenSuite\OxygenErgani\Enums\EmploymentStatus;
use OxygenSuite\OxygenErgani\Enums\EmploymentType;
use OxygenSuite\OxygenErgani\Enums\MaritalStatus;
use OxygenSuite\OxygenErgani\Enums\Sex;
use OxygenSuite\OxygenErgani\Enums\WorkerType;

/**
 * Provides base definition fields for all termination declaration factories.
 */
trait HasBaseDefinition
{
    /**
     * Get base definition fields shared by all termination declarations.
     *
     * @return array<string, mixed>
     */
    protected function baseDefinition(): array
    {
        $fake = fake();
        $gender = $fake->randomElement(['male', 'female']);
        $birthDate = $fake->dateTimeBetween('-55 years', '-20 years');
        $hiringDate = $fake->dateTimeBetween('-10 years', '-1 month');
        $departureDate = new DateTimeImmutable('today');

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
            'f_arithmos_teknon' => (string) $fake->numberBetween(0, 3),

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
            'f_xaraktirismos' => (string) WorkerType::EMPLOYEE->value,
            'f_sxeshapasxolisis' => (string) EmploymentType::INDEFINITE->value,
            'f_orismenou_apo' => '',
            'f_orismenou_ews' => '',
            'f_kathestosapasxolisis' => (string) EmploymentStatus::FULL->value,
            'f_eidikothta' => str_pad((string) $fake->numberBetween(1, 999999), 6, '0', STR_PAD_LEFT),
            'f_proslipsidate' => $hiringDate->format('d/m/Y'),
            'f_apoxwrisidate' => $departureDate->format('d/m/Y'),

            // Comments and Files
            'f_comments' => '',
            'f_foreign_file' => '',
            'f_young_file' => '',
        ];
    }

    // ==================== Common State Methods ====================

    /**
     * Configure the model as a fixed-term employment.
     */
    public function fixedTerm(?string $from = null, ?string $to = null): static
    {
        $startDate = $from ?? (new DateTimeImmutable('-6 months'))->format('d/m/Y');
        $endDate = $to ?? (new DateTimeImmutable('+6 months'))->format('d/m/Y');

        return $this->state([
            'f_sxeshapasxolisis' => (string) EmploymentType::FIXED_TERM->value,
            'f_orismenou_apo' => $startDate,
            'f_orismenou_ews' => $endDate,
        ]);
    }

    /**
     * Configure the model as part-time employment.
     */
    public function partTime(): static
    {
        return $this->state([
            'f_kathestosapasxolisis' => (string) EmploymentStatus::PARTIAL->value,
        ]);
    }

    /**
     * Configure the model for a foreign national with direct access permit.
     */
    public function foreignNational(string $nationality = '002'): static
    {
        $fake = fake();

        return $this->state([
            'f_yphkoothta' => $nationality,
            'f_typos_taytothtas' => 'ΔΙΑΒ',
            'f_res_permit_inst' => '1',
            'f_res_permit_inst_type' => str_pad((string) $fake->numberBetween(1, 99999), 5, '0', STR_PAD_LEFT),
            'f_res_permit_inst_ar' => $fake->bothify('??######'),
            'f_res_permit_inst_lixi' => (new DateTimeImmutable('+2 years'))->format('d/m/Y'),
        ]);
    }

    /**
     * Configure the model for a blue-collar worker.
     */
    public function asWorker(): static
    {
        return $this->state([
            'f_xaraktirismos' => (string) WorkerType::WORKER->value,
        ]);
    }

    /**
     * Configure the model for a specific gender.
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
     */
    public function male(): static
    {
        return $this->gender('male');
    }

    /**
     * Configure the model as female.
     */
    public function female(): static
    {
        return $this->gender('female');
    }

    /**
     * Configure the model with a related protocol (for corrections).
     */
    public function withRelatedProtocol(string $protocol, string $date): static
    {
        return $this->state([
            'f_rel_protocol' => $protocol,
            'f_rel_date' => $date,
        ]);
    }

    /**
     * Configure the model with departure date.
     */
    public function departureDate(string $date): static
    {
        return $this->state([
            'f_apoxwrisidate' => $date,
        ]);
    }
}
