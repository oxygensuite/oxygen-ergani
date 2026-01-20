# Models

Models are data classes that represent ERGANI form data. They use a fluent interface for building and support automatic type conversion.

## Base Concepts

### HasAttributes Trait

All models use the `HasAttributes` trait providing:

- Fluent setters that return `$this`
- Automatic type casting via `$casts` property
- Ordered array output via `$expectedOrder` property
- Greek float formatting for decimal values

### DateTime Support

All date setter methods accept both `DateTime` objects and strings:

```php
use DateTime;

// Both are equivalent:
$declaration->setBirthDate('15/01/1990');
$declaration->setBirthDate(new DateTime('1990-01-15'));

// Works with DateTimeImmutable too:
$declaration->setHiringDate(new DateTimeImmutable('2025-01-20'));
```

When a `DateTime` is passed, it's automatically formatted to the expected format (usually `DD/MM/YYYY`).

### Factory Pattern

Models support a factory pattern for testing:

```php
$declaration = NewDeclaration::factory()->make();

// With overrides
$declaration = NewDeclaration::factory()->make([
    'f_afm' => '123456789',
]);

// Multiple instances
$declarations = NewDeclaration::factory(3)->make();
```

### Greek Float Casting

Decimal fields use Greek format (`1.234,56`) via the `$casts` property:

```php
protected array $casts = [
    'f_apodoxes' => 'greek_float',      // 2 decimals
    'f_week_hours' => 'greek_float:1',  // 1 decimal
];
```

**Input:** `1500.00` → **Output:** `"1.500,00"`

## Available Models

| Category | Models |
|----------|--------|
| [Work Cards](./work-cards) | Card, CardDetail |
| [Work Time](./work-time) | WorkTime, WorkTimeEmployee, WorkTimeEntry |
| [Hiring (E3)](./hiring) | NewDeclaration, ModificationDeclaration, LendingDeclaration, DeletionDeclaration |
| [Termination (E5/E7)](./termination) | VoluntaryResignationDeclaration, NotificationDeclaration, DeathTerminationDeclaration, FixedTermTerminationDeclaration, etc. |
| [Dismissal (E6)](./dismissal) | DismissalWithoutNoticeDeclaration, DismissalWithNoticeDeclaration, TransferDeclaration, etc. |
| [Modifications (MA)](./modifications) | ModificationDeclaration, BorrowedModificationDeclaration |

## Common Fields

All declaration models share [common personal information fields](./common-fields).

## See Also

- [Enums](/api/enums/) - Enum values used by models
- [Responses](/api/responses) - API response classes
