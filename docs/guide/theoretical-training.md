# Theoretical Training Start (E3.2)

The E3.2 form announces the start of theoretical training (θεωρητική κατάρτιση) for a program beneficiary, optionally followed by practical training at a business.

## Overview

| Property | Value |
|----------|-------|
| Document Classes | `TheoreticalTraining`, `TheoreticalTrainingCorrection` |
| Action Codes | `WebE32` (declaration), `WebE32C` (correction) |
| Declaration Model | `TheoreticalTrainingDeclaration` |
| Schema | `E32_v1.xsd` (root `AnaggeliesE32` / `AnaggeliaE32`) |

## Basic Usage

```php
use OxygenSuite\OxygenErgani\Enums\MaritalStatus;
use OxygenSuite\OxygenErgani\Enums\Sex;
use OxygenSuite\OxygenErgani\Http\Documents\Training\TheoreticalTraining;
use OxygenSuite\OxygenErgani\Models\Training\TheoreticalTrainingDeclaration;

$declaration = TheoreticalTrainingDeclaration::make()
    // Submission & training provider
    ->setBranchCode(0)
    ->setDypaServiceCode('101213')
    ->setLaborInspectionCode('24010')
    ->setProgramCode('34')
    ->setInvitationCode('002')
    ->setMainActivityCode('8559')
    ->setBranchActivityCode('8559')
    ->setMunicipalityCode('91860101')
    ->setEopepCode('1234567')
    ->setTrainingProviderCategory('001')
    // Beneficiary
    ->setLastName('ΠΑΠΑΔΟΠΟΥΛΟΣ')
    ->setFirstName('ΓΕΩΡΓΙΟΣ')
    ->setFatherName('ΝΙΚΟΛΑΟΣ')
    ->setMotherName('ΜΑΡΙΑ')
    ->setSex(Sex::MALE)
    ->setBirthDate('08/09/1990')
    ->setNationality('048')
    ->setIdType('ΔΑΤ')
    ->setIdNumber('ΑΒ123456')
    ->setMaritalStatus(MaritalStatus::SINGLE)
    ->setNumberOfChildren(0)
    ->setAfm('123456789')
    ->setAmka('08099012345')
    ->setEducationLevel('5')
    // Theoretical training
    ->setTrainingPeriod('15/09/2025', '31/10/2025')
    ->setWeeklyHours(20.0)
    ->setScheduleDescription('ΔΕΥΤΕΡΑ - ΠΑΡΑΣΚΕΥΗ 17:00 - 21:00')
    ->setTheoryHours(80)
    ->setTheoryProgram('001')
    ->setTheoryDescription('ΨΗΦΙΑΚΕΣ ΔΕΞΙΟΤΗΤΕΣ')
    ->setStatusAtApplication('001')
    ->setStatusBeforeApplication('002')
    ->setUnemploymentMonths(2)
    ->setLegalRepresentativeAfm('078776545')
    ->withDefaults(); // send every remaining optional field as an empty string

$response = (new TheoreticalTraining())->handle($declaration);
```

::: tip All fields are sent
ERGANI expects every E3.2 element to be present, even when empty. Call `withDefaults()` after setting your values to fill the remaining fields with empty strings.
:::

### Via Ergani Facade

```php
use OxygenSuite\OxygenErgani\Ergani;

$ergani = new Ergani($accessToken);

$responses = $ergani->sendTheoreticalTraining($declaration);
```

## Practical Training

When the program continues with practical training at a business, fill in the practice fields:

```php
$declaration
    ->setPracticeHours(200)
    ->setInsuranceDays(25)
    ->setDailyAllowance(30.00)                   // f_daily_apodoxes → "30,00"
    ->setPracticeCompanyName('ΕΠΙΧΕΙΡΗΣΗ Α.Ε.')
    ->setPracticeCompanyAfm('999999999')
    ->setPracticeCompanyTaxOffice('1101')
    ->setPracticeCompanyAme('1234567')
    ->setPracticeBranchAddress('ΕΡΜΟΥ 1')
    ->setPracticeBranchMunicipalityCode('91860101')
    ->setPracticeBranchPostalCode('10563')
    ->setPracticePeriod('01/11/2025', '31/12/2025');
```

