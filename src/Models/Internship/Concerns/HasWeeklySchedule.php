<?php

namespace OxygenSuite\OxygenErgani\Models\Internship\Concerns;

/**
 * Weekly schedule fields (f_time_*, f_second_time_*, f_break_time_*) for days 1-7.
 *
 * Used in: E3.5 (WebE35/WebE35C), E3.5 schedule change (WebE35T)
 */
trait HasWeeklySchedule
{
    /**
     * Set primary schedule for a day (1=Monday, 7=Sunday).
     */
    public function setSchedule(int $day, string $from, string $to): static
    {
        return $this
            ->set("f_time_from_{$day}", $from)
            ->set("f_time_to_{$day}", $to);
    }

    /**
     * Set split-shift schedule for a day (1=Monday, 7=Sunday).
     */
    public function setSplitSchedule(int $day, string $from, string $to): static
    {
        return $this
            ->set("f_second_time_from_{$day}", $from)
            ->set("f_second_time_to_{$day}", $to);
    }

    /**
     * Set break schedule for a day (1=Monday, 7=Sunday).
     */
    public function setBreakSchedule(int $day, string $from, string $to): static
    {
        return $this
            ->set("f_break_time_from_{$day}", $from)
            ->set("f_break_time_to_{$day}", $to);
    }

    /**
     * Get primary schedule for a day (1=Monday, 7=Sunday).
     *
     * @return array{from: ?string, to: ?string}
     */
    public function getSchedule(int $day): array
    {
        return ['from' => $this->get("f_time_from_{$day}"), 'to' => $this->get("f_time_to_{$day}")];
    }

    /**
     * Get split-shift schedule for a day (1=Monday, 7=Sunday).
     *
     * @return array{from: ?string, to: ?string}
     */
    public function getSplitSchedule(int $day): array
    {
        return ['from' => $this->get("f_second_time_from_{$day}"), 'to' => $this->get("f_second_time_to_{$day}")];
    }

    /**
     * Get break schedule for a day (1=Monday, 7=Sunday).
     *
     * @return array{from: ?string, to: ?string}
     */
    public function getBreakSchedule(int $day): array
    {
        return ['from' => $this->get("f_break_time_from_{$day}"), 'to' => $this->get("f_break_time_to_{$day}")];
    }
}
