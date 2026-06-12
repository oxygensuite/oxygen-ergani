<?php

namespace OxygenSuite\OxygenErgani\Enums;

use OxygenSuite\OxygenErgani\Attributes\Label;
use OxygenSuite\OxygenErgani\Enums\Concerns\HasLabels;

/**
 * Type of work time entry for work time declarations (WTO).
 *
 * Codes are sourced from ParameterLookup::WORK_TIME_TYPE.
 * Use Parameters::compareWithEnum() to check for drift.
 */
enum WorkTimeType: string
{
    use HasLabels;

    // ============================================
    // WORK TYPES (ΕΡΓΑΣΙΑ-ΕΡΓΑΣΙΑ)
    // ============================================

    #[Label('Work', 'Εργασία')]
    case WORK = 'ΕΡΓ';

    #[Label('Remote work', 'Τηλεργασία')]
    case REMOTE_WORK = 'ΤΗΛ';

    // ============================================
    // REST/NON-WORK (ΕΡΓΑΣΙΑ-ΧΩΡΙΣ ΕΡΓΑΣΙΑ ΑΝΑΠΑΥΣΗ ΡΕΠΟ)
    // ============================================

    #[Label('Rest/Day off', 'Ανάπαυση/Ρεπό')]
    case REST = 'ΑΝ';

    #[Label('Non-working', 'Μη εργασία')]
    case NON_WORKING = 'ΜΕ';

    // ============================================
    // FULL-DAY LEAVE (ΑΔΕΙΑ-ΑΔΕΙΑ)
    // ============================================

    #[Label('Regular leave', 'Κανονική άδεια')]
    case LEAVE_REGULAR = 'ΑΔΚΑΝ';

    #[Label('Blood donation leave', 'Αιμοδοτική άδεια')]
    case LEAVE_BLOOD_DONATION = 'ΑΔΑΙΜ';

    #[Label('Examination leave', 'Άδεια εξετάσεων')]
    case LEAVE_EXAMINATION = 'ΑΔΕΞ';

    #[Label('Unpaid leave', 'Άδεια άνευ αποδοχών')]
    case LEAVE_UNPAID = 'ΑΔΑΑ';

    #[Label('Maternity leave', 'Άδεια μητρότητας')]
    case LEAVE_MATERNITY = 'ΑΔΜΗ';

    #[Label('Maternity protection', 'Ειδική παροχή προστασίας της μητρότητας')]
    case LEAVE_MATERNITY_PROTECTION = 'ΑΔΠΠΜ';

    #[Label('Paternity leave', 'Άδεια πατρότητας')]
    case LEAVE_PATERNITY = 'ΑΔΠΑ';

    #[Label('Child care leave', 'Άδεια φροντίδας παιδιού')]
    case LEAVE_CHILD_CARE = 'ΑΔΦΠ';

    #[Label('Parental leave', 'Γονική άδεια')]
    case LEAVE_PARENTAL = 'ΑΔΓΟΝ';

    #[Label('Caregiver leave', 'Άδεια φροντιστή')]
    case LEAVE_CAREGIVER = 'ΑΔΦΡΟ';

    #[Label('Force majeure absence', 'Απουσία για λόγους ανωτέρας βίας')]
    case LEAVE_FORCE_MAJEURE = 'ΑΔΑΠΑΒ';

    #[Label('Assisted reproduction leave', 'Άδεια για ιατρικώς υποβοηθούμενη αναπαραγωγή')]
    case LEAVE_ASSISTED_REPRODUCTION = 'ΑΔΙΥΑ';

    #[Label('Prenatal examination leave', 'Άδεια εξετάσεων προγεννητικού ελέγχου')]
    case LEAVE_PRENATAL = 'ΑΔΠΕ';

    #[Label('Marriage leave', 'Άδεια γάμου')]
    case LEAVE_MARRIAGE = 'ΑΔΓΑΜ';

    #[Label('Serious child illness leave', 'Άδεια λόγω σοβαρών νοσημάτων των παιδιών')]
    case LEAVE_CHILD_SERIOUS_ILLNESS = 'ΑΔΣΝΠ';

    #[Label('Child hospitalization leave', 'Άδεια λόγω νοσηλείας των παιδιών')]
    case LEAVE_CHILD_HOSPITALIZATION = 'ΑΔΝΠ';

