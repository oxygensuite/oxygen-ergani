<?php

namespace OxygenSuite\OxygenErgani\Enums;

use OxygenSuite\OxygenErgani\Attributes\Label;
use OxygenSuite\OxygenErgani\Enums\Concerns\HasLabels;

/**
 * Individual employment contract status.
 *
 * Used in E3 hiring declaration forms (f_atomikh_symbash field).
 */
enum IndividualContract: int
{
    use HasLabels;

    #[Label('No', 'Όχι')]
    case NO = 0;

    #[Label('Acceptance with attached file', 'Αποδοχή με επισυναπτόμενο αρχείο')]
    case WITH_FILE = 1;

    #[Label('Pending', 'Εκκρεμεί')]
    case PENDING = 2;
}
