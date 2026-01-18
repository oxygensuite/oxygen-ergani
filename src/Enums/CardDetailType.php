<?php

namespace OxygenSuite\OxygenErgani\Enums;

use OxygenSuite\OxygenErgani\Attributes\Label;
use OxygenSuite\OxygenErgani\Enums\Concerns\HasLabels;

/**
 * Work card entry type (check-in or check-out).
 *
 * Used in work card submissions.
 */
enum CardDetailType: string
{
    use HasLabels;

    #[Label('Check-in', 'Προσέλευση')]
    case CHECK_IN = '0';

    #[Label('Check-out', 'Αποχώρηση')]
    case CHECK_OUT = '1';
}
