# Common Fields

All declaration models share common personal information fields defined in their base `Declaration` class.

## Branch/Location

| Method | API Field | Type | Description |
|--------|-----------|------|-------------|
| `setBranchCode($code)` | `f_aa_pararthmatos` | int | Branch number (0 = HQ) |
| `setLaborInspectionServiceCode($code)` | `f_ypiresia_sepe` | string | SEPE code |
| `setDypaServiceCode($code)` | `f_ypiresia_oaed` | string | DYPA/OAED code |
| `setBranchActivityCode($code)` | `f_kad_pararthmatos` | string | Activity code (KAD) |

---

## Personal Information

| Method | API Field | Type | Description |
|--------|-----------|------|-------------|
| `setLastName($name)` | `f_eponymo` | string | Last name (uppercase Greek) |
| `setFirstName($name)` | `f_onoma` | string | First name |
| `setFatherName($name)` | `f_onoma_patros` | string | Father's name |
| `setMotherName($name)` | `f_onoma_mitros` | string | Mother's name |
| `setBirthDate($date)` | `f_birthdate` | string\|DateTime | Birth date (DD/MM/YYYY) |
| `setSex($sex)` | `f_sex` | Sex | Sex (enum) |
| `setMaritalStatus($status)` | `f_marital_status` | MaritalStatus | Marital status |
| `setNumberOfChildren($count)` | `f_arithmos_teknon` | int | Number of children |

---

## Identity

| Method | API Field | Type | Description |
|--------|-----------|------|-------------|
| `setNationality($code)` | `f_yphkoothta` | string | Nationality code |
| `setIdType($type)` | `f_typos_taytothtas` | string | ID document type |
| `setIdNumber($number)` | `f_ar_taytothtas` | string | ID number |
| `setIdIssuingAuthority($auth)` | `f_ekdousa_arxh` | string | Issuing authority |
| `setIdIssueDate($date)` | `f_date_ekdosis` | string\|DateTime | Issue date |
| `setIdExpiryDate($date)` | `f_date_ekdosis_lixi` | string\|DateTime | Expiry date |

---

## Tax/Insurance

| Method | API Field | Type | Description |
|--------|-----------|------|-------------|
| `setAfm($afm)` | `f_afm` | string | Tax ID (9 digits) |
| `setTaxOffice($code)` | `f_doy` | string | Tax office code |
| `setAmka($amka)` | `f_amka` | string | Social security number (11 digits) |
| `setAmika($amika)` | `f_amika` | string | IKA number (8 digits) |

---

## Residence Permits (Foreign Nationals)

| Method | API Field | Type | Description |
|--------|-----------|------|-------------|
| `setResPermitApprovalRequired($flag)` | `f_adeia_diamonis_egkrisi` | string | Requires approval |
| `setResPermitApprovalNumber($number)` | `f_adeia_diamonis_egkrisi_arith` | string | Approval number |
| `setResPermitApprovalDate($date)` | `f_adeia_diamonis_egkrisi_date` | string\|DateTime | Approval date |
| `setResPermitDirectAccess($flag)` | `f_adeia_diamonis_amesi` | string | Has direct access |
| `setResPermitDirectAccessExpiry($date)` | `f_adeia_diamonis_amesi_lixi` | string\|DateTime | Direct access expiry |
| `setResPermitSeasonalVisa($flag)` | `f_adeia_diamonis_eviza` | string | Has seasonal visa |
| `setResPermitSeasonalVisaExpiry($date)` | `f_adeia_diamonis_eviza_lixi` | string\|DateTime | Visa expiry |

---

## Address

| Method | API Field | Type | Description |
|--------|-----------|------|-------------|
| `setAddressStreet($street)` | `f_dieuthinsi` | string | Street address |
| `setAddressNumber($number)` | `f_arithmos` | string | Street number |
| `setAddressCity($city)` | `f_poli` | string | City |
| `setAddressPostalCode($code)` | `f_tk` | string | Postal code |

---

## Example Usage

```php
use OxygenSuite\OxygenErgani\Models\Hiring\NewDeclaration;
use OxygenSuite\OxygenErgani\Enums\{Sex, MaritalStatus};
use DateTime;

$declaration = NewDeclaration::make()
    // Branch
    ->setBranchCode(0)
    ->setLaborInspectionServiceCode('SEPE001')
    ->setDypaServiceCode('OAED001')

    // Personal
    ->setLastName('ΠΑΠΑΔΟΠΟΥΛΟΣ')
    ->setFirstName('ΙΩΑΝΝΗΣ')
    ->setFatherName('ΔΗΜΗΤΡΙΟΣ')
    ->setMotherName('ΜΑΡΙΑ')
    ->setBirthDate(new DateTime('1990-01-15'))
    ->setSex(Sex::MALE)
    ->setMaritalStatus(MaritalStatus::SINGLE)

    // Identity
    ->setNationality('001')  // Greek
    ->setIdType('ΑΤ')
    ->setIdNumber('ΑΒ123456')
    ->setIdIssuingAuthority('Α.Τ. ΑΘΗΝΩΝ')

    // Tax/Insurance
    ->setAfm('123456789')
    ->setTaxOffice('1101')
    ->setAmka('15019012345')
    ->setAmika('12345678')

    // Address
    ->setAddressStreet('ΕΡΜΟΥ')
    ->setAddressNumber('100')
    ->setAddressCity('ΑΘΗΝΑ')
    ->setAddressPostalCode('10563');
```

## See Also

- [Hiring Models](./hiring) - E3 declaration models
- [Termination Models](./termination) - E5/E7 declaration models
- [Dismissal Models](./dismissal) - E6 declaration models
- [Personal Enums](/api/enums/personal) - Sex, MaritalStatus
