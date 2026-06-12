# Model Factories

The package includes a Laravel-inspired factory system for generating model instances filled with realistic, valid Greek test data — AFMs with correct checksums, AMKAs derived from birth dates, Greek names, and properly formatted dates.

Factories are intended for **testing and development**. Every instantiable model ships with a factory.

## Requirements

Factories depend on [FakerPHP](https://fakerphp.org/), which is **not** installed by default:

```bash
composer require --dev fakerphp/faker
```

If you call `Model::factory()` without Faker installed, a `RuntimeException` explains exactly this.

## Basic Usage

Every model using the `HasFactory` trait exposes a static `factory()` method:

```php
use OxygenSuite\OxygenErgani\Models\Hiring\NewDeclaration;

// Create a single instance with random valid data
$declaration = NewDeclaration::factory()->make();

// Create multiple instances
$declarations = NewDeclaration::factory(3)->make();

// Override specific attributes
$declaration = NewDeclaration::factory()->make([
    'f_eponymo' => 'ΠΑΠΑΔΟΠΟΥΛΟΣ',
    'f_afm' => '123456789',
]);

// Exclude fields from the generated data
$declaration = NewDeclaration::factory()
    ->except(['f_comments'])
    ->make();
```

`make()` returns a single model when the count is 1, or an array of models otherwise.

## State Methods

Factories provide fluent state methods for common scenarios. `NewDeclarationFactory` is the richest example:

```php
use OxygenSuite\OxygenErgani\Models\Hiring\NewDeclaration;

// Employment types
$declaration = NewDeclaration::factory()
    ->fixedTerm('01/01/2025', '30/06/2025')  // Fixed-term contract
    ->partTime(25.0)                          // Part-time (25 hours/week)
    ->withTrialPeriod('30/06/2025')           // With trial period
    ->make();

// Foreign nationals
$declaration = NewDeclaration::factory()
    ->foreignNationalDirectAccess('002')      // Direct labor market access
    ->foreignNationalApproval('003')          // Requires approval
    ->withSeasonalVisa('004')                 // Seasonal work visa
    ->make();

// Special cases
$declaration = NewDeclaration::factory()
    ->asManager()                             // Managerial position
    ->asWorker()                              // Blue-collar worker
    ->asMinor('BOOK123')                      // Minor worker (15-17 years)
    ->remoteWork('Athens')                    // Remote work location
    ->make();

// Programs and insurance
$declaration = NewDeclaration::factory()
    ->withDypaPlacement('PROG001')            // DYPA program placement
    ->withSupplementaryInsurance(['201'])     // Supplementary insurance
    ->withCollectiveAgreement('ΕΓΣΣΕ')        // Collective agreement
    ->make();

// Chain multiple states
$declaration = NewDeclaration::factory()
    ->fixedTerm()
    ->partTime(20.0)
    ->female()
    ->withTrialPeriod()
    ->make();
```

Check each factory class in `src/Factories/` for its available state methods.

## Available Factories

Factories mirror the model namespace structure:

| Category | Factories |
|----------|-----------|
| Work Cards | `Card`, `CardDetail` |
| Work Time | `WorkTime`, `WorkTimeEmployee`, `WorkTimeEntry` |
| Overtime | `Overtime`, `OvertimeEmployee` |
| Hiring (E3) | `NewDeclaration`, `ModificationDeclaration`, `DeletionDeclaration`, `LendingDeclaration`, `SupplementaryInsuranceSelection` |
| Termination (E5/E7) | `VoluntaryResignationDeclaration`, `NotificationDeclaration`, `ResignationAfterNotificationDeclaration`, `DeathTerminationDeclaration`, `CompensatedExitDeclaration`, `VoluntaryRetirementDeclaration`, `MandatoryRetirementDeclaration`, `FixedTermTerminationDeclaration` |
| Dismissal (E6) | `DismissalWithoutNoticeDeclaration`, `DismissalWithNoticeDeclaration`, `RetirementDismissalDeclaration`, `EndOfLoanDeclaration`, `TrialPeriodTerminationDeclaration`, `TransferDeclaration` |
| Modifications (MA) | `ModificationDeclaration`, `BorrowedModificationDeclaration`, `ModificationTypeSelection` |
| Construction (E12) | `ConstructionWork`, `ConstructionEmployee`, `ConstructionCensus`, `ConstructionCensusEmployee` |
| Sixth Day | `SixthDayDeclaration` |
| Pre-Announcement | `ExemptionDeclaration` |
| Internship (E3.5) | `InternshipDeclaration` |

## Greek Data Provider

The factory system registers a custom Faker provider (`GreekProvider`) that generates valid Greek identifiers:

| Method | Description |
|--------|-------------|
| `afm()` | Valid 9-digit AFM (tax number) with correct checksum |
| `amka()` | Valid 11-digit AMKA (social security number) based on a birth date |
| `greekIdNumber()` | Greek ID format (1–2 Greek letters + 6 digits) |
| `greekFirstName()` | Greek first name in uppercase |
| `greekLastName()` | Greek last name in uppercase |
| `amika()` | 8-digit IKA insurance number |
| `greekDate()` | Date in `DD/MM/YYYY` format |
| `time24h()` | Time in `HH:MM` (24-hour) format |
| `workEndTime()` | Work end time relative to a start time |

You can access the configured generator directly:

```php
use OxygenSuite\OxygenErgani\Factories\Factory;

$afm = Factory::fake()->afm();       // e.g., '123456789' (valid checksum)
$name = Factory::fake()->greekLastName(); // e.g., 'ΠΑΠΑΔΟΠΟΥΛΟΣ'
```

## Creating Custom Factories

To add a factory for your own model:

1. Create a factory class extending `Factory`, mirroring the model namespace structure. Use `self::fake()` inside `definition()`:

```php
namespace OxygenSuite\OxygenErgani\Factories\Hiring;

use OxygenSuite\OxygenErgani\Factories\Factory;

class MyDeclarationFactory extends Factory
{
    public function definition(): array
    {
        $fake = self::fake();

        return [
            'f_afm' => $fake->afm(),
            'f_eponymo' => $fake->greekLastName(),
            // ... all required fields
        ];
    }

    // Optional state methods
    public function someState(): static
    {
        return $this->state(['f_field' => 'value']);
    }
}
```

2. Add the `HasFactory` trait to the model:

```php
use OxygenSuite\OxygenErgani\Models\Concerns\HasFactory;

class MyDeclaration extends Model
{
    use HasFactory;
}
```

The factory class is resolved by convention: `Models\Hiring\MyDeclaration` → `Factories\Hiring\MyDeclarationFactory`.

## Example: Testing a Submission

```php
use OxygenSuite\OxygenErgani\Models\Hiring\NewDeclaration;

public function test_my_hiring_integration(): void
{
    $declaration = NewDeclaration::factory()
        ->fixedTerm('01/01/2025', '30/06/2025')
        ->make([
            'f_afm' => '123456789', // pin the fields your assertion depends on
        ]);

    $payload = $declaration->toSortedArray();

    // ... assert against your own integration layer
}
```
