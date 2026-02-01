<?php

namespace OxygenSuite\OxygenErgani\Responses;

use DateTimeInterface;

class EmployeeStatusResponse extends Response
{
    // Employer/Branch identifiers
    public ?int $employerId;
    public ?int $branchAa;
    public ?int $year;
    public ?int $month;

    // Employee identification
    public ?string $employeeType;
    public ?string $afm;
    public ?string $lastName;
    public ?string $firstName;
    public ?string $fatherName;
    public ?string $motherName;
    public ?DateTimeInterface $birthDate;
    public ?string $sex;
    public ?string $nationality;
    public ?string $maritalStatus;
    public ?int $childrenCount;
    public ?string $ama;
    public ?string $amka;
    public ?string $educationLevel;

    // Employment details
    public ?string $characterization;
    public ?string $employmentRelation;
    public ?string $employmentStatus;
    public ?string $specialty;
    public ?string $salary;
    public ?string $weeklyHours;
    public ?string $hourlyWage;
    public ?string $program;
    public ?string $responsible;
    public ?DateTimeInterface $hiringDate;

    // Work days
    public ?int $workDays;
    public ?int $remoteWorkDays;
    public ?int $restDays;
    public ?int $nonWorkingDays;

    // Leave days (day-based)
    public ?int $annualLeaveDays;
    public ?int $bloodDonationLeaveDays;
    public ?int $examLeaveDays;
    public ?int $unpaidLeaveDays;
    public ?int $maternityLeaveDays;
    public ?int $maternityProtectionDays;
    public ?int $paternityLeaveDays;
    public ?int $childCareLeaveDays;
    public ?int $parentalLeaveDays;
    public ?int $caregiverLeaveDays;
    public ?int $forceMajeureDays;
    public ?int $assistedReproductionLeaveDays;
    public ?int $prenatalExamLeaveDays;
    public ?int $marriageLeaveDays;
    public ?int $seriousChildIllnessLeaveDays;
    public ?int $childHospitalizationLeaveDays;
    public ?int $singleParentLeaveDays;
    public ?int $schoolPerformanceLeaveDays;
    public ?int $childIllnessLeaveDays;
    public ?int $violenceAbsenceDays;
    public ?int $sicknessDays;
    public ?int $disabilityLeaveDays;
    public ?int $bereavementLeaveDays;
    public ?int $minorStudentLeaveDays;
    public ?int $bloodTransfusionDays;
    public ?int $kanepGseeLeaveDays;
    public ?int $aidsLeaveDays;
    public ?int $flexibleWorkDays;
    public ?int $otherLeaveDays;

    // Leave (minute-based)
    public ?int $childCareMinutes;
    public ?int $parentalLeaveMinutes;
    public ?int $forceMajeureMinutes;
    public ?int $flexibleWorkMinutes;
    public ?int $prenatalExamMinutes;
    public ?int $schoolPerformanceMinutes;
    public ?int $otherLeaveMinutes;
    public ?int $totalMinutes;

    // Overtime
    public ?int $overtimeMinutes;
    public ?int $overtimeDays;

    // Work card
    public ?int $workCardDays;

    // Sunday/Holiday work
    public ?int $sundayHolidayDays;
    public ?int $sundayHolidayCardDays;

    // Insurance totals
    public ?int $totalInsuredLeaveDays;
    public ?int $totalInsuredSicknessDays;

