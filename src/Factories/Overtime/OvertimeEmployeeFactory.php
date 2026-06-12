<?php

namespace OxygenSuite\OxygenErgani\Factories\Overtime;

use DateTimeImmutable;
use OxygenSuite\OxygenErgani\Factories\Factory;
use OxygenSuite\OxygenErgani\Models\Overtime\OvertimeEmployee;

/**
 * Factory for generating fake OvertimeEmployee models.
 *
 * @extends Factory<OvertimeEmployee>
 */
class OvertimeEmployeeFactory extends Factory
{
    /**
     * Define the default attribute values for the OvertimeEmployee model.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $fake = self::fake();
        $birthDate = $fake->dateTimeBetween('-55 years', '-20 years');
        $overtimeDate = new DateTimeImmutable('today');
        $startTime = $fake->time24h();

        return [
            'f_afm' => $fake->afm(),
            'f_amka' => $fake->amka($birthDate),
            'f_eponymo' => $fake->greekLastName(),
            'f_onoma' => $fake->greekFirstName(),
            'f_date' => $overtimeDate->format('d/m/Y'),
            'f_from' => $startTime,
            'f_to' => $fake->workEndTime($startTime),
            'f_cancellation' => '0',
            'f_step' => '1',
            'f_reason' => 'Αυξημένος φόρτος εργασίας',
            'f_weekdates' => '',
            'f_asee' => '',
        ];
    }

    // ==================== State Methods ====================

    /**
     * Configure the employee with specific TIN.
     *
     * @return $this
     */
    public function withTin(string $tin): static
    {
        return $this->state(['f_afm' => $tin]);
    }

    /**
     * Configure the employee with specific name.
     *
     * @return $this
     */
    public function withName(string $firstName, string $lastName): static
    {
        return $this->state([
            'f_onoma' => $firstName,
            'f_eponymo' => $lastName,
        ]);
    }

    /**
     * Configure the overtime for a specific date.
     *
     * @return $this
     */
    public function forDate(string $date): static
    {
        return $this->state(['f_date' => $date]);
    }

    /**
     * Configure the overtime time range.
     *
     * @return $this
     */
    public function timeRange(string $from, string $to): static
    {
        return $this->state([
            'f_from' => $from,
            'f_to' => $to,
        ]);
    }

    /**
     * Mark as cancellation.
     *
     * @return $this
     */
    public function asCancellation(): static
    {
        return $this->state(['f_cancellation' => '1']);
    }

    /**
     * Configure the overtime step.
     *
     * @return $this
     */
    public function step(string $step): static
    {
        return $this->state(['f_step' => $step]);
    }

    /**
     * Configure the overtime reason.
     *
     * @return $this
     */
    public function withReason(string $reason): static
    {
        return $this->state(['f_reason' => $reason]);
    }

    /**
     * Configure for weekly overtime.
     *
     * @return $this
     */
    public function weekly(string $weekDates): static
    {
        return $this->state(['f_weekdates' => $weekDates]);
    }

    /**
     * Configure ASEE number.
     *
     * @return $this
     */
    public function withAsee(string $asee): static
    {
        return $this->state(['f_asee' => $asee]);
    }
}
