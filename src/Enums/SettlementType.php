<?php

namespace OxygenSuite\OxygenErgani\Enums;

use OxygenSuite\OxygenErgani\Attributes\Label;
use OxygenSuite\OxygenErgani\Enums\Concerns\HasLabels;

/**
 * Type of work arrangement/settlement for employment changes.
 *
 * Used in MA employment change declaration forms (f_eidos_dieuthethshs field).
 */
enum SettlementType: int
{
    use HasLabels;

    #[Label('Collective agreement', 'Συλλογική σύμβαση')]
    case COLLECTIVE = 0;

    #[Label('Individual agreement', 'Ατομική συμφωνία')]
    case INDIVIDUAL = 1;

    #[Label('No settlement', 'Χωρίς διευθέτηση')]
    case NO = 2;
}
