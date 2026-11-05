# Practical Training Start (E3.3)

The E3.3 form announces the start of the practical training (πρακτική άσκηση) that follows the theoretical training of a program beneficiary ([E3.2](/guide/theoretical-training)). It is submitted by the host business; the theoretical training provider is identified through the `setTheory*()` fields.

## Overview

| Property | Value |
|----------|-------|
| Document Classes | `PracticalTraining`, `PracticalTrainingCorrection` |
| Action Codes | `WebE33` (declaration), `WebE33C` (correction) |
| Declaration Model | `PracticalTrainingDeclaration` |
| Schema | `E33_v1.xsd` (root `AnaggeliesE33` / `AnaggeliaE33`) |

::: warning Availability
As of November 2026 `WebE33` / `WebE33C` are accepted by **production**. The **trial** environment does not have them deployed yet and rejects them with `XSD File not found` / `Type: ... not valid`.
:::

## Basic Usage

```php
use OxygenSuite\OxygenErgani\Enums\MaritalStatus;
use OxygenSuite\OxygenErgani\Enums\Sex;
use OxygenSuite\OxygenErgani\Http\Documents\Training\PracticalTraining;
use OxygenSuite\OxygenErgani\Models\Training\PracticalTrainingDeclaration;

$declaration = PracticalTrainingDeclaration::make()
    // Submission & host business
    ->setBranchCode(0)
    ->setDypaServiceCode('101213')
    ->setLaborInspectionCode('24010')
    ->setInvitationCode('002')
    ->setMainActivityCode('7022')
    ->setBranchActivityCode('7022')
    ->setMunicipalityCode('91860101')
    ->setBranchWorkforce(men: 12, women: 8, minors: 0)
    // Beneficiary
    ->setLastName('ΠΑΠΑΔΟΠΟΥΛΟΣ')
    ->setFirstName('ΓΕΩΡΓΙΟΣ')
    ->setFatherName('ΝΙΚΟΛΑΟΣ')
    ->setMotherName('ΜΑΡΙΑ')
    ->setSex(Sex::MALE)
    ->setBirthDate('08/09/2000')
    ->setNationality('048')
    ->setIdType('ΔΑΤ')
    ->setIdNumber('ΑΒ123456')
    ->setMaritalStatus(MaritalStatus::SINGLE)
    ->setNumberOfChildren(0)
    ->setAfm('123456789')
    ->setAmka('08090012345')
    ->setEducationLevel('1')
    // Practical training
    ->setPlacementDate('15/09/2025')
    ->setPlacementTime('09:00')
    ->setScheduleDescription('ΔΕΥΤΕΡΑ - ΠΑΡΑΣΚΕΥΗ 09:00 - 17:00')
    ->setWeeklyHours('40,0')                       // free text
    ->setScheduleAttached(false)
    ->setReplacement(false)
    ->setSpecialtyCode('212208')
    ->setFixedTermPeriod('15/09/2025', '30/11/2025') // f_orismenou_apo / f_orismenou_ews
    // Theoretical training provider
    ->setTheoryProviderAfm('000000001')
    ->setTheoryProviderBranch(0)
    ->setLegalRepresentativeAfm('078776545')
    ->withDefaults(); // send every remaining optional field as an empty string

$response = (new PracticalTraining())->handle($declaration);
```

::: tip All fields are sent
ERGANI expects every E3.3 element to be present, even when empty. Call `withDefaults()` after setting your values to fill the remaining fields with empty strings.
:::

### Via Ergani Facade

```php
use OxygenSuite\OxygenErgani\Ergani;

$ergani = new Ergani($accessToken);

$responses = $ergani->sendPracticalTraining($declaration);
```

## Helpers

```php
// Branch workforce counts (f_mpa_men / f_mpa_women / f_mpa_minors)
$declaration->setBranchWorkforce(12, 8);         // minors default to 0

// Practical training period (f_orismenou_apo / f_orismenou_ews)
$declaration->setFixedTermPeriod('15/09/2025', '30/11/2025');

// Theoretical training provider (EOPPEP code, category, AFM, branch, Kallikratis)
$declaration
    ->setTheoryEopepCode('1234567')
    ->setTheoryProviderCategory('001')
    ->setTheoryProviderAfm('000000001')
    ->setTheoryProviderBranch(0)
    ->setTheoryProviderMunicipalityCode('91860101');
```

::: info Weekly hours are free text
In E3.3, `f_week_hours` is a free-text field (max 200 characters), so `setWeeklyHours()` accepts `int|string` and sends the value as-is (e.g. `'40,0'`). The E3.2 form uses a Greek decimal instead.
:::

## Corrections (WebE33C)

```php
use OxygenSuite\OxygenErgani\Http\Documents\Training\PracticalTrainingCorrection;

$declaration->setRelatedSubmission(               // f_protocol_sx / f_date_ypovolis_sx
    $original[0]->protocol,
    $original[0]->submissionDate,
);

$response = (new PracticalTrainingCorrection())->handle($declaration);

// or
$responses = $ergani->sendPracticalTrainingCorrection($declaration);
```

## Field Reference

Every element must be present in the payload; "Required" marks the elements whose XSD pattern does not accept an empty value.