    protected function processData(): void
    {
        // Employer/Branch identifiers
        $this->employerId = $this->int('f_ergodoti_id');
        $this->branchAa = $this->int('f_pararthma_aa');
        $this->year = $this->int('f_year');
        $this->month = $this->int('f_month');

        // Employee identification
        $this->employeeType = $this->string('f_ergazomenos_type');
        $this->afm = $this->string('f_afm');
        $this->lastName = $this->string('f_eponimo');
        $this->firstName = $this->string('f_onoma');
        $this->fatherName = $this->string('f_onoma_patera');
        $this->motherName = $this->string('f_onoma_miteras');
        $this->birthDate = $this->date('f_date_birth');
        $this->sex = $this->string('f_sex');
        $this->nationality = $this->string('f_nationality');
        $this->maritalStatus = $this->string('f_marital_status');
        $this->childrenCount = $this->int('f_ar_teknwn');
        $this->ama = $this->string('f_ama');
        $this->amka = $this->string('f_amka');
        $this->educationLevel = $this->string('f_education_level');

        // Employment details
        $this->characterization = $this->string('f_xarakthsismos');
        $this->employmentRelation = $this->string('f_sxesh_apasxolhshs');
        $this->employmentStatus = $this->string('f_kathestos');
        $this->specialty = $this->string('f_step');
        $this->salary = $this->string('f_apodoxes');
        $this->weeklyHours = $this->string('f_week_wres');
        $this->hourlyWage = $this->string('f_hour_apodoxes');
        $this->program = $this->string('f_programma');
        $this->responsible = $this->string('f_responsible');
        $this->hiringDate = $this->date('f_date_proslipsis');

        // Work days
        $this->workDays = $this->int('f_arithmos_hmerwn_ergasias');
        $this->remoteWorkDays = $this->int('f_arithmos_hmerwn_tilergasias');
        $this->restDays = $this->int('f_arithmos_hmerwn_anapaushs_repo');
        $this->nonWorkingDays = $this->int('f_arithmos_hmerwn_mh_ergasias');

        // Leave days (day-based)
        $this->annualLeaveDays = $this->int('f_arithmos_hmerwn_kanonikh_adeia');
        $this->bloodDonationLeaveDays = $this->int('f_arithmos_hmerwn_aimodotikh_adeia');
        $this->examLeaveDays = $this->int('f_arithmos_hmerwn_adeia_exetasewn');
        $this->unpaidLeaveDays = $this->int('f_arithmos_hmerwn_adeia_axoris_apodw');
        $this->maternityLeaveDays = $this->int('f_arithmos_hmerwn_adeia_mhrotitas');
        $this->maternityProtectionDays = $this->int('f_arithmos_hmerwn_eidikh_paroxh_prostasias_ths_mhrotitas');
        $this->paternityLeaveDays = $this->int('f_arithmos_hmerwn_adeia_patrotitas');
        $this->childCareLeaveDays = $this->int('f_arithmos_hmerwn_adeia_frontidas_paidiou');
        $this->parentalLeaveDays = $this->int('f_arithmos_hmerwn_gonikh_adeia');
        $this->caregiverLeaveDays = $this->int('f_arithmos_hmerwn_adeia_frontisti');
        $this->forceMajeureDays = $this->int('f_arithmos_hmerwn_ergasias_logo_anoteras_vias');
        $this->assistedReproductionLeaveDays = $this->int('f_arithmos_hmerwn_adeia_methodous_iatrikws_ipovothoumenhs_anaparagoghs');
        $this->prenatalExamLeaveDays = $this->int('f_arithmos_hmerwn_adeia_exetasewn_progennitikou_elegxou');
        $this->marriageLeaveDays = $this->int('f_arithmos_hmerwn_adeia_gamou');
        $this->seriousChildIllnessLeaveDays = $this->int('f_arithmos_hmerwn_adeia_logw_sovarwn_noshmatwn_twn_paidion');
        $this->childHospitalizationLeaveDays = $this->int('f_arithmos_hmerwn_adeia_logw_noshlias_paidion');
        $this->singleParentLeaveDays = $this->int('f_arithmos_hmerwn_adeia_monogoneikon_oikogeneion');
        $this->schoolPerformanceLeaveDays = $this->int('f_arithmos_hmerwn_adeia_parakolouthishs_sxolikhs_epidosis_paidiou');
        $this->childIllnessLeaveDays = $this->int('f_arithmos_hmerwn_adeia_astheneias_paidiou_h_allou_exartoumenou_melous');
        $this->violenceAbsenceDays = $this->int('f_arithmos_hmerwn_apousia_apo_ergasia_logo_vias_parenoxlisis');
        $this->sicknessDays = $this->int('f_arithmos_hmerwn_astheneias_anipaitiokolima_paroxhs_ergasias');
        $this->disabilityLeaveDays = $this->int('f_arithmos_hmerwn_adeia_amea');
        $this->bereavementLeaveDays = $this->int('f_arithmos_hmerwn_adeia_thanatos_syggeneous');
        $this->minorStudentLeaveDays = $this->int('f_arithmos_hmerwn_adeia_anhlikwn_spoudastwn');
        $this->bloodTransfusionDays = $this->int('f_arithmos_hmerwn_metaggiseis_aimatos_h_aimokatharsi');
        $this->kanepGseeLeaveDays = $this->int('f_arithmos_hmerwn_ekpaideytikh_adeia_foithtes_KANEP_GSEE');
        $this->aidsLeaveDays = $this->int('f_arithmos_hmerwn_aids');
        $this->flexibleWorkDays = $this->int('f_arithmos_hmerwn_eveliktes_rythmiseis_ergasias');
        $this->otherLeaveDays = $this->int('f_arithmos_hmerwn_alli_adeia');

        // Leave (minute-based)
        $this->childCareMinutes = $this->int('f_arithmos_hmerwn_frontida_paidiou_lepta');
        $this->parentalLeaveMinutes = $this->int('f_arithmos_hmerwn_gonikh_adeia_lepta');
        $this->forceMajeureMinutes = $this->int('f_arithmos_hmerwn_anoteras_vias_lepta');
        $this->flexibleWorkMinutes = $this->int('f_arithmos_hmerwn_eveliktes_rythmiseis_ergasias_lepta');
        $this->prenatalExamMinutes = $this->int('f_arithmos_hmerwn_exetaseis_progennitikou_lepta');
        $this->schoolPerformanceMinutes = $this->int('f_arithmos_hmerwn_parakolouthish_paidiou_lepta');
        $this->otherLeaveMinutes = $this->int('f_arithmos_hmerwn_alli_adeia_lepta');
        $this->totalMinutes = $this->int('f_arithmos_leptwn');

        // Overtime
        $this->overtimeMinutes = $this->int('f_lepta_yperorias');
        $this->overtimeDays = $this->int('f_arithmos_hmerwn_yperorias');

        // Work card
        $this->workCardDays = $this->int('f_arithmos_hmerwn_karta_ergasias');

        // Sunday/Holiday work
        $this->sundayHolidayDays = $this->int('f_arithmos_kyriakwn_psoxe');
        $this->sundayHolidayCardDays = $this->int('f_arithmos_kyriakwn_karta');

        // Insurance totals
        $this->totalInsuredLeaveDays = $this->int('f_synolo_hmerwn_adeias_asfalish');
        $this->totalInsuredSicknessDays = $this->int('f_synolo_hmerwn_astheneias_asfalish');
    }
}
