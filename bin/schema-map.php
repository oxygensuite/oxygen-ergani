<?php

/**
 * Document groups shared by the maintainer CLI tools (schema:check, submit:check).
 *
 * Each group maps an XSD file in xsd/ to the document classes validated against it.
 * Documents sharing the same XSD are grouped together.
 *
 * @return array<string, array{xsd: string, documents: array<string, class-string>}>
 */

use OxygenSuite\OxygenErgani\Http\Documents\CommunityProgram\CommunityProgramEmployment;
use OxygenSuite\OxygenErgani\Http\Documents\CommunityProgram\CommunityProgramEmploymentCorrection;
use OxygenSuite\OxygenErgani\Http\Documents\Construction\ConstructionWorkCensus;
use OxygenSuite\OxygenErgani\Http\Documents\Construction\ConstructionWorkDeclaration;
use OxygenSuite\OxygenErgani\Http\Documents\Dismissal\DismissalWithNotice;
use OxygenSuite\OxygenErgani\Http\Documents\Dismissal\DismissalWithoutNotice;
use OxygenSuite\OxygenErgani\Http\Documents\Dismissal\EndOfLoan;
use OxygenSuite\OxygenErgani\Http\Documents\Dismissal\RetirementDismissal;
use OxygenSuite\OxygenErgani\Http\Documents\Dismissal\Transfer;
use OxygenSuite\OxygenErgani\Http\Documents\Dismissal\TrialPeriodTermination;
use OxygenSuite\OxygenErgani\Http\Documents\Hiring\HiringDeletion;
use OxygenSuite\OxygenErgani\Http\Documents\Hiring\HiringModification;
use OxygenSuite\OxygenErgani\Http\Documents\Hiring\HiringNew;
use OxygenSuite\OxygenErgani\Http\Documents\Hiring\HiringWithLending;
use OxygenSuite\OxygenErgani\Http\Documents\Internship\Internship;
use OxygenSuite\OxygenErgani\Http\Documents\Internship\InternshipCorrection;
use OxygenSuite\OxygenErgani\Http\Documents\Internship\InternshipScheduleChange;
use OxygenSuite\OxygenErgani\Http\Documents\Internship\InternshipTermination;
use OxygenSuite\OxygenErgani\Http\Documents\Modification\BorrowedEmploymentModification;
use OxygenSuite\OxygenErgani\Http\Documents\Modification\EmploymentModification;
use OxygenSuite\OxygenErgani\Http\Documents\Motorcycle\MotorcycleUse;
use OxygenSuite\OxygenErgani\Http\Documents\Overtime\WorkTimeOvertime;
use OxygenSuite\OxygenErgani\Http\Documents\Overtime\WorkTimeOvertimeDrivers;
use OxygenSuite\OxygenErgani\Http\Documents\Overtime\WorkTimeOvertimeRetrospective;
use OxygenSuite\OxygenErgani\Http\Documents\ParentalLeave\ParentalLeave;
use OxygenSuite\OxygenErgani\Http\Documents\ParentalLeave\ParentalLeaveCorrection;
use OxygenSuite\OxygenErgani\Http\Documents\PreAnnouncement\PreAnnouncementExemption;
use OxygenSuite\OxygenErgani\Http\Documents\SixthDay\SixthDay;
use OxygenSuite\OxygenErgani\Http\Documents\Termination\FixedTermTermination;
use OxygenSuite\OxygenErgani\Http\Documents\Termination\ResignationAfterNotification;
use OxygenSuite\OxygenErgani\Http\Documents\Termination\ResignationNotification;
use OxygenSuite\OxygenErgani\Http\Documents\Termination\RetirementMandatory;
use OxygenSuite\OxygenErgani\Http\Documents\Termination\RetirementVoluntary;
use OxygenSuite\OxygenErgani\Http\Documents\Termination\TerminationByDeath;
use OxygenSuite\OxygenErgani\Http\Documents\Termination\VoluntaryExitCompensation;
use OxygenSuite\OxygenErgani\Http\Documents\Termination\VoluntaryResignation;
use OxygenSuite\OxygenErgani\Http\Documents\Training\TheoreticalTraining;
use OxygenSuite\OxygenErgani\Http\Documents\Training\TheoreticalTrainingCorrection;
use OxygenSuite\OxygenErgani\Http\Documents\UrgentHiring\UrgentHiring;
use OxygenSuite\OxygenErgani\Http\Documents\UrgentHiring\UrgentHiringCorrection;
use OxygenSuite\OxygenErgani\Http\Documents\WorkCard\WorkCard;
use OxygenSuite\OxygenErgani\Http\Documents\WorkTime\DailyWorkTime;
use OxygenSuite\OxygenErgani\Http\Documents\WorkTime\DailyWorkTimeDrivers;
use OxygenSuite\OxygenErgani\Http\Documents\WorkTime\DailyWorkTimeRetrospective;
use OxygenSuite\OxygenErgani\Http\Documents\WorkTime\WeeklyWorkTime;
use OxygenSuite\OxygenErgani\Http\Documents\WorkTime\WorkTimeLeave;
use OxygenSuite\OxygenErgani\Http\Documents\WorkTime\WorkTimeLeaveCorrection;

