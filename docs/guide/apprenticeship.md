# Apprenticeship Start (E3.4)

The E3.4 form announces the start of an apprenticeship (μαθητεία) at a business for a student of a vocational school (e.g. EPAS / EPAL apprenticeship year).

## Overview

| Property | Value |
|----------|-------|
| Document Classes | `Apprenticeship`, `ApprenticeshipCorrection` |
| Action Codes | `WebE34` (declaration), `WebE34C` (correction) |
| Declaration Model | `ApprenticeshipDeclaration` |
| Schema | `E34_v1.xsd` (root `AnaggeliesE34` / `AnaggeliaE34`) |

::: warning Availability
As of November 2026 `WebE34` / `WebE34C` are accepted by **production**. The **trial** environment does not have them deployed yet and rejects them with `XSD File not found` / `Type: ... not valid`.
:::

## Basic Usage

```php
use OxygenSuite\OxygenErgani\Enums\MaritalStatus;
use OxygenSuite\OxygenErgani\Enums\Sex;
use OxygenSuite\OxygenErgani\Http\Documents\Apprenticeship\Apprenticeship;
use OxygenSuite\OxygenErgani\Models\Apprenticeship\ApprenticeshipDeclaration;

$declaration = ApprenticeshipDeclaration::make()
    // Submission & employer
    ->setBranchCode(0)
    ->setDypaServiceCode('101213')
    ->setLaborInspectionCode('24010')
    ->setProgramCode('001')
    ->setMainActivityCode('7022')
    ->setBranchActivityCode('7022')
    ->setMunicipalityCode('91860101')
    // Apprentice
    ->setLastName('ΠΑΠΑΔΟΠΟΥΛΟΣ')
    ->setFirstName('ΓΕΩΡΓΙΟΣ')
    ->setFatherName('ΝΙΚΟΛΑΟΣ')
    ->setMotherName('ΜΑΡΙΑ')
    ->setSex(Sex::MALE)
    ->setBirthDate('08/09/2005')
    ->setNationality('048')
    ->setIdType('ΔΑΤ')
    ->setIdNumber('ΑΒ123456')
    ->setMaritalStatus(MaritalStatus::SINGLE)
    ->setNumberOfChildren(0)
    ->setAfm('123456789')
    ->setAmka('08090512345')
    ->setEducationLevel('1')                       // 0-4 only
    ->setSchoolCode('4002')
    ->setSpecialtyGroupCode('4004')
    // Apprenticeship
    ->setApprenticeshipPeriod('14/09/2025', '30/06/2026') // f_date_proslipsis / f_orismenou_ews
    ->setPlacementTime('09:00')
    ->setScheduleDescription('ΔΕΥΤΕΡΑ - ΠΑΡΑΣΚΕΥΗ 09:00 - 15:00')
    ->setWeeklyHours(30)                           // free text, sent as "30"
    ->setScheduleAttached(false)
    ->setSpecialtyCode('4013')
    ->setGrossSalary(1000.0)                       // f_apodoxes → "1.000,00"
    ->setContractNumber('ΣΥΜΒ-2025/123')
    // Sign-off
    ->setLegalRepresentativeAfm('078776545')
    // last name, first name, capacity, address, AFM (or the individual setCertifier*() setters)
    ->setCertifier('ΛΟΓΙΣΤΗΣ', 'ΜΑΡΙΑ', 'ΛΟΓΙΣΤΡΙΑ', 'ΣΤΑΔΙΟΥ 10', '000000001')
    ->withDefaults(); // send every remaining optional field as an empty string

$response = (new Apprenticeship())->handle($declaration);
```

::: tip All fields are sent
ERGANI expects every E3.4 element to be present, even when empty. Call `withDefaults()` after setting your values to fill the remaining fields with empty strings.
:::

### Via Ergani Facade

```php
use OxygenSuite\OxygenErgani\Ergani;

$ergani = new Ergani($accessToken);

$responses = $ergani->sendApprenticeship($declaration);
```

## Helpers

```php
// Apprenticeship period (f_date_proslipsis / f_orismenou_ews)
$declaration->setApprenticeshipPeriod('14/09/2025', '30/06/2026');

// School / specialty group not in the code list: leave the code empty and use free text
$declaration
    ->setSchoolCode('')
    ->setSchoolOther('ΕΠΑΣ ΜΑΘΗΤΕΙΑΣ ΑΘΗΝΑΣ')     // max 50 characters
    ->setSpecialtyGroupCode('')
    ->setSpecialtyGroupOther('ΤΟΜΕΑΣ ΠΛΗΡΟΦΟΡΙΚΗΣ'); // max 50 characters
```