## Corrections (WebE32C)

```php
use OxygenSuite\OxygenErgani\Http\Documents\Training\TheoreticalTrainingCorrection;

$declaration
    ->setRelatedProtocol($original[0]->protocol)        // f_protocol_sx
    ->setRelatedDate($original[0]->submissionDate);     // f_date_ypovolis_sx

$response = (new TheoreticalTrainingCorrection())->handle($declaration);

// or
$responses = $ergani->sendTheoreticalTrainingCorrection($declaration);
```

## Field Reference

"Required" reflects the elements the XSD marks as non-nillable.

| Method | API Field | Type | Required | Description |
|--------|-----------|------|----------|-------------|
| `setBranchCode()` | `f_aa_pararthmatos` | int\|string | Yes | Branch sequence number (0 for HQ) |
| `setRelatedProtocol()` | `f_protocol_sx` | string | No | Protocol of the original declaration (corrections only). |
| `setRelatedDate()` | `f_date_ypovolis_sx` | DateTime\|string | No | Submission date of the original declaration (corrections only). |
| `setDypaServiceCode()` | `f_ypiresia_oaed` | string | Yes | DYPA service code. |
| `setLaborInspectionCode()` | `f_ypiresia_sepe` | string | Yes | Labour inspectorate service code (5 digits). |
| `setProgramCode()` | `f_programma_voucher_code` | string | No | Program code |
| `setInvitationCode()` | `f_programma_voucher` | string | No | Invitation (call) code (3 digits). |
| `setEmployerOrganization()` | `f_mer_organization` | string | No | Employer organization |
| `setMainActivityCode()` | `f_mer_stakod1` | string | Yes | Main activity code (KAD, 4 digits). |
| `setSecondaryActivityCode1()` | `f_mer_stakod2` | string | No | Secondary activity code1 |
| `setSecondaryActivityCode2()` | `f_mer_stakod3` | string | No | Secondary activity code2 |
| `setSecondaryActivityCode3()` | `f_mer_stakod4` | string | No | Secondary activity code3 |
| `setSecondaryActivityCode4()` | `f_mer_stakod5` | string | No | Secondary activity code4 |
| `setBranchActivityCode()` | `f_mpa_stakod` | string | Yes | Activity code (KAD) of the branch. |
| `setMunicipalityCode()` | `f_mpa_kallikratis` | string | Yes | Kallikratis code of the branch (8 digits). |
| `setEopepCode()` | `f_eopep_code` | string | Yes | Training provider code (EOPPEP). |
| `setTrainingProviderCategory()` | `f_edu_agency_category` | string | Yes | Training provider category code (3 digits). |
| `setLastName()` | `f_mea_eponimo` | string | Yes | Last name |
| `setFirstName()` | `f_mea_onoma` | string | Yes | First name |
| `setFatherLastName()` | `f_mea_eponimo_patera` | string | No | Father last name |
| `setFatherName()` | `f_mea_onoma_patera` | string | Yes | Father name |
| `setMotherLastName()` | `f_mea_eponimo_miteras` | string | No | Mother last name |
| `setMotherName()` | `f_mea_onoma_miteras` | string | Yes | Mother name |
| `setSex()` | `f_mea_sex` | Sex\|string\|int | Yes | 0=Male, 1=Female (or use Sex enum) |
| `setBirthPlace()` | `f_mea_birth_city` | string | No | Birth place |
| `setBirthDate()` | `f_mea_date_birth` | DateTime\|string | Yes | Birth date (DD/MM/YYYY) |
| `setNationality()` | `f_mea_nationality` | string | Yes | Nationality code (3 digits, see ParameterLookup::NATIONALITY). |
| `setIdType()` | `f_mea_typos_taytotitas` | string | Yes | Identity document type (see ParameterLookup::ID_TYPE). |
| `setIdNumber()` | `f_mea_ar_taytotitas` | string | Yes | Identity document number |
| `setIdIssuingAuthority()` | `f_mea_ekdousa` | string | No | Id issuing authority |
| `setIdIssueDate()` | `f_mea_date_ekdosis` | DateTime\|string | No | Identity document issue date (DD/MM/YYYY) |
| `setIdExpiryDate()` | `f_mea_date_ekdosis_lixi` | DateTime\|string | No | Identity document expiry date (DD/MM/YYYY) |
| `setResidencePermit()` | `f_mea_res_permit` | string\|bool | No | Whether the beneficiary holds a residence or work permit. |
| `setResidencePermitType()` | `f_mea_res_permit_type` | string | No | Residence permit type (see ParameterLookup::RESIDENCE_PERMIT). |
| `setResidencePermitNumber()` | `f_mea_res_permit_ar` | string | No | Residence permit number |
| `setResidencePermitExpiry()` | `f_mea_res_permit_lixi` | DateTime\|string | No | Residence permit expiry date (DD/MM/YYYY) |
| `setMaritalStatus()` | `f_mea_marital_status` | MaritalStatus\|string\|int | Yes | 0=Single, 1=Married, 2=Divorced, 3=Widowed (or use MaritalStatus enum) |
| `setNumberOfChildren()` | `f_mea_ar_teknon` | int\|string | Yes | Number of children |
| `setAfm()` | `f_mea_afm` | string | Yes | Beneficiary tax ID (AFM) |
| `setTaxOffice()` | `f_mea_doy` | string | No | Tax office code (4 digits, see ParameterLookup::DOY). |
| `setInsuranceAgency()` | `f_mea_insurance_agency` | string | No | Main insurance agency code (see ParameterLookup::PRIMARY_INSURANCE). |
| `setAmika()` | `f_mea_am_ika` | string | No | e-EFKA insurance registry number. |
| `setAmka()` | `f_mea_amka` | string | Yes | Beneficiary social security number (AMKA) |
| `setUnemploymentCardNumber()` | `f_mea_code_anergias` | string | No | Unemployment card number |
| `setMinorBookNumber()` | `f_mea_ar_vivliou_anilikou` | string | No | Minor book number |
| `setAddress()` | `f_mea_dieythinsi` | string | No | Address |
| `setResidenceMunicipalityCode()` | `f_mea_kallikratis` | string | No | Kallikratis code of the beneficiary residence (8 digits). |
| `setPostalCode()` | `f_mea_tk` | string | No | Postal code |
| `setPhone()` | `f_mea_til` | string | No | Phone |
| `setFax()` | `f_mea_fax` | string | No | Fax |
| `setEmail()` | `f_mea_email` | string | No | E-mail |
| `setEducationLevel()` | `f_mea_epipedo_morfosis` | string | Yes | Education level code (see ParameterLookup::EDUCATION_LEVEL). |
| `setVocationalTrainingSubject()` | `f_mea_practical_education` | string | No | Vocational training subject code (3 digits). |
| `setVoucherProgram()` | `f_mea_voucher` | string\|bool | No | Whether this is a voucher program. |
| `setKayasCode()` | `f_mea_kayas` | string | No | K.A.Y.A.S. code. |
| `setTrainingStartDate()` | `f_date_proslipsis` | DateTime\|string | Yes | Theoretical training start date (DD/MM/YYYY) |
| `setWeeklyHours()` | `f_week_hours` | float | Yes | Weekly theoretical training hours. |
| `setScheduleDescription()` | `f_orario` | string | No | Working / training days and hours as free text (max 200 characters). |
| `setBreakDescription()` | `f_orario_dialeima` | string | No | Break hours as free text (max 200 characters). |
| `setTheoryHours()` | `f_total_hours_theory` | int\|string | No | Total theoretical training hours in the program. |
| `setPracticeHours()` | `f_total_hours_practice` | int\|string | No | Total practical training hours in the program. |
| `setInsuranceDays()` | `f_total_insurance_days` | int\|string | No | Insurance days for theoretical and practical training. |
| `setDailyAllowance()` | `f_daily_apodoxes` | float | No | Gross daily training allowance. |
| `setTheoryProgram()` | `f_theory_education` | string | Yes | Theoretical training program code. |
| `setTheoryDescription()` | `f_theory_description` | string | No | Theory description |
| `setTheoryCode()` | `f_theory_code` | string | No | Theory code |
| `setTrainingEndDate()` | `f_orismenou_ews` | DateTime\|string | Yes | Theoretical training end date (DD/MM/YYYY) |
| `setStatusAtApplication()` | `f_benefiter_status_after` | string | Yes | Beneficiary status when applying to the program (3-digit code). |
| `setUnemploymentMonths()` | `f_months_unempoyment` | int\|string | No | Unemployment months |
| `setStatusBeforeApplication()` | `f_benefiter_status_before` | string | Yes | Beneficiary status one month before applying (3-digit code). |
| `setComments()` | `f_comments` | string | No | Comments |
| `setPracticeCompanyName()` | `f_practice_mer_eponimia` | string | No | Practice company name |
| `setPracticeCompanyAfm()` | `f_practice_mer_afm` | string | No | AFM of the practical training business |
| `setPracticeCompanyTaxOffice()` | `f_practice_mer_doy` | string | No | Tax office code of the practical training business |
| `setPracticeCompanyAme()` | `f_practice_mer_ame` | string | No | Employer registry number (AME) of the business. |
| `setPracticeCompanyWithoutAme()` | `f_practice_mer_no_ame` | string\|bool | No | Whether the business has no AME. |
| `setPracticeBranchAddress()` | `f_practice_mpa_address` | string | No | Practice branch address |
| `setPracticeBranchMunicipalityCode()` | `f_practice_mpa_kallikratis` | string | No | Practice branch municipality code |
| `setPracticeBranchPostalCode()` | `f_practice_mpa_tk` | string | No | Practice branch postal code |
| `setPracticeBranchPhone()` | `f_practice_mpa_til` | string | No | Practice branch phone |
| `setPracticeBranchFax()` | `f_practice_mpa_fax` | string | No | Practice branch fax |
| `setPracticeBranchEmail()` | `f_practice_mpa_email` | string | No | Practice branch email |
| `setPracticeDateFrom()` | `f_practice_date_from` | DateTime\|string | No | Practical training start date (DD/MM/YYYY) |
| `setPracticeDateTo()` | `f_practice_date_to` | DateTime\|string | No | Practical training end date (DD/MM/YYYY) |
| `setLegalRepresentativeAfm()` | `f_mek_afm` | string | Yes | AFM of the responsible provider / legal representative. |
| `setFormFile()` | `f_file` | string | No | Signed form (base64-encoded PDF). |
| `setForeignFile()` | `f_foreign_file` | string | No | Third-country national labour-market access documents (base64-encoded PDF). |
| `setMinorFile()` | `f_young_file` | string | No | Minor worker book (base64-encoded PDF). |

## Response Handling

```php
$response = (new TheoreticalTraining())->handle($declaration);

foreach ($response as $result) {
    echo $result->id;              // Unique submission ID
    echo $result->protocol;        // Protocol number
    echo $result->submissionDate->format('d/m/Y H:i:s');
}
```

## Retrieve PDF

```php
$pdfBase64 = (new TheoreticalTraining())->pdf(
    $response[0]->protocol,
    $response[0]->submissionDate
);

file_put_contents('theoretical-training.pdf', base64_decode($pdfBase64));
```

## Testing with Factories

```php
use OxygenSuite\OxygenErgani\Models\Training\TheoreticalTrainingDeclaration;

$declaration = TheoreticalTrainingDeclaration::factory()->make();

$declaration = TheoreticalTrainingDeclaration::factory()
    ->forBranch(1)
    ->withPractice(hours: 240, dailyAllowance: 32.50)
    ->asCorrection('ΑΚ123', '01/10/2026')
    ->make();
```

## See Also

- [Community-Benefit Program Employment (E3.1)](/guide/community-program) - Shares the beneficiary and provider fields
- [Internship (E3.5)](/guide/internship)
- [Services & Queries](/guide/services) - Parameter lookups