return [
    'WorkCard' => [
        'xsd' => 'Card_v1.xsd',
        'documents' => [
            'WorkCard' => WorkCard::class,
        ],
    ],
    'WTO' => [
        'xsd' => 'WTO_v1.xsd',
        'documents' => [
            'DailyWorkTime' => DailyWorkTime::class,
            'DailyWorkTimeA' => DailyWorkTimeRetrospective::class,
            'DailyWorkTimeD' => DailyWorkTimeDrivers::class,
            'Overtime' => WorkTimeOvertime::class,
            'OvertimeA' => WorkTimeOvertimeRetrospective::class,
            'OvertimeD' => WorkTimeOvertimeDrivers::class,
        ],
    ],
    'WTOLeave' => [
        'xsd' => 'WTO_v2.xsd',
        'documents' => [
            'WorkTimeLeave' => WorkTimeLeave::class,
            'WorkTimeLeaveC' => WorkTimeLeaveCorrection::class,
        ],
    ],
    'WeeklyWorkTime' => [
        'xsd' => 'WTO_Weekly_v1.xsd',
        'documents' => [
            'WeeklyWorkTime' => WeeklyWorkTime::class,
        ],
    ],
    'E3N' => [
        'xsd' => 'E3N_v1.xsd',
        'documents' => [
            'E3N' => HiringNew::class,
        ],
    ],
    'E3M' => [
        'xsd' => 'E3M_v1.xsd',
        'documents' => [
            'E3M' => HiringModification::class,
        ],
    ],
    'E3D' => [
        'xsd' => 'E3D_v1.xsd',
        'documents' => [
            'E3D' => HiringDeletion::class,
        ],
    ],
    'E3PD' => [
        'xsd' => 'E3PD_v1.xsd',
        'documents' => [
            'E3PD' => HiringWithLending::class,
        ],
    ],
    'E5N' => [
        'xsd' => 'E5N_v1.xsd',
        'documents' => [
            'E5N' => VoluntaryResignation::class,
        ],
    ],
    'E5O' => [
        'xsd' => 'E5O_v1.xsd',
        'documents' => [
            'E5O' => ResignationNotification::class,
        ],
    ],
    'E5AO' => [
        'xsd' => 'E5AO_v1.xsd',
        'documents' => [
            'E5AO' => ResignationAfterNotification::class,
        ],
    ],
    'E5D' => [
        'xsd' => 'E5D_v1.xsd',
        'documents' => [
            'E5D' => TerminationByDeath::class,
        ],
    ],
    'E5E' => [
        'xsd' => 'E5E_v1.xsd',
        'documents' => [
            'E5E' => VoluntaryExitCompensation::class,
        ],
    ],
    'E5S' => [
        'xsd' => 'E5S_v1.xsd',
        'documents' => [
            'E5S' => RetirementVoluntary::class,
        ],
    ],
    'E5DS' => [
        'xsd' => 'E5DS_v1.xsd',
        'documents' => [
            'E5DS' => RetirementMandatory::class,
        ],
    ],
    'E6NXP' => [
        'xsd' => 'E6NXP_v1.xsd',
        'documents' => [
            'E6NXP' => DismissalWithoutNotice::class,
        ],
    ],
    'E6NMP' => [
        'xsd' => 'E6NMP_v1.xsd',
        'documents' => [
            'E6NMP' => DismissalWithNotice::class,
        ],
    ],
    'E6SXP' => [
        'xsd' => 'E6SXP_v1.xsd',
        'documents' => [
            'E6SXP' => RetirementDismissal::class,
        ],
    ],
    'E6LD' => [
        'xsd' => 'E6LD_v1.xsd',
        'documents' => [
            'E6LD' => EndOfLoan::class,
        ],
    ],
    'E6LT' => [
        'xsd' => 'E6LT_v1.xsd',
        'documents' => [
            'E6LT' => TrialPeriodTermination::class,
        ],
    ],
    'E6M' => [
        'xsd' => 'E6M_v1.xsd',
        'documents' => [
            'E6M' => Transfer::class,
        ],
    ],
    'E7N' => [
        'xsd' => 'E7N_v1.xsd',
        'documents' => [
            'E7N' => FixedTermTermination::class,
        ],
    ],
    'MA' => [
        'xsd' => 'MA_v1.xsd',
        'documents' => [
            'MA' => EmploymentModification::class,
        ],
    ],
    'MAD' => [
        'xsd' => 'MAD_v1.xsd',
        'documents' => [
            'MAD' => BorrowedEmploymentModification::class,
        ],
    ],
    'E12' => [
        'xsd' => 'E12_v1.xsd',
        'documents' => [
            'E12' => ConstructionWorkDeclaration::class,
        ],
    ],
    'E12Ap' => [
        'xsd' => 'E12Apografiko_v1.xsd',
        'documents' => [
            'E12Ap' => ConstructionWorkCensus::class,
        ],
    ],
    'SixthDay' => [
        'xsd' => 'SixthDay_v2.xsd',
        'documents' => [
            'SixthDay' => SixthDay::class,
        ],
    ],
    'ExProan' => [
        'xsd' => 'ExProan.xsd',
        'documents' => [
            'ExProan' => PreAnnouncementExemption::class,
        ],
    ],
    'Internship' => [
        'xsd' => 'E35_v3.xsd',
        'documents' => [
            'Internship' => Internship::class,
            'InternshipCorrection' => InternshipCorrection::class,
        ],
    ],
    'E35D' => [
        'xsd' => 'E35D_v1.xsd',
        'documents' => [
            'InternshipTermination' => InternshipTermination::class,
        ],
    ],
    'E35T' => [
        'xsd' => 'E35T_v1.xsd',
        'documents' => [
            'InternshipScheduleChange' => InternshipScheduleChange::class,
        ],
    ],
    'E13' => [
        'xsd' => 'E13_v1.xsd',
        'documents' => [
            'E13' => MotorcycleUse::class,
        ],
    ],
    'FastAna' => [
        'xsd' => 'FastAna_v1.xsd',
        'documents' => [
            'FastAna' => UrgentHiring::class,
            'FastAnaC' => UrgentHiringCorrection::class,
        ],
    ],
    'E31' => [
        'xsd' => 'E31_v1.xsd',
        'documents' => [
            'E31' => CommunityProgramEmployment::class,
            'E31C' => CommunityProgramEmploymentCorrection::class,
        ],
    ],
    'E32' => [
        'xsd' => 'E32_v1.xsd',
        'documents' => [
            'E32' => TheoreticalTraining::class,
            'E32C' => TheoreticalTrainingCorrection::class,
        ],
    ],
    'E14' => [
        'xsd' => 'E14_v1.xsd',
        'documents' => [
            'ParentalLeave' => ParentalLeave::class,
            'ParentalLeaveCorrection' => ParentalLeaveCorrection::class,
        ],
    ],
];
