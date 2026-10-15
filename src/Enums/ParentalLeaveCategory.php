<?php

namespace OxygenSuite\OxygenErgani\Enums;

use OxygenSuite\OxygenErgani\Attributes\Label;
use OxygenSuite\OxygenErgani\Enums\Concerns\HasLabels;

/**
 * Employee category, required when the parental leave is not paid by the entity itself.
 *
 * Used in E14 parental leave declarations (f_category field).
 */
enum ParentalLeaveCategory: string
{
    use HasLabels;

    #[Label('Private law, fixed term (IDOX)', 'ΙΔΟΧ')]
    case PRIVATE_LAW_FIXED_TERM = '0';

    #[Label('Private law, indefinite term (IDAX)', 'ΙΔΑΧ')]
    case PRIVATE_LAW_INDEFINITE = '1';

    #[Label('Salaried mandate', 'Έμμισθη εντολή')]
    case SALARIED_MANDATE = '2';

    #[Label('Private sector employee', 'Εργαζόμενος στον ιδιωτικό τομέα')]
    case PRIVATE_SECTOR = '3';

    #[Label('Wider public sector employee', 'Εργαζόμενος στον ευρύτερο δημόσιο τομέα')]
    case WIDER_PUBLIC_SECTOR = '4';
}
