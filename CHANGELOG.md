# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added

#### New Document Types
- **E3 Hiring Forms** - Complete hiring declaration support
  - `HiringNew` (E3N) - New employee hiring
  - `HiringModification` (E3M) - Employee transfer from another company
  - `HiringDeletion` (E3D) - Employee lending FROM direct employer
  - `HiringWithLending` (E3PD) - Employee hiring TO indirect employer
- **E5 Termination Forms** - Employee-initiated termination support
  - `VoluntaryResignation` (E5N) - Standard voluntary resignation
  - `ResignationNotification` (E5O) - Notification of possible resignation
  - `ResignationAfterNotification` (E5AO) - Confirmed resignation after E5O
  - `TerminationByDeath` (E5D) - Termination due to employee death
  - `VoluntaryExitCompensation` (E5E) - Voluntary exit with severance
  - `RetirementVoluntary` (E5S) - Voluntary retirement with compensation
  - `RetirementMandatory` (E5DS) - Mandatory retirement
- **E6 Dismissal Forms** - Employer-initiated termination support
  - `DismissalWithoutNotice` (E6NXP) - Immediate dismissal
  - `DismissalWithNotice` (E6NMP) - Dismissal with notice period
  - `RetirementDismissal` (E6SXP) - Employer-initiated retirement
  - `EndOfLoan` (E6LD) - End of employee loan arrangement
  - `TrialPeriodTermination` (E6LT) - Trial period termination
  - `Transfer` (E6M) - Employee transfer to another company
- **E7 Fixed-Term Termination** - Fixed-term contract termination support
  - `FixedTermTermination` (E7N) - Contract expiration or early termination
- **Employment Modification Forms** - Employment terms modification support
  - `EmploymentModification` (WebMA) - Regular employee modifications
  - `BorrowedEmploymentModification` (WebMAD) - Borrowed employee modifications
- **Overtime Documents** - Overtime declaration support
  - `WorkTimeOvertime` - Regular overtime
  - `WorkTimeOvertimeDrivers` - Driver-specific overtime
  - `WorkTimeOvertimeRetrospective` - Retrospective overtime
- **Working Status Change** - Employee status update document

#### Query Services
- `EmployerInfo` (EX_BASE_01) - Retrieve employer details
- `BranchInfo` (EX_BASE_02) - Retrieve branch details
- `ParameterLookup` (EX_BASE_03) - Query parameter lists (work types, nationalities, etc.)
- `MonthlyStatus` (EX_BASE_04) - Query monthly employee status

#### Response Collections
- `BranchCollection` - Typed collection for branch responses with search/filter methods
- `ParameterCollection` - Typed collection for parameter responses with O(1) lookup

#### Model Factory System
- Laravel-inspired factory system for generating test data
- `GreekProvider` for Faker with valid Greek identifiers (AFM, AMKA, ID numbers)
- Factory state methods for common scenarios (fixed-term, part-time, foreign nationals, etc.)
- Factories for all model types (WorkCard, WorkTime, Hiring, Termination, Dismissal, Modification, Overtime)

#### Enums with Bilingual Labels
- `HasLabels` trait for backed enums with English/Greek labels
- New enums: `Sex`, `MaritalStatus`, `EmploymentStatus`, `WorkerType`, `EmploymentType`, `WorkLocation`, `SpecialCase`, `LoanType`, `SalaryPaymentSource`, `FixedTermTerminationReason`, `NoticePeriodMonths`, `WorkTimeType`, `DayOfWeek`, `WeekDays`, `BasicsAcceptance`, `SettlementType`, `IndividualContract`, `ResponsiblePosition`
- Labels accessible via `->label()`, `->labelGreek()`, `::labels()`, `::labelsGreek()`

#### Developer Experience
- `withDefaults()` method on models to auto-fill missing fields with empty strings
- Greek float casting with configurable precision (`'greek_float'` for 2 decimals, `'greek_float:1'` for 1 decimal)
- Configurable cache directory for `FileToken` via constructor options or `FileToken::setDirectory()`
- `.htaccess` protection for default `.cache/` directory
- **DateTime support** for all date setter methods - accept both `DateTime` objects and strings, automatically formatted to the expected format

