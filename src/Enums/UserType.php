<?php

namespace OxygenSuite\OxygenErgani\Enums;

use OxygenSuite\OxygenErgani\Attributes\Label;
use OxygenSuite\OxygenErgani\Enums\Concerns\HasLabels;

/**
 * Type of user authenticating with ERGANI.
 */
enum UserType: string
{
    use HasLabels;

    #[Label('External user', 'Εξωτερικός χρήστης')]
    case EXTERNAL = '01';

    #[Label('ERGANI user', 'Χρήστης ΕΡΓΑΝΗ')]
    case ERGANI = '02';

    #[Label('EFKA user', 'Χρήστης ΕΦΚΑ')]
    case EFKA = '03';
}