| Method | API Field | Type | Required | Description |
|--------|-----------|------|----------|-------------|
| `setBranchCode()` | `f_aa_pararthmatos` | int\|string | Yes | Branch sequence number (0 for HQ) |
| `setRelatedProtocol()` | `f_protocol_sx` | string | No | Protocol of the original declaration (corrections only). |
| `setRelatedDate()` | `f_date_ypovolis_sx` | DateTime\|string | No | Submission date of the original declaration (corrections only). |
| `setDypaServiceCode()` | `f_ypiresia_oaed` | string | Yes | DYPA service code. |
| `setLaborInspectionCode()` | `f_ypiresia_sepe` | string | Yes | Labour inspectorate service code (5 digits). |
| `setInvitationCode()` | `f_programma_voucher` | string | No | Invitation (call) code (3 digits). |
| `setEmployerOrganization()` | `f_mer_organization` | string | No | Employer organization |
| `setMainActivityCode()` | `f_mer_stakod1` | string | Yes | Main activity code (KAD, 4 digits). |
| `setSecondaryActivityCode1()` | `f_mer_stakod2` | string | No | Secondary activity code1 |
| `setSecondaryActivityCode2()` | `f_mer_stakod3` | string | No | Secondary activity code2 |
| `setSecondaryActivityCode3()` | `f_mer_stakod4` | string | No | Secondary activity code3 |
| `setSecondaryActivityCode4()` | `f_mer_stakod5` | string | No | Secondary activity code4 |
| `setBranchActivityCode()` | `f_mpa_stakod` | string | Yes | Activity code (KAD) of the branch. |
| `setMunicipalityCode()` | `f_mpa_kallikratis` | string | Yes | Kallikratis code of the branch (8 digits). |
| `setBranchMenCount()` | `f_mpa_men` | int\|string | Yes | Number of men employed at the branch. |
| `setBranchWomenCount()` | `f_mpa_women` | int\|string | Yes | Number of women employed at the branch. |
| `setBranchMinorsCount()` | `f_mpa_minors` | int\|string | Yes | Number of minors employed at the branch. |
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
| `setAmka()` | `f_mea_amka` | string | No | Beneficiary social security number (AMKA) |
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
| `setPlacementDate()` | `f_date_proslipsis` | DateTime\|string | Yes | First day of practical training at the business (DD/MM/YYYY). |
| `setPlacementTime()` | `f_date_time_proslipsis` | string | No | Start time on the first day (HH:MM). |
| `setScheduleDescription()` | `f_orario` | string | No | Working / training days and hours as free text (max 200 characters). |
| `setWeeklyHours()` | `f_week_hours` | int\|string | No | Weekly practical training hours as free text (e.g. "40,0"). |
| `setBreakDescription()` | `f_orario_dialeima` | string | No | Break hours as free text (max 200 characters). |
| `setScheduleAttached()` | `f_wres_external` | string\|bool | No | Whether the training days and hours are given in an attached file. |
| `setReplacement()` | `f_replace_programa` | string\|bool | No | Whether the beneficiary replaces another program beneficiary. |
| `setSpecialtyCode()` | `f_eidikothta` | string | Yes | Specialty code (up to 6 digits, see ParameterLookup::SPECIALTY). |
| `setStartDate()` | `f_orismenou_apo` | DateTime\|string | Yes | Practical training period start date (DD/MM/YYYY). |
| `setEndDate()` | `f_orismenou_ews` | DateTime\|string | Yes | Practical training period end date (DD/MM/YYYY). |
| `setComments()` | `f_comments` | string | No | Comments |
| `setTheoryEopepCode()` | `f_theory_eopep_code` | string | No | EOPPEP code of the theoretical training provider. |
| `setTheoryProviderCategory()` | `f_theory_edu_agency_category` | string | No | Category code of the theoretical training provider. |
| `setTheoryProviderAfm()` | `f_theory_mer_afm` | string | Yes | AFM of the theoretical training provider. |
| `setTheoryProviderBranch()` | `f_theory_mpa_aa` | int\|string | Yes | Branch number of the theoretical training provider (0 for HQ). |
| `setTheoryProviderMunicipalityCode()` | `f_theory_mpa_kallikratis` | string | No | Kallikratis code of the theoretical training provider's branch (8 digits). |
| `setLegalRepresentativeAfm()` | `f_mek_afm` | string | Yes | AFM of the responsible provider / legal representative. |
| `setFormFile()` | `f_file` | string | No | Signed form (base64-encoded PDF). |
| `setForeignFile()` | `f_foreign_file` | string | No | Third-country national labour-market access documents (base64-encoded PDF). |
| `setMinorFile()` | `f_young_file` | string | No | Minor worker book (base64-encoded PDF). |

## Response Handling

```php
$response = (new PracticalTraining())->handle($declaration);

foreach ($response as $result) {
    echo $result->id;              // Unique submission ID
    echo $result->protocol;        // Protocol number
    echo $result->submissionDate->format('d/m/Y H:i:s');
}
```

## Retrieve PDF

```php
$pdfBase64 = (new PracticalTraining())->pdf(
    $response[0]->protocol,
    $response[0]->submissionDate
);

file_put_contents('practical-training.pdf', base64_decode($pdfBase64));
```

## Testing with Factories

```php
use OxygenSuite\OxygenErgani\Models\Training\PracticalTrainingDeclaration;

$declaration = PracticalTrainingDeclaration::factory()->make();

$declaration = PracticalTrainingDeclaration::factory()
    ->forBranch(1)
    ->withTheoryProvider('000000001', eopepCode: '1234567', branch: 0)
    ->asCorrection('ΑΚ123', '01/10/2026')
    ->make();
```

## See Also

- [Theoretical Training (E3.2)](/guide/theoretical-training) - The theoretical training that precedes the practice
- [Community-Benefit Program Employment (E3.1)](/guide/community-program) - Shares the beneficiary and provider fields
- [Apprenticeship (E3.4)](/guide/apprenticeship)
- [Services & Queries](/guide/services) - Parameter lookups