#### Quality Assurance
- PHPStan level 7 static analysis
- Mutation testing with Infection
- Comprehensive test coverage for all document types

#### Documentation
- VitePress documentation site with guides and API reference
- Full coverage of all document types, models, enums, and services

### Changed

#### Breaking Changes
- **PHP requirement raised to ^8.2** (was ^8.1)
- **PHPUnit requirement raised to ^12.0** (was ^11.0)
- **WorkCard moved to subfolder**: `Http\Documents\WorkCard` → `Http\Documents\WorkCard\WorkCard`
- **DailyWorkTime removed**: Use `WorkTime\DailySchedule` or `WorkTime\WeeklySchedule` instead
- **WTO models renamed to WorkTime**:
  - `Models\WTO\WTO` → `Models\WorkTime\WorkTime`
  - `Models\WTO\WTOAnalytics` → `Models\WorkTime\WorkTimeEntry`
  - `Models\WTO\WTOEmployee` → `Models\WorkTime\WorkTimeEmployee`
- **Numeric fields require strict types**: Float fields must be passed as floats, integer fields as integers
- **Code style changed from PSR-12 to PER**
- **CardDetail getter renamed**: `getTinNumber()` → `getTin()` for consistency with setter `setTin()`

#### Non-Breaking Changes
- Documents reorganized into subfolders (`Hiring/`, `Termination/`, `Dismissal/`, `Modification/`, `Overtime/`, `WorkCard/`, `WorkingStatus/`)
- Models reorganized into subfolders matching document structure
- Extracted shared traits for declaration models (`HasExtendedEmploymentDetails`, `HasDypaPrograms`, `HasTrialPeriod`, `HasInsurance`, `HasWagePayment`, `HasAcceptanceFiles`, `HasSalary`, `HasCompensation`, `HasFormFile`, etc.)
- Generic `Collection` base class extracted for response collections

## [1.1.1] - 2024-12-17

### Changed

- Updated README documentation

### Fixed

- Removed forgotten test code

## [1.1.0] - 2024-06-03

### Changed

- Updated README with detailed usage instructions and token management guide

### Fixed

- Invalid environment handling in `FileToken`

## [1.0.0] - 2024-03-14

First stable release.

### Fixed

- Handle 400 Bad Request responses with `ErganiException`

## [0.1.1-alpha] - 2024-02-XX

### Added

- Token caching based on environment
- `toArray()` method to `HasAttributes` trait

### Changed

- Renamed `setTinNumber` to `setTin` in `CardDetail`

### Fixed

- Allow null values for card detail comments

## [0.1.0-alpha] - 2024-02-19

Initial alpha release.

### Added

- Work Card submissions (check-in/check-out)
- JWT authentication (login, refresh, logout)
- Token management system (`FileToken`, `InMemoryToken`)
- Work Time Organization (WTO) documents and models
- `CancelSubmittedDocument` for canceling submissions
- `LookupSubmissions` for querying past submissions
- `Ergani` facade class for simplified API access
- Timezone support and utility methods for DateTime handling
- `HasAttributes` trait for model data handling
- Environment support (TEST/PRODUCTION)
- Basic exception handling

### Changed

- Renamed `Authentication` class to `AuthenticationLogin`
- Renamed `clear` method to `failedAuthentication` in Token

[Unreleased]: https://github.com/oxygensuite/oxygen-ergani/compare/v1.1.1...HEAD
[1.1.1]: https://github.com/oxygensuite/oxygen-ergani/compare/v1.1.0...v1.1.1
[1.1.0]: https://github.com/oxygensuite/oxygen-ergani/compare/v1.0.0...v1.1.0
[1.0.0]: https://github.com/oxygensuite/oxygen-ergani/compare/v0.1.1-alpha...v1.0.0
[0.1.1-alpha]: https://github.com/oxygensuite/oxygen-ergani/compare/v0.1.0-alpha...v0.1.1-alpha
[0.1.0-alpha]: https://github.com/oxygensuite/oxygen-ergani/releases/tag/v0.1.0-alpha