    #[Label('Single parent leave', 'Άδεια μονογονεϊκών οικογενειών')]
    case LEAVE_SINGLE_PARENT = 'ΑΔΜΟ';

    #[Label('School performance leave', 'Άδεια παρακολούθησης σχολικής επίδοσης τέκνου')]
    case LEAVE_SCHOOL_PERFORMANCE = 'ΑΔΠΣΕΤ';

    #[Label('Child/dependent illness leave', 'Άδεια λόγω ασθένειας παιδιού ή εξαρτώμενου μέλους')]
    case LEAVE_CHILD_ILLNESS = 'ΑΔΑΠΕΜ';

    #[Label('Violence/harassment risk absence', 'Απουσία λόγω κινδύνου βίας ή παρενόχλησης')]
    case LEAVE_HARASSMENT_RISK = 'ΑΔΑΠΣΚ';

    #[Label('Sick leave', 'Άδεια ασθένειας')]
    case LEAVE_SICK = 'ΑΔΑΣ';

    #[Label('Disability leave', 'Άδεια απουσίας Α.Μ.Ε.Α.')]
    case LEAVE_DISABILITY = 'ΑΔΑΜΕΑ';

    #[Label('Bereavement leave', 'Άδεια λόγω θανάτου συγγενούς')]
    case LEAVE_BEREAVEMENT = 'ΑΔΘΣΥΓ';

    #[Label('Minor student leave', 'Άδεια ανήλικων σπουδαστών')]
    case LEAVE_MINOR_STUDENT = 'ΑΔΑΝΣΠ';

    #[Label('Blood transfusion/dialysis leave', 'Άδεια για μεταγγίσεις αίματος ή αιμοκάθαρση')]
    case LEAVE_TRANSFUSION = 'ΑΔΜΑΑ';

    #[Label('KANEP-GSEE educational leave', 'Εκπαιδευτική άδεια για φοιτητές στο Κ.ΑΝ.Ε.Π.-Γ.Σ.Ε.Ε.')]
    case LEAVE_KANEP_EDUCATION = 'ΑΔΕΚΦ';

    #[Label('AIDS leave', 'Άδεια λόγω AIDS')]
    case LEAVE_AIDS = 'ΑΔΣΕΑΑ';

    #[Label('Flexible work arrangements', 'Ευέλικτες ρυθμίσεις εργασίας')]
    case LEAVE_FLEXIBLE = 'ΑΔΕΡΕ';

    #[Label('Other leave', 'Άδεια Άλλη')]
    case LEAVE_OTHER = 'ΑΔΑΛ';

    // ============================================
    // HOURLY LEAVE (ΑΔΕΙΑ-ΩΡΟΑΔΕΙΑ)
    // ============================================

    #[Label('Child care leave (hours)', 'Άδεια φροντίδας παιδιού (ΩΡΕΣ)')]
    case HOURLY_CHILD_CARE = 'ΩΑΦΠ';

    #[Label('Parental leave (hours)', 'Γονική άδεια (ΩΡΕΣ)')]
    case HOURLY_PARENTAL = 'ΩΑΓΟΝ';

    #[Label('Force majeure absence (hours)', 'Απουσία για λόγους ανωτέρας βίας (ΩΡΕΣ)')]
    case HOURLY_FORCE_MAJEURE = 'ΩΑΑΠΑΒ';

    #[Label('Flexible work arrangements (hours)', 'Ευέλικτες ρυθμίσεις εργασίας (ΩΡΕΣ)')]
    case HOURLY_FLEXIBLE = 'ΩΑΕΡΕ';

    #[Label('Prenatal examination leave (hours)', 'Άδεια εξετάσεων προγεννητικού ελέγχου (ΩΡΕΣ)')]
    case HOURLY_PRENATAL = 'ΩΑΠΕ';

    #[Label('School performance leave (hours)', 'Άδεια παρακολούθησης σχολικής επίδοσης τέκνου (ΩΡΕΣ)')]
    case HOURLY_SCHOOL_PERFORMANCE = 'ΩΑΠΣΕΤ';

    #[Label('Other leave (hours)', 'Άδεια Άλλη (ΩΡΕΣ)')]
    case HOURLY_OTHER = 'ΩΑΑΛ';

    // ============================================
    // OVERTIME (ΥΠΕΡΩΡΙΑ)
    // ============================================

    #[Label('Overtime', 'Υπερωρία')]
    case OVERTIME = 'ΥΠ';

