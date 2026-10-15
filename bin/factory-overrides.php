<?php

/**
 * Factory variants for documents whose model's default factory output targets another variant
 * of the same schema (shared by submit:check and tests/Unit/XsdComplianceTest).
 *
 * Keys are document names from bin/schema-map.php.
 *
 * @return array<string, callable(): \OxygenSuite\OxygenErgani\Models\Model>
 */

use OxygenSuite\OxygenErgani\Models\WorkTime\WorkTime;

return [
    'WeeklyWorkTime' => fn() => WorkTime::factory()->weekly()->make(),
    'WorkTimeLeave' => fn() => WorkTime::factory()->leave()->make(),
    'WorkTimeLeaveC' => fn() => WorkTime::factory()->leave()->make(),
    'Overtime' => fn() => WorkTime::factory()->overtime()->make(),
    'OvertimeA' => fn() => WorkTime::factory()->overtime()->make(),
    'OvertimeD' => fn() => WorkTime::factory()->overtime()->make(),
];
