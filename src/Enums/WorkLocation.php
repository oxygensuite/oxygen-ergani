<?php

namespace OxygenSuite\OxygenErgani\Enums;

use OxygenSuite\OxygenErgani\Attributes\Label;
use OxygenSuite\OxygenErgani\Enums\Concerns\HasLabels;

/**
 * Location where the employee performs work.
 *
 * Used in E3 hiring declaration forms (f_topos_ergasias field).
 */
enum WorkLocation: int
{
    use HasLabels;

    #[Label("Employer's branch", 'Έδρα/Παράρτημα εργοδότη')]
    case EMPLOYER_BRANCH = 0;

    #[Label('Other location', 'Άλλος τόπος')]
    case OTHER = 1;
}
