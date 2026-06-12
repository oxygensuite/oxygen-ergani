<?php

namespace OxygenSuite\OxygenErgani\Enums;

use OxygenSuite\OxygenErgani\Attributes\Label;
use OxygenSuite\OxygenErgani\Enums\Concerns\HasLabels;

/**
 * Special employment case for public sector employees under private law.
 *
 * Used in E3 hiring declaration forms (f_special_case field).
 */
enum SpecialCase: string
{
    use HasLabels;

    #[Label('Not applicable', 'Δεν εφαρμόζεται')]
    case NONE = '';

    #[Label('Private law - Narrow public sector', 'Ιδιωτικού δικαίου - Στενός δημόσιος τομέας')]
    case PRIVATE_LAW_NARROW_PUBLIC = '2';

    #[Label('Private law - Broader public sector', 'Ιδιωτικού δικαίου - Ευρύτερος δημόσιος τομέας')]
    case PRIVATE_LAW_BROADER_PUBLIC = '3';
}
