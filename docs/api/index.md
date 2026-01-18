# API Reference

This section provides detailed API documentation for the Oxygen Ergani package.

## Package Structure

```
OxygenSuite\OxygenErgani
├── Ergani                          # Main facade class
├── Enums/                          # Enum definitions
├── Exceptions/                     # Exception classes
├── Http/
│   ├── Client                      # Base HTTP client
│   ├── Documents/                  # Document submission classes
│   │   ├── WorkCard/               # Work card submissions
│   │   ├── WorkTime/               # Work time declarations
│   │   ├── Hiring/                 # E3 hiring forms
│   │   ├── Termination/            # E5 termination forms
│   │   ├── Dismissal/              # E6 dismissal forms
│   │   └── Modification/           # MA modification forms
│   └── Services/                   # Query services
├── Models/                         # Data models
├── Responses/                      # Response wrappers
└── Storage/                        # Token management
```

## Core Classes

### Ergani Facade

The `Ergani` class provides a simplified interface for common operations.

```php
use OxygenSuite\OxygenErgani\Ergani;
use OxygenSuite\OxygenErgani\Enums\Environment;

$ergani = new Ergani($accessToken, Environment::PRODUCTION);
```

See [Ergani Facade](/api/ergani) for full documentation.

## Documents

Documents handle submissions to the ERGANI API:

| Class | Action | Description |
|-------|--------|-------------|
| `WorkCard` | - | Employee check-ins/check-outs |
| `DailyWorkTime` | WTD | Daily work time declarations |
| `WeeklyWorkTime` | WTW | Weekly work time declarations |
| `Overtime` | OVT | Overtime declarations |

### Hiring Documents (E3)

| Class | Action | Description |
|-------|--------|-------------|
| `HiringNew` | WebE3N | New employee hiring |
| `HiringModification` | WebE3M | Transfer from another company |
| `HiringDeletion` | WebE3D | Employee lending FROM employer |
| `HiringWithLending` | WebE3PD | Hiring TO indirect employer |

### Termination Documents (E5)

| Class | Action | Description |
|-------|--------|-------------|
| `VoluntaryResignation` | WebE5N | Standard resignation |
| `ResignationNotification` | WebE5O | Notification of possible resignation |
| `ResignationAfterNotification` | WebE5AO | Confirmed resignation after E5O |
| `TerminationByDeath` | WebE5D | Termination due to death |
| `VoluntaryExitCompensation` | WebE5E | Voluntary exit with severance |
| `RetirementVoluntary` | WebE5S | Voluntary retirement |
| `RetirementMandatory` | WebE5DS | Mandatory retirement |

### Dismissal Documents (E6)

| Class | Action | Description |
|-------|--------|-------------|
| `DismissalWithoutNotice` | WebE6NXP | Immediate dismissal |
| `DismissalWithNotice` | WebE6NMP | Dismissal with notice |
| `RetirementDismissal` | WebE6SXP | Retirement dismissal |
| `EndOfLoan` | WebE6LD | End of loan arrangement |
| `TrialPeriodTermination` | WebE6LT | Trial period termination |
| `Transfer` | WebE6M | Employee transfer |

### Fixed-Term Documents (E7)

| Class | Action | Description |
|-------|--------|-------------|
| `FixedTermTermination` | WebE7N | Fixed-term contract termination |

### Modification Documents (MA)

| Class | Action | Description |
|-------|--------|-------------|
| `EmploymentModification` | WebMA | Modify regular employee |
| `BorrowedEmploymentModification` | WebMAD | Modify borrowed employee |

## Services

Services query ERGANI data:

| Class | Code | Description |
|-------|------|-------------|
| `EmployerInfo` | EX_BASE_01 | Employer details |
| `BranchInfo` | EX_BASE_02 | Branch details |
| `ParameterLookup` | EX_BASE_03 | Parameter lists |
| `MonthlyStatus` | EX_BASE_04 | Monthly employee status |

## API Reference Pages

- [Ergani Facade](/api/ergani) - Main facade class methods
- [Models](/api/models) - Data model classes
- [Enums](/api/enums) - Enumeration classes with values
- [Responses](/api/responses) - Response wrapper classes
- [Exceptions](/api/exceptions) - Exception classes
