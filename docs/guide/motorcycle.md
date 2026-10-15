# Motorcycle Use (E13)

The E13 form is the employer declaration that employees use a moped or motorcycle while carrying out their work (e.g. couriers and delivery staff). One declaration can list several vehicles.

## Overview

| Property | Value |
|----------|-------|
| Document Class | `MotorcycleUse` |
| Action Code | `WebE13` |
| Declaration Model | `MotorcycleUseDeclaration` |
| Vehicle Model | `MotorcycleVehicle` |
| Use Case | Declaring mopeds/motorcycles used by employees for work |

## Basic Usage

```php
use OxygenSuite\OxygenErgani\Enums\VehicleOwner;
use OxygenSuite\OxygenErgani\Http\Documents\Motorcycle\MotorcycleUse;
use OxygenSuite\OxygenErgani\Models\Motorcycle\MotorcycleUseDeclaration;
use OxygenSuite\OxygenErgani\Models\Motorcycle\MotorcycleVehicle;

$vehicle = MotorcycleVehicle::make()
    ->setLicencePlate('ΥΡΟ4534')
    ->setOwner(VehicleOwner::EMPLOYEE)
    ->setAfm('888888888')
    ->setLastName('ΠΑΠΑΔΟΠΟΥΛΟΣ')
    ->setFirstName('ΙΩΑΝΝΗΣ')
    ->setFatherName('ΓΕΩΡΓΙΟΣ')
    ->setStartDate('15/09/2025')
    ->setStartTime('08:00')
    ->setEndDate('')
    ->setNotes('');

$declaration = MotorcycleUseDeclaration::make()
    ->setBranchCode(0)
    ->setLaborInspectionCode('24010')
    ->setDypaServiceCode('101213')
    ->setComments('')
    ->setLegalRepresentativeAfm('123456789')
    ->addVehicle($vehicle);

$response = (new MotorcycleUse())->handle($declaration);
```

### Via Ergani Facade

```php
use OxygenSuite\OxygenErgani\Ergani;

$ergani = new Ergani($accessToken);
$responses = $ergani->sendMotorcycleUse($declaration);
```

## Field Reference

### Declaration Fields (MotorcycleUseDeclaration)

| Method | API Field | Type | Required | Description |
|--------|-----------|------|----------|-------------|
| `setBranchCode()` | `f_aa_pararthmatos` | int\|string | Yes | Branch sequence number (0 for HQ) |
| `setLaborInspectionCode()` | `f_ypiresia_sepe` | string | Yes | Labour inspectorate service code (5 digits) |
| `setDypaServiceCode()` | `f_ypiresia_oaed` | string | Yes | DYPA service code (6 digits) |
| `setComments()` | `f_comments` | string | Yes | Comments (max 200 characters, may be empty) |
| `setLegalRepresentativeAfm()` | `f_afm_proswpoy` | string | Yes | Legal representative AFM (9 digits) |
| `setVehicles()` / `addVehicle()` | `Mixanes` | MotorcycleVehicle[] | Yes | At least one vehicle |

### Vehicle Fields (MotorcycleVehicle)

| Method | API Field | Type | Required | Description |
|--------|-----------|------|----------|-------------|
| `setLicencePlate()` | `f_licence_plate` | string | Yes | Licence plate number |
| `setOwner()` | `f_is_owner` | VehicleOwner\|string\|int | Yes | Who owns/holds the vehicle (0=Employer, 1=Employee) |
| `setAfm()` | `f_afm_ergazomenou` | string | Yes | Employee AFM (9 digits, may be empty) |
| `setLastName()` | `f_eponimo_ergazomenou` | string | Yes | Employee last name (max 100) |
| `setFirstName()` | `f_onoma_ergazomenou` | string | Yes | Employee first name (max 50) |
| `setFatherName()` | `f_onoma_patera` | string | Yes | Employee father's name (max 50) |
| `setStartDate()` | `f_date_enarxis_xrisis` | DateTime\|string | Yes | Vehicle use start date (DD/MM/YYYY) |
| `setStartTime()` | `f_hour_enarxis_xrisis` | string | Yes | Vehicle use start time (HH:MM) |
| `setEndDate()` | `f_date_lixis_xrisis` | DateTime\|string | Yes | Vehicle use end date (DD/MM/YYYY, may be empty) |
| `setNotes()` | `f_paratiriseis` | string | Yes | Notes (max 200, may be empty) |

::: tip Empty values
All vehicle fields must be present in the payload. Send an empty string for optional values such as the end date or notes.
:::

### VehicleOwner Enum

| Case | Value | English | Greek |
|------|-------|---------|-------|
| `EMPLOYER` | `0` | Employer | Εργοδότης |
| `EMPLOYEE` | `1` | Employee | Εργαζόμενος |

### Managing Vehicles

```php
// Add one at a time
$declaration->addVehicle($vehicle1);
$declaration->addVehicle($vehicle2);

// Or set all at once
$declaration->setVehicles([$vehicle1, $vehicle2]);

// Access vehicles
$vehicles = $declaration->getVehicles();
$first = $declaration->getVehicle(0);
```

## Response Handling

```php
$response = (new MotorcycleUse())->handle($declaration);

foreach ($response as $result) {
    echo $result->id;              // Unique submission ID
    echo $result->protocol;        // Protocol number
    echo $result->submissionDate->format('d/m/Y H:i:s');
}
```

## Retrieve PDF

```php
$pdfBase64 = (new MotorcycleUse())->pdf(
    $response[0]->protocol,
    $response[0]->submissionDate
);

file_put_contents('motorcycle-use.pdf', base64_decode($pdfBase64));
```

## Testing with Factories

```php
use OxygenSuite\OxygenErgani\Models\Motorcycle\MotorcycleUseDeclaration;
use OxygenSuite\OxygenErgani\Models\Motorcycle\MotorcycleVehicle;

$declaration = MotorcycleUseDeclaration::factory()->make();

// With state methods
$declaration = MotorcycleUseDeclaration::factory()
    ->forBranch(2)
    ->withVehicles(3)
    ->make();

// Vehicle factory
$vehicle = MotorcycleVehicle::factory()
    ->employerOwned()
    ->endingOn('31/12/2026')
    ->make();
```

## See Also

- [Services & Queries](/guide/services) - Parameter lookups
- [Cancel Submissions](/guide/cancel-submissions) - Cancel a submitted declaration
