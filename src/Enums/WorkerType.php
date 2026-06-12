<?php

namespace OxygenSuite\OxygenErgani\Enums;

use OxygenSuite\OxygenErgani\Attributes\Label;
use OxygenSuite\OxygenErgani\Enums\Concerns\HasLabels;

/**
 * Classification of worker type.
 *
 * Used in E3 hiring declaration forms (f_xaraktirismos field).
 */
enum WorkerType: int
{
    use HasLabels;

    #[Label('Worker', 'Εργάτης')]
    case WORKER = 0;

    #[Label('Employee', 'Υπάλληλος')]
    case EMPLOYEE = 1;
}
