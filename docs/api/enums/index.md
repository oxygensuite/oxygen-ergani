# Enums

All enums in the package use the `HasLabels` trait, providing bilingual labels (English and Greek) for each value.

## Using Enums

```php
use OxygenSuite\OxygenErgani\Enums\Sex;

// Get value
$value = Sex::MALE->value;  // 0

// Get English label
$label = Sex::MALE->label();  // "Male"

// Get Greek label
$labelGreek = Sex::MALE->labelGreek();  // "Άνδρας"

// Get all labels for dropdowns
$labels = Sex::labels();       // ['0' => 'Male', '1' => 'Female']
$labelsGr = Sex::labelsGreek(); // ['0' => 'Άνδρας', '1' => 'Γυναίκα']
```

## Filtered Dropdowns

Some enums like `WorkTimeType` have category helper methods. Use `labelsFor()` to create dropdowns from subsets:

```php
use OxygenSuite\OxygenErgani\Enums\WorkTimeType;

// Get dropdown for schedule types (work + rest)
$scheduleDropdown = WorkTimeType::labelsFor(WorkTimeType::schedule(), 'greek');

// Get dropdown for all leave types
$leaveDropdown = WorkTimeType::labelsFor(WorkTimeType::leaves(), 'greek');
```

## Available Enums

### By Category

| Category | Enums |
|----------|-------|
| [Personal Information](./personal) | Sex, MaritalStatus |
| [Employment](./employment) | EmploymentStatus, WorkerType, EmploymentType, WorkLocation, ResponsiblePosition, IndividualContract, SpecialCase |
| [Work Time](./work-time) | WorkTimeType, WorkCardDelayReason, CardDetailType, DayOfWeek, WeekDays |
| [Loan/Borrowing](./loan) | LoanType, SalaryPaymentSource |
| [Termination](./termination) | FixedTermTerminationReason, NoticePeriodMonths |
| [Administrative](./administrative) | BasicsAcceptance, SettlementType, UserType, Environment |

## HasLabels Trait

The `HasLabels` trait provides:

| Method | Returns | Description |
|--------|---------|-------------|
| `label()` | string | English label for this case |
| `labelGreek()` | string | Greek label for this case |
| `labels()` | array | All English labels `[value => label]` |
| `labelsGreek()` | array | All Greek labels `[value => label]` |
| `labelsFor(array $cases, string $locale)` | array | Labels for subset of cases |

::: info Note
`HasLabels` only works with backed enums (not pure enums like `Environment`).
:::

## See Also

- [Models](/api/models/) - Model classes using these enums
- [Responses](/api/responses) - Response classes