::: info E3.4 validation rules
- `setEducationLevel()` only accepts `0`-`4`, and `setEmployerOrganization()` is limited to 20 characters.
- `f_date_time_proslipsis` (`setPlacementTime()`), `f_orario` (`setScheduleDescription()`), `f_week_hours` (`setWeeklyHours()`) and `f_contract` (`setContractNumber()`) are required.
- `f_week_hours` is free text (`setWeeklyHours(40)` sends `"40"`), while `f_apodoxes` is a Greek decimal (`setGrossSalary(1000.0)` sends `"1.000,00"`).
:::

## Corrections (WebE34C)

```php
use OxygenSuite\OxygenErgani\Http\Documents\Apprenticeship\ApprenticeshipCorrection;

$declaration->setRelatedSubmission(               // f_protocol_sx / f_date_ypovolis_sx
    $original[0]->protocol,
    $original[0]->submissionDate,
);

$response = (new ApprenticeshipCorrection())->handle($declaration);

// or
$responses = $ergani->sendApprenticeshipCorrection($declaration);
```

## Field Reference

Every element must be present in the payload; "Required" marks the elements whose XSD pattern does not accept an empty value.

| Method | API Field | Type | Required | Description |
|--------|-----------|------|----------|-------------|
| `setBranchCode()` | `f_aa_pararthmatos` | int\|string | Yes | Branch sequence number (0 for HQ) |
| `setRelatedProtocol()` | `f_protocol_sx` | string | No | Protocol of the original declaration (corrections only). |
| `setRelatedDate()` | `f_date_ypovolis_sx` | DateTimeInterface\|string | No | Submission date of the original declaration (corrections only). |
| `setDypaServiceCode()` | `f_ypiresia_oaed` | string | Yes | DYPA service code. |
| `setLaborInspectionCode()` | `f_ypiresia_sepe` | string | Yes | Labour inspectorate service code (5 digits). |
| `setProgramCode()` | `f_programma_novitiate` | string | Yes | Apprenticeship program code (3 digits). |
| `setEmployerOrganization()` | `f_mer_organization` | string | No | Employer organization (max 20 characters). |
| `setMainActivityCode()` | `f_mer_stakod1` | string | Yes | Main activity code (KAD, 4 digits). |
| `setSecondaryActivityCode1()` | `f_mer_stakod2` | string | No | Secondary activity code1 |
| `setSecondaryActivityCode2()` | `f_mer_stakod3` | string | No | Secondary activity code2 |
| `setSecondaryActivityCode3()` | `f_mer_stakod4` | string | No | Secondary activity code3 |
| `setSecondaryActivityCode4()` | `f_mer_stakod5` | string | No | Secondary activity code4 |
| `setBranchActivityCode()` | `f_mpa_stakod` | string | Yes | Activity code (KAD) of the branch. |
| `setMunicipalityCode()` | `f_mpa_kallikratis` | string | Yes | Kallikratis code of the branch (8 digits). |
| `setLastName()` | `f_mea_eponimo` | string | Yes | Last name |
| `setFirstName()` | `f_mea_onoma` | string | Yes | First name |
| `setFatherLastName()` | `f_mea_eponimo_patera` | string | No | Father last name |
| `setFatherName()` | `f_mea_onoma_patera` | string | Yes | Father name |
| `setMotherLastName()` | `f_mea_eponimo_miteras` | string | No | Mother last name |
| `setMotherName()` | `f_mea_onoma_miteras` | string | Yes | Mother name |
| `setSex()` | `f_mea_sex` | Sex\|string\|int | Yes | 0=Male, 1=Female (or use Sex enum) |
| `setBirthPlace()` | `f_mea_birth_city` | string | No | Birth place |
| `setBirthDate()` | `f_mea_date_birth` | DateTimeInterface\|string | Yes | Birth date (DD/MM/YYYY) |
| `setNationality()` | `f_mea_nationality` | string | Yes | Nationality code (3 digits, see ParameterLookup::NATIONALITY). |
| `setIdType()` | `f_mea_typos_taytotitas` | string | Yes | Identity document type (see ParameterLookup::ID_TYPE). |
| `setIdNumber()` | `f_mea_ar_taytotitas` | string | Yes | Identity document number |
| `setIdIssuingAuthority()` | `f_mea_ekdousa` | string | No | Id issuing authority |
| `setIdIssueDate()` | `f_mea_date_ekdosis` | DateTimeInterface\|string | No | Identity document issue date (DD/MM/YYYY) |
| `setIdExpiryDate()` | `f_mea_date_ekdosis_lixi` | DateTimeInterface\|string | No | Identity document expiry date (DD/MM/YYYY) |
| `setResidencePermit()` | `f_mea_res_permit` | string\|bool | No | Whether the beneficiary holds a residence or work permit. |
| `setResidencePermitType()` | `f_mea_res_permit_type` | string | No | Residence permit type (see ParameterLookup::RESIDENCE_PERMIT). |
| `setResidencePermitNumber()` | `f_mea_res_permit_ar` | string | No | Residence permit number |
| `setResidencePermitExpiry()` | `f_mea_res_permit_lixi` | DateTimeInterface\|string | No | Residence permit expiry date (DD/MM/YYYY) |
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
| `setEducationLevel()` | `f_mea_epipedo_morfosis` | string | Yes | Education level code — E3.4 accepts only 0-4. |
| `setSchoolCode()` | `f_mea_noviciate_sxolh` | string | No | Vocational school code (4 digits); leave empty and use setSchoolOther() if not listed. |
| `setSchoolOther()` | `f_mea_noviciate_sxolh_other` | string | No | School name as free text when the school code is not listed (max 50 characters). |
| `setSpecialtyGroupCode()` | `f_mea_noviciate_omada` | string | No | Specialty group / sector code (4 digits); leave empty and use setSpecialtyGroupOther() if not listed. |
| `setSpecialtyGroupOther()` | `f_mea_noviciate_omada_other` | string | No | Specialty group as free text when the group code is not listed (max 50 characters). |
| `setPlacementDate()` | `f_date_proslipsis` | DateTimeInterface\|string | Yes | First day of the apprenticeship (DD/MM/YYYY). |
| `setPlacementTime()` | `f_date_time_proslipsis` | string | Yes | Start time on the first day (HH:MM). |
| `setScheduleDescription()` | `f_orario` | string | Yes | Working / training days and hours as free text (max 200 characters). |
| `setWeeklyHours()` | `f_week_hours` | int\|string | Yes | Weekly apprenticeship hours as free text (e.g. "40"). |
| `setBreakDescription()` | `f_orario_dialeima` | string | No | Break hours as free text (max 200 characters). |
| `setScheduleAttached()` | `f_wres_external` | string\|bool | Yes | Whether the working days and hours are given in an attached file. |
| `setSpecialtyCode()` | `f_novitiate_education` | string | Yes | Apprenticeship specialty code (4 digits). |
| `setGrossSalary()` | `f_apodoxes` | float | Yes | Gross apprentice wage (Greek float, `1000.0` → `"1.000,00"`). |
| `setEndDate()` | `f_orismenou_ews` | DateTimeInterface\|string | Yes | Apprenticeship end date (DD/MM/YYYY). |
| `setContractNumber()` | `f_contract` | string | Yes | Apprenticeship contract number / reference (max 50 characters). |
| `setComments()` | `f_comments` | string | No | Comments |
| `setLegalRepresentativeAfm()` | `f_mek_afm` | string | Yes | AFM of the responsible provider / legal representative. |
| `setCertifierLastName()` | `f_eponymo` | string | Yes | Certifier last name |
| `setCertifierFirstName()` | `f_onoma` | string | Yes | Certifier first name |
| `setCertifierCapacity()` | `f_idiotita` | string | Yes | Capacity of the certifying person (e.g. accountant) |
| `setCertifierAddress()` | `f_dieythinsi` | string | Yes | Certifier address |
| `setCertifierAfm()` | `f_afm_bebaiwn` | string | Yes | AFM of the certifying person |
| `setFormFile()` | `f_file` | string | No | Signed form (base64-encoded PDF). |
| `setForeignFile()` | `f_foreign_file` | string | No | Third-country national labour-market access documents (base64-encoded PDF). |
| `setMinorFile()` | `f_young_file` | string | No | Minor worker book (base64-encoded PDF). |