    #[Label('No overtime', 'Χωρίς υπερωρία')]
    case NO_OVERTIME = 'ΧΥΠ';

    // ============================================
    // HELPER METHODS
    // ============================================

    /**
     * Get all work types (regular work, remote work).
     *
     * @return array<self>
     */
    public static function work(): array
    {
        return [
            self::WORK,
            self::REMOTE_WORK,
        ];
    }

    /**
     * Get all rest/non-work types.
     *
     * @return array<self>
     */
    public static function rest(): array
    {
        return [
            self::REST,
            self::NON_WORKING,
        ];
    }

    /**
     * Get all work and rest types combined (for weekly schedule dropdowns).
     *
     * @return array<self>
     */
    public static function schedule(): array
    {
        return [...self::work(), ...self::rest()];
    }

    /**
     * Get all full-day leave types.
     *
     * @return array<self>
     */
    public static function dayLeaves(): array
    {
        return [
            self::LEAVE_REGULAR,
            self::LEAVE_BLOOD_DONATION,
            self::LEAVE_EXAMINATION,
            self::LEAVE_UNPAID,
            self::LEAVE_MATERNITY,
            self::LEAVE_MATERNITY_PROTECTION,
            self::LEAVE_PATERNITY,
            self::LEAVE_CHILD_CARE,
            self::LEAVE_PARENTAL,
            self::LEAVE_CAREGIVER,
            self::LEAVE_FORCE_MAJEURE,
            self::LEAVE_ASSISTED_REPRODUCTION,
            self::LEAVE_PRENATAL,
            self::LEAVE_MARRIAGE,
            self::LEAVE_CHILD_SERIOUS_ILLNESS,
            self::LEAVE_CHILD_HOSPITALIZATION,
            self::LEAVE_SINGLE_PARENT,
            self::LEAVE_SCHOOL_PERFORMANCE,
            self::LEAVE_CHILD_ILLNESS,
            self::LEAVE_HARASSMENT_RISK,
            self::LEAVE_SICK,
            self::LEAVE_DISABILITY,
            self::LEAVE_BEREAVEMENT,
            self::LEAVE_MINOR_STUDENT,
            self::LEAVE_TRANSFUSION,
            self::LEAVE_KANEP_EDUCATION,
            self::LEAVE_AIDS,
            self::LEAVE_FLEXIBLE,
            self::LEAVE_OTHER,
        ];
    }

    /**
     * Get all hourly leave types.
     *
     * @return array<self>
     */
    public static function hourlyLeaves(): array
    {
        return [
            self::HOURLY_CHILD_CARE,
            self::HOURLY_PARENTAL,
            self::HOURLY_FORCE_MAJEURE,
            self::HOURLY_FLEXIBLE,
            self::HOURLY_PRENATAL,
            self::HOURLY_SCHOOL_PERFORMANCE,
            self::HOURLY_OTHER,
        ];
    }

    /**
     * Get all leave types (full-day and hourly combined).
     *
     * @return array<self>
     */
    public static function leaves(): array
    {
        return [...self::dayLeaves(), ...self::hourlyLeaves()];
    }

    /**
     * Get all overtime types.
     *
     * @return array<self>
     */
    public static function overtime(): array
    {
        return [
            self::OVERTIME,
            self::NO_OVERTIME,
        ];
    }

    /**
     * Check if this type is a work type.
     */
    public function isWork(): bool
    {
        return in_array($this, self::work(), true);
    }

    /**
     * Check if this type is a rest/non-work type.
     */
    public function isRest(): bool
    {
        return in_array($this, self::rest(), true);
    }

    /**
     * Check if this type is a schedule type (work or rest).
     */
    public function isSchedule(): bool
    {
        return in_array($this, self::schedule(), true);
    }

    /**
     * Check if this type is a leave type (full-day or hourly).
     */
    public function isLeave(): bool
    {
        return in_array($this, self::leaves(), true);
    }

    /**
     * Check if this type is a full-day leave type.
     */
    public function isDayLeave(): bool
    {
        return in_array($this, self::dayLeaves(), true);
    }

    /**
     * Check if this type is an hourly leave type.
     */
    public function isHourlyLeave(): bool
    {
        return in_array($this, self::hourlyLeaves(), true);
    }

    /**
     * Check if this type is an overtime type.
     */
    public function isOvertime(): bool
    {
        return in_array($this, self::overtime(), true);
    }
}
