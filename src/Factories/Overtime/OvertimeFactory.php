<?php

namespace OxygenSuite\OxygenErgani\Factories\Overtime;

use OxygenSuite\OxygenErgani\Factories\Factory;
use OxygenSuite\OxygenErgani\Models\Overtime\Overtime;
use OxygenSuite\OxygenErgani\Models\Overtime\OvertimeEmployee;

/**
 * Factory for generating fake Overtime models.
 *
 * @extends Factory<Overtime>
 */
class OvertimeFactory extends Factory
{
    /**
     * Define the default attribute values for the Overtime model.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $fake = fake();

        return [
            'f_aa_pararthmatos' => (string) $fake->numberBetween(0, 99),
            'f_rel_protocol' => '',
            'f_rel_date' => '',
            'f_ypiresia_sepe' => str_pad((string) $fake->numberBetween(1, 99999), 5, '0', STR_PAD_LEFT),
            'f_ergodotikh_organwsh' => str_pad((string) $fake->numberBetween(1, 99999), 5, '0', STR_PAD_LEFT),
            'f_kad_kyria' => str_pad((string) $fake->numberBetween(1, 9999), 4, '0', STR_PAD_LEFT),
            'f_kad_deyt_1' => '',
            'f_kad_deyt_2' => '',
            'f_kad_deyt_3' => '',
            'f_kad_deyt_4' => '',
            'f_kad_pararthmatos' => str_pad((string) $fake->numberBetween(1, 9999), 4, '0', STR_PAD_LEFT),
            'f_kallikratis_pararthmatos' => str_pad((string) $fake->numberBetween(1, 99999999), 8, '0', STR_PAD_LEFT),
            'f_comments' => '',
            'f_afm_proswpoy' => $fake->afm(),
            'Ergazomenoi' => ['OvertimeErgazomenosDate' => []],
        ];
    }

    // ==================== State Methods ====================

    /**
     * Configure the branch code.
     *
     * @return $this
     */
    public function forBranch(int|string $branchCode): static
    {
        return $this->state(['f_aa_pararthmatos' => (string) $branchCode]);
    }

    /**
     * Configure with related protocol.
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

    /**
     * Configure SEPE service code.
     *
     * @return $this
     */
    public function withSepeService(string $code): static
    {
        return $this->state(['f_ypiresia_sepe' => $code]);
    }

    /**
     * Configure employer organization code.
     *
     * @return $this
     */
    public function withEmployerOrganization(string $code): static
    {
        return $this->state(['f_ergodotikh_organwsh' => $code]);
    }

    /**
     * Configure primary KAD code.
     *
     * @return $this
     */
    public function withPrimaryKad(string $kad): static
    {
        return $this->state(['f_kad_kyria' => $kad]);
    }

    /**
     * Configure secondary KAD codes.
     *
     * @return $this
     */
    public function withSecondaryKads(string $kad1 = '', string $kad2 = '', string $kad3 = '', string $kad4 = ''): static
    {
        return $this->state([
            'f_kad_deyt_1' => $kad1,
            'f_kad_deyt_2' => $kad2,
            'f_kad_deyt_3' => $kad3,
            'f_kad_deyt_4' => $kad4,
        ]);
    }

    /**
     * Configure comments.
     *
     * @return $this
     */
    public function withComments(string $comments): static
    {
        return $this->state(['f_comments' => $comments]);
    }

    /**
     * Configure representative TIN.
     *
     * @return $this
     */
    public function withRepresentative(string $tin): static
    {
        return $this->state(['f_afm_proswpoy' => $tin]);
    }

    /**
     * Add employees to the overtime declaration.
     *
     * @param array<int, OvertimeEmployee> $employees
     *
     * @return $this
     */
    public function withEmployees(array $employees): static
    {
        return $this->state([
            'Ergazomenoi' => ['OvertimeErgazomenosDate' => $employees],
        ]);
    }

    /**
     * Create overtime with generated employees.
     *
     * @return $this
     */
    public function withGeneratedEmployees(int $count = 1): static
    {
        $result = OvertimeEmployeeFactory::new()->count($count)->make();
        /** @var array<int, \OxygenSuite\OxygenErgani\Models\Overtime\OvertimeEmployee> $employees */
        $employees = $count === 1 ? [$result] : $result;

        return $this->withEmployees($employees);
    }
}
