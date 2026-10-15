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

    /**
     * Not accepted by the schema files ERGANI currently validates against (E3N_v1.xsd, E3M_v1.xsd, MA_v1.xsd
     * and the other forms allow only 0-2), so ERGANI rejects it. It is defined again in the newer
     * E3N_v2.xsd, E3M_v2.xsd and MA_v2.xsd schema files, which ERGANI has published but not activated yet.
     */
    #[Label('On-demand', 'Διαλείπουσα απασχόληση')]
    case ON_DEMAND = 3;
}
