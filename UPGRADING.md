# Upgrading from v1.x to v2.0

This guide covers the breaking changes in v2.0 and how to migrate your code.

## Requirements

### PHP Version

v2.0 requires **PHP 8.2 or higher** (v1.x required PHP 8.1).

```diff
- "php": "^8.1"
+ "php": "^8.2"
```

### PHPUnit Version

If you're using PHPUnit in your project, v2.0's dev dependencies require PHPUnit 12:

```diff
- "phpunit/phpunit": "^11.0"
+ "phpunit/phpunit": "^12.0"
```

## Breaking Changes

### 1. WorkCard Namespace Change

The `WorkCard` document class has been moved to a subfolder.

```diff
- use OxygenSuite\OxygenErgani\Http\Documents\WorkCard;
+ use OxygenSuite\OxygenErgani\Http\Documents\WorkCard\WorkCard;
```

### 2. DailyWorkTime Removed

The `DailyWorkTime` document has been removed. Use the new `WorkTime` documents instead.

**Before (v1.x):**
```php
use OxygenSuite\OxygenErgani\Http\Documents\DailyWorkTime;

$document = new DailyWorkTime();
```

**After (v2.0):**
```php
use OxygenSuite\OxygenErgani\Http\Documents\WorkTime\DailySchedule;
// or
use OxygenSuite\OxygenErgani\Http\Documents\WorkTime\WeeklySchedule;

$document = new DailySchedule();
// or
$document = new WeeklySchedule();
```

### 3. WTO Models Renamed to WorkTime

All WTO (Work Time Organization) models have been renamed for clarity.

```diff
- use OxygenSuite\OxygenErgani\Models\WTO\WTO;
- use OxygenSuite\OxygenErgani\Models\WTO\WTOAnalytics;
- use OxygenSuite\OxygenErgani\Models\WTO\WTOEmployee;
+ use OxygenSuite\OxygenErgani\Models\WorkTime\WorkTime;
+ use OxygenSuite\OxygenErgani\Models\WorkTime\WorkTimeEntry;
+ use OxygenSuite\OxygenErgani\Models\WorkTime\WorkTimeEmployee;
```

**Migration table:**

| v1.x Class | v2.0 Class |
|------------|------------|
| `WTO` | `WorkTime` |
| `WTOAnalytics` | `WorkTimeEntry` |
| `WTOEmployee` | `WorkTimeEmployee` |

### 4. Strict Types for Numeric Fields

v2.0 enforces strict types for numeric fields. Float fields must be passed as floats, and integer fields as integers.

**Before (v1.x):**
```php
$declaration->setGrossSalary('1500.00');  // String worked
$declaration->setWeeklyHours('40');       // String worked
```

**After (v2.0):**
```php
$declaration->setGrossSalary(1500.00);    // Must be float
$declaration->setWeeklyHours(40.0);       // Must be float
```

The package handles Greek number formatting (e.g., `1.500,00`) internally when generating API payloads.

### 5. Code Style: PSR-12 to PER