## Response Handling

```php
$response = (new Apprenticeship())->handle($declaration);

foreach ($response as $result) {
    echo $result->id;              // Unique submission ID
    echo $result->protocol;        // Protocol number
    echo $result->submissionDate->format('d/m/Y H:i:s');
}
```

## Retrieve PDF

```php
$pdfBase64 = (new Apprenticeship())->pdf(
    $response[0]->protocol,
    $response[0]->submissionDate
);

file_put_contents('apprenticeship.pdf', base64_decode($pdfBase64));
```

## Testing with Factories

```php
use OxygenSuite\OxygenErgani\Models\Apprenticeship\ApprenticeshipDeclaration;

$declaration = ApprenticeshipDeclaration::factory()->make();

$declaration = ApprenticeshipDeclaration::factory()
    ->forBranch(1)
    ->asMinor('ΒΑ123456')                        // minor worker book number
    ->asCorrection('ΑΚ123', '01/10/2026')
    ->make();
```

## See Also

- [Community-Benefit Program Employment (E3.1)](/guide/community-program) - Shares the beneficiary and provider fields
- [Theoretical Training (E3.2)](/guide/theoretical-training)
- [Practical Training (E3.3)](/guide/practical-training)
- [Internship (E3.5)](/guide/internship)
- [Services & Queries](/guide/services) - Parameter lookups
