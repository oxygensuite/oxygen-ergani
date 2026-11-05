<?php

namespace OxygenSuite\OxygenErgani\Models\Internship\Concerns;

use OxygenSuite\OxygenErgani\Models\Programs\Concerns\HasMinorFile;
use OxygenSuite\OxygenErgani\Models\Programs\Concerns\HasProgramFiles;

/**
 * Attached files: signed form, third-country nationals documents, minor's work booklet (base64 PDFs).
 *
 * Used in: E3.5 (WebE35/WebE35C/WebE35D/WebE35T)
 */
trait HasInternshipFiles
{
    use HasMinorFile;
    use HasProgramFiles;
}