The codebase now follows the [PER Coding Style](https://www.php-fig.org/per/coding-style/) instead of PSR-12. This shouldn't affect your code, but if you're contributing, please use PER style.

### 6. CardDetail Getter Renamed

The `getTinNumber()` method in `CardDetail` has been renamed to `getTin()` for consistency with the setter `setTin()`.

```diff
- $cardDetail->getTinNumber();
+ $cardDetail->getTin();
```

### 7. `flushCache()` Is Now Static

The `flushCache()` method on the `Ergani` facade is now a static method that takes the cache instance as a parameter.

```diff
- $ergani->flushCache();
+ Ergani::flushCache($cache);
```

### 8. Working Status Change Removed

The `WKChgWK` (Working Status Change) document has been removed entirely. The ERGANI API no longer lists this submission type; its functionality has been absorbed into the Employment Modification forms (MA/MAD).

**Before:**

```php
use OxygenSuite\OxygenErgani\Http\Documents\WorkingStatus\WorkingStatusChange;
use OxygenSuite\OxygenErgani\Models\WorkingStatus\WorkingStatus;
use OxygenSuite\OxygenErgani\Models\WorkingStatus\WorkingStatusEmployee;

$employee = WorkingStatusEmployee::make()
    ->setAfm('123456789')
    // ...

$response = (new WorkingStatusChange())->handle(
    WorkingStatus::make()->addEmployee($employee)
);

// Or via the Ergani facade:
$ergani->sendWorkingStatusChange($workingStatus);
```

**After:**

Use `EmploymentModification` (WebMA) for regular employees, or `BorrowedEmploymentModification` (WebMAD) for borrowed/loaned employees:

```php
use OxygenSuite\OxygenErgani\Http\Documents\Modification\EmploymentModification;
use OxygenSuite\OxygenErgani\Models\Modification\ModificationDeclaration;
use OxygenSuite\OxygenErgani\Models\Modification\ModificationTypeSelection;

$declaration = ModificationDeclaration::make()
    ->setBranchCode(0)
    ->setLastName('ΠΑΠΑΔΟΠΟΥΛΟΣ')
    ->setFirstName('ΙΩΑΝΝΗΣ')
    // ... personal and employment fields ...
    ->setModificationDate('01/02/2025')
    ->addModificationTypeSelection(
        ModificationTypeSelection::make()->setModificationTypeCode('01')
    );

$response = (new EmploymentModification())->handle($declaration);

// Or via the Ergani facade:
$ergani->sendEmploymentModification($declaration);
```

See the [Modifications guide](/guide/modifications) for full documentation.

**Removed classes:**

| Removed | Replacement |
|---------|-------------|
| `WorkingStatusChange` | `EmploymentModification` or `BorrowedEmploymentModification` |
| `WorkingStatus` | `ModificationDeclaration` or `BorrowedModificationDeclaration` |
| `WorkingStatusEmployee` | _(fields are now part of the declaration model)_ |
| `SendsWorkingStatusDocuments` trait | `SendsModificationDocuments` trait |
| `sendWorkingStatusChange()` | `sendEmploymentModification()` or `sendBorrowedEmploymentModification()` |

## New Features Available in v2.0

After upgrading, you can take advantage of these new features:

### Hiring Forms (E3)

```php
use OxygenSuite\OxygenErgani\Http\Documents\Hiring\HiringNew;
use OxygenSuite\OxygenErgani\Models\Hiring\NewDeclaration;

$declaration = NewDeclaration::make()
    ->setEmployerTin('999999999')
    ->setLastName('ΠΑΠΑΔΟΠΟΥΛΟΣ')
    // ... other fields
    ->withDefaults();

$response = (new HiringNew())->handle($declaration);
```

### Termination Forms (E5)

```php
use OxygenSuite\OxygenErgani\Http\Documents\Termination\VoluntaryResignation;
use OxygenSuite\OxygenErgani\Models\Termination\VoluntaryResignationDeclaration;

$declaration = VoluntaryResignationDeclaration::make()
    ->setEmployerTin('999999999')
    ->setResignationDate('15/01/2025')
    // ... other fields
    ->withDefaults();

$response = (new VoluntaryResignation())->handle($declaration);
```

### Dismissal Forms (E6)

```php
use OxygenSuite\OxygenErgani\Http\Documents\Dismissal\DismissalWithoutNotice;
use OxygenSuite\OxygenErgani\Models\Dismissal\DismissalWithoutNoticeDeclaration;

$declaration = DismissalWithoutNoticeDeclaration::make()
    ->setEmployerTin('999999999')
    ->setDismissalDate('15/01/2025')
    // ... other fields
    ->withDefaults();

$response = (new DismissalWithoutNotice())->handle($declaration);
```

### Query Services

```php
use OxygenSuite\OxygenErgani\Http\Services\EmployerInfo;
use OxygenSuite\OxygenErgani\Http\Services\ParameterLookup;

// Get employer details
$employer = (new EmployerInfo())->handle('999999999');

// Get parameter lists
$workTypes = (new ParameterLookup())->handle(ParameterLookup::WORK_TIME_TYPE);
$workType = $workTypes->find('ΕΡΓ'); // O(1) lookup
```

### Model Factories (for testing)

```php
use OxygenSuite\OxygenErgani\Models\Hiring\NewDeclaration;

// Generate test data with valid Greek identifiers
$declaration = NewDeclaration::factory()->make();

// With custom attributes
$declaration = NewDeclaration::factory()->make([
    'f_eponymo' => 'CUSTOM_NAME',
]);

// With state methods
$declaration = NewDeclaration::factory()
    ->fixedTerm('01/01/2025', '30/06/2025')
    ->partTime(25.0)
    ->make();
```

### Configurable Token Cache Directory

```php
use OxygenSuite\OxygenErgani\Storage\FileToken;
use OxygenSuite\OxygenErgani\Storage\Token;

// Store tokens outside web root for security
$options = ['cache_dir' => '/var/app/storage/tokens'];
Token::setCurrentTokenManager(
    new FileToken($username, $password, $options),
    $env
);
```

### Enums with Labels

```php
use OxygenSuite\OxygenErgani\Enums\Sex;

echo Sex::MALE->label();       // "Male"
echo Sex::MALE->labelGreek();  // "Άνδρας"

// For HTML dropdowns
$options = Sex::labels();      // ['1' => 'Male', '2' => 'Female']
```

### withDefaults() Method

Auto-fill missing required fields with empty strings:

```php
$declaration = NewDeclaration::make()
    ->setEmployerTin('999999999')
    ->setLastName('ΠΑΠΑΔΟΠΟΥΛΟΣ')
    // Only set the fields you need
    ->withDefaults(); // All other fields become ''
```

## Full Migration Example

**v1.x Code:**
```php
use OxygenSuite\OxygenErgani\Http\Documents\WorkCard;
use OxygenSuite\OxygenErgani\Models\WorkCard\Card;
use OxygenSuite\OxygenErgani\Models\WorkCard\CardDetail;
use OxygenSuite\OxygenErgani\Enums\CardDetailType;

$card = Card::make()
    ->setEmployerTin('999999999')
    ->setBranchCode(0)
    ->addDetails(
        CardDetail::make()
            ->setTin('888888888')
            ->setLastName('DOE')
            ->setFirstName('JOHN')
            ->setType(CardDetailType::CHECK_IN)
    );

$response = (new WorkCard())->handle($card);
```

**v2.0 Code:**
```php
use OxygenSuite\OxygenErgani\Http\Documents\WorkCard\WorkCard; // Changed namespace
use OxygenSuite\OxygenErgani\Models\WorkCard\Card;
use OxygenSuite\OxygenErgani\Models\WorkCard\CardDetail;
use OxygenSuite\OxygenErgani\Enums\CardDetailType;

$card = Card::make()
    ->setEmployerTin('999999999')
    ->setBranchCode(0) // Still works, but now strictly typed
    ->addDetails(
        CardDetail::make()
            ->setTin('888888888')
            ->setLastName('DOE')
            ->setFirstName('JOHN')
            ->setType(CardDetailType::CHECK_IN)
    );

$response = (new WorkCard())->handle($card);
```

## Getting Help

- **Documentation**: [oxygensuite.github.io/oxygen-ergani](https://oxygensuite.github.io/oxygen-ergani)
- **Issues**: [GitHub Issues](https://github.com/oxygensuite/oxygen-ergani/issues)
- **Changelog**: [CHANGELOG.md](CHANGELOG.md)
