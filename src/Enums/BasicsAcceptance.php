<?php

namespace OxygenSuite\OxygenErgani\Enums;

use OxygenSuite\OxygenErgani\Attributes\Label;
use OxygenSuite\OxygenErgani\Enums\Concerns\HasLabels;

/**
 * Method of submitting the employment basics/terms acceptance document.
 *
 * Used in E3 hiring declaration forms (f_basics_acceptance field).
 */
enum BasicsAcceptance: int
{
    use HasLabels;

    #[Label('Submit with file', 'Υποβολή με αρχείο')]
    case WITH_FILE = 0;

    #[Label('Await acceptance via MyErgani', 'Αναμονή αποδοχής μέσω MyErgani')]
    case AWAIT_MY_ERGANI = 1;

    #[Label('Not required', 'Δεν απαιτείται')]
    case NOT_REQUIRED = 2;
}
