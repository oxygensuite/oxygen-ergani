<?php

namespace OxygenSuite\OxygenErgani\Enums;

use OxygenSuite\OxygenErgani\Attributes\Label;
use OxygenSuite\OxygenErgani\Enums\Concerns\HasLabels;

/**
 * Employment status indicating the type of working hours arrangement.
 *
 * Used in E3 hiring declaration forms (f_kathestosapasxolisis field).
 */
enum EmploymentStatus: int
{
    use HasLabels;

    #[Label('Full-time', 'Πλήρης απασχόληση')]
    case FULL = 0;

    #[Label('Part-time', 'Μερική απασχόληση')]
    case PARTIAL = 1;

    #[Label('Rotational', 'Εκ περιτροπής απασχόληση')]
    case ROTATION = 2;

    #[Label('On-demand', 'Διαλείπουσα απασχόληση')]
    case ON_DEMAND = 3;
}
