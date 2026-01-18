<?php

namespace OxygenSuite\OxygenErgani\Enums;

use OxygenSuite\OxygenErgani\Attributes\Label;
use OxygenSuite\OxygenErgani\Enums\Concerns\HasLabels;

/**
 * Number of months for advance notice period in dismissals with notice.
 *
 * Used in E6NMP dismissal with notice declaration forms (f_minesproidopoihsh field).
 */
enum NoticePeriodMonths: int
{
    use HasLabels;

    #[Label('1 Month', '1 Μήνας')]
    case ONE = 1;

    #[Label('2 Months', '2 Μήνες')]
    case TWO = 2;

    #[Label('3 Months', '3 Μήνες')]
    case THREE = 3;

    #[Label('4 Months', '4 Μήνες')]
    case FOUR = 4;
}
