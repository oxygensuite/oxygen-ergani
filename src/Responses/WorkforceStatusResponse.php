<?php

namespace OxygenSuite\OxygenErgani\Responses;

use DateTimeInterface;

class WorkforceStatusResponse extends Response
{
    // Employee identification
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
    public ?string $doy;
    public ?string $unemploymentCode;
    public ?string $amIka;
    public ?string $amka;
    public ?string $minorBookNumber;

    // Identity document
    public ?string $idType;
    public ?string $idNumber;
    public ?string $idIssuingAuthority;
    public ?DateTimeInterface $idIssueDate;
    public ?DateTimeInterface $idExpiryDate;

    // Residence permits
    public ?string $residencePermitInstNumber;
    public ?string $residencePermitApNumber;
    public ?string $residencePermitVisaNumber;

    // Employment details
    public ?int $branchAa;
    public ?DateTimeInterface $effectiveDate;
    public ?DateTimeInterface $hiringDate;
    public ?string $specialtyDescription;
    public ?string $characterization;
    public ?string $step;
    public ?string $weekDays;
    public ?int $experienceYears;
    public ?string $employmentRelation;
    public ?string $employmentStatus;
    public ?string $weeklyHours;
    public ?string $schedule;
    public ?string $breakInfo;
    public ?string $workLocation;
    public ?string $workLocationComments;
    public ?string $salaryPaymentTiming;
    public ?string $unpredictableSchedule;
    public ?string $mandatoryTraining;
    public ?string $collectiveAgreement;
    public ?string $collectiveAgreementComments;
    public ?string $grossSalary;
    public ?string $hourlyWage;
    public ?string $primaryInsurance;
    public ?string $supplementaryInsurance;
    public ?string $additionalInsuranceBenefits;
    public ?string $trialPeriod;
    public ?string $educationLevel;

    // Digital work time organization
    public ?string $digitalWorkTimeOrganization;
    public ?string $fullEmploymentHours;
    public ?int $breakMinutes;
    public ?string $breakWithinSchedule;
    public ?string $workingCard;
    public ?int $flexibleArrivalMinutes;

    // Last modification
    public ?DateTimeInterface $lastModifiedDate;

    protected function processData(): void
    {
        // Employee identification
        $this->afm = $this->string('afm');
        $this->lastName = $this->string('Eponimo');
        $this->firstName = $this->string('Onoma');
        $this->fatherName = $this->string('OnomaPatera');
        $this->motherName = $this->string('OnomaMiteras');
        $this->birthDate = $this->date('BirthDate');
        $this->sex = $this->string('Sex');
        $this->nationality = $this->string('Nationality');
        $this->maritalStatus = $this->string('MaritalStatus');
        $this->childrenCount = $this->int('NumChildren');
        $this->doy = $this->string('Doy');
        $this->unemploymentCode = $this->string('CodeAnergias');
        $this->amIka = $this->string('AmIka');
        $this->amka = $this->string('Amka');
        $this->minorBookNumber = $this->string('ArVivliouAnilikou');

        // Identity document
        $this->idType = $this->string('TyposTaytotitas');
        $this->idNumber = $this->string('ArTaytotitas');
        $this->idIssuingAuthority = $this->string('EkdousaArxi');
        $this->idIssueDate = $this->date('DateEkdosis');
        $this->idExpiryDate = $this->date('DateLixis');

        // Residence permits
        $this->residencePermitInstNumber = $this->string('ResPermitInstAr');
        $this->residencePermitApNumber = $this->string('ResPermitApAr');
        $this->residencePermitVisaNumber = $this->string('ResPermitVisaAr');

        // Employment details
        $this->branchAa = $this->int('PararthmaAa');
        $this->effectiveDate = $this->date('DateFrom');
        $this->hiringDate = $this->date('DateProslipsis');
        $this->specialtyDescription = $this->string('Eidikothta');
        $this->characterization = $this->string('asXaraktirismos');
        $this->step = $this->string('Step');
        $this->weekDays = $this->string('WeekDays');
        $this->experienceYears = $this->int('Proipiresia');
        $this->employmentRelation = $this->string('SxesiApasxolisis');
        $this->employmentStatus = $this->string('KathestosApasxolisis');
        $this->weeklyHours = $this->string('WeekHours');
        $this->schedule = $this->string('Orario');
        $this->breakInfo = $this->string('Dialeimma');
        $this->workLocation = $this->string('ToposErgasias');
        $this->workLocationComments = $this->string('ToposErgasiasComments');
        $this->salaryPaymentTiming = $this->string('XronosKatabolisApodoxwn');
        $this->unpredictableSchedule = $this->string('MhProblepsimoProgrammaErgasias');
        $this->mandatoryTraining = $this->string('IpoxreotikiKatartisi');
        $this->collectiveAgreement = $this->string('EfarmosteaSyllogikiSymbasi');
        $this->collectiveAgreementComments = $this->string('EfarmosteaSyllogikiSymbasiComments');
        $this->grossSalary = $this->string('Apodoxes');
        $this->hourlyWage = $this->string('HourApodoxes');
        $this->primaryInsurance = $this->string('KyriaAsfalisi');
        $this->supplementaryInsurance = $this->string('EpikourikiAsfalisi');
        $this->additionalInsuranceBenefits = $this->string('ProsthetesAsfalistikesParoxes');
        $this->trialPeriod = $this->string('TrialPeriod');
        $this->educationLevel = $this->string('EpipedoMorfosis');

        // Digital work time organization
        $this->digitalWorkTimeOrganization = $this->string('WorkingTimeDigitalOrganization');
        $this->fullEmploymentHours = $this->string('FullEmploymentHours');
        $this->breakMinutes = $this->int('DialeimmaMinutes');
        $this->breakWithinSchedule = $this->string('DialeimmaEntosWrariou');
        $this->workingCard = $this->string('WorkingCard');
        $this->flexibleArrivalMinutes = $this->int('EueliktoWrario');

        // Last modification
        $this->lastModifiedDate = $this->date('LastModifiedDate');
    }
}
