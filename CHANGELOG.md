# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [2.2.0] - 2026-10-15

### Added

- `XsdComplianceTest` validates the factory payload of every document in `bin/schema-map.php` against the official XSD in `xsd/`
- Maintainer tools `composer xsd:sync` (compare `xsd/` with an ERGANI help-files bundle) and `composer submit:check` (validate every document against ERGANI's own validator using invalid business data)
- `WorkTimeFactory::weekly()`, `WorkTimeFactory::leave()` and `WorkTimeFactory::overtime()` states
- `RealWorkingDiary` (EX_BASE_07) service for real employment diary entries with `RealWorkingResponse` and `Ergani::getRealWorkingDiary()`
- `DigitalWorkTimeStatus` (EX_BASE_08) service for the current digital work time organization (ΨΟΧΕ) status with `DigitalWorkTimeResponse` and `Ergani::getDigitalWorkTimeStatus()`
- **E14 Parental Leave** - `ParentalLeave` (WebE14) and `ParentalLeaveCorrection` (WebE14C) documents with `ParentalLeaveDeclaration`, `ParentalLeaveChild` and `ParentalLeaveDate` models, `ParentalLeaveCategory` and `LeaveGrantMethod` enums, factories, and `Ergani::sendParentalLeave()` / `sendParentalLeaveCorrection()`
- **E3.1 Community Programs** - `CommunityProgramEmployment` (WebE31) and `CommunityProgramEmploymentCorrection` (WebE31C) with `CommunityProgramDeclaration`, factory and `Ergani::sendCommunityProgramEmployment()` / `sendCommunityProgramEmploymentCorrection()`
- **E3.2 Theoretical Training** - `TheoreticalTraining` (WebE32) and `TheoreticalTrainingCorrection` (WebE32C) with `TheoreticalTrainingDeclaration`, factory and `Ergani::sendTheoreticalTraining()` / `sendTheoreticalTrainingCorrection()`; shared E3.1/E3.2 fields live in `Models\Programs\Concerns` traits
- **E13 Motorcycle Use** - `MotorcycleUse` (WebE13) document with `MotorcycleUseDeclaration` and `MotorcycleVehicle` models, `VehicleOwner` enum, factories and `Ergani::sendMotorcycleUse()`
- **Urgent Hiring (FastAna)** - `UrgentHiring` (FastAna) and `UrgentHiringCorrection` (FastAnaC) documents with `UrgentHiringDeclaration` model, factory and `Ergani::sendUrgentHiring()` / `sendUrgentHiringCorrection()`
- **E3.5 follow-up forms** - `InternshipCorrection` (WebE35C), `InternshipTermination` (WebE35D) with `InternshipTerminationDeclaration`, and `InternshipScheduleChange` (WebE35T) with `InternshipScheduleChangeDeclaration`, plus factories and `Ergani::sendInternshipCorrection()` / `sendInternshipTermination()` / `sendInternshipScheduleChange()`
- `InternshipDeclaration::getSchedule()`, `getSplitSchedule()` and `getBreakSchedule()` getters
- `InternshipDeclaration::setInsuranceAgency()` / `getInsuranceAgency()` for the new `f_insurance_agency` field (E35_v3)

### Changed

- **Overtime documents now take a `WorkTime`** (entries of type `WorkTimeType::OVERTIME`) and send the `WTOS` / `WTO` structure: `WorkTimeOvertime` (WTOOv), `WorkTimeOvertimeRetrospective` (WTOOvA), `WorkTimeOvertimeDrivers` (WTOOvD) and `Ergani::sendOvertime()` / `sendOvertimeRetrospective()` / `sendOvertimeDrivers()`. The previous `Overtimes` / `Overtime` payload was rejected by ERGANI on both environments, so no overtime submission could succeed
- Exclude `bin/` (maintainer CLI tools) from the published package
- Use ERGANI's new action codes for construction and internship submissions: `ConstructionWorkDeclaration` → `WebE12`, `ConstructionWorkCensus` → `WebE12Ap`, `Internship` → `WebE35` (the old `E12`, `E12Apogr` and `57` codes are rejected by both environments)
- Update the internship schema to `E35_v3.xsd`
- Move `Internship` submission logic into an abstract `InternshipDocument` base, and the schedule, certifier, legal representative and file accessors of `InternshipDeclaration` into traits under `Models\Internship\Concerns` (public API unchanged)
- Refresh `E3N_v1.xsd`, `E3M_v1.xsd` and `E3D_v1.xsd` from ERGANI's latest help files (E3N/E3M no longer allow employment status `3` (on-demand); E3M allows employment relationship `2`/`3`; E3D salary and collective agreement fields accept empty values)

### Deprecated

- `Models\Overtime\Overtime`, `Models\Overtime\OvertimeEmployee` and their factories (legacy overtime structure); use `WorkTime` with `WorkTimeType::OVERTIME` entries. They will be removed in v3

### Fixed

- Work time factories no longer emit empty `f_day` / `f_date` / `f_year` / `f_req_days`, which ERGANI rejects for daily, weekly and leave submissions
- `schema:check` validates daily work time and overtime against `WTO_v1.xsd`; leave moved to a new `WTOLeave` group (`WTO_v2.xsd`)
- MA (`EmploymentModification`) sent supplementary insurance as `EpikourikiSelections` / `f_kod_epikourikis`; `MA_v1.xsd` expects `Epikourikes` → `EpikourikesMA` / `f_epikouriki_kod`, so ERGANI rejected every MA with supplementary insurance
- MAD (`BorrowedModificationDeclaration`) sent the `f_borrow_*` fields after `f_eidos_dieuthethshs`; they now follow `MAD_v1.xsd` order
- E3PD (`LendingDeclaration`) no longer sends `f_atomikh_symbash`, which only exists in the not-yet-active `E3PD_v2.xsd`; its factory and docs no longer use employment type `BORROWED` (3), which `E3PD_v1.xsd` rejects
- `NewDeclarationFactory::withUnpredictableSchedule()` no longer sets employment status `3` (on-demand), which the current E3N/E3M schemas reject (confirmed on production); `EmploymentStatus::ON_DEMAND` is now documented as not accepted until ERGANI activates the newer `E3N_v2.xsd` / `E3M_v2.xsd` / `MA_v2.xsd` schema files
- `DayOfWeek::SUNDAY` now uses value `0` instead of `7`
- `GreekProvider::amka()` now appends a valid Luhn check digit (most generated AMKAs were rejected by ERGANI)
- `InternshipDeclaration` factory now generates schema-valid data (KAD codes, DYPA service, tax office, ID type and legal representative AFM)

## [2.1.0] - 2026-08-08

### Added

- Optional `UserType` parameter on `Token`, `FileToken`, `InMemoryToken` and their `fake()` helpers, so token managers can authenticate as ERGANI (`02`) or EFKA (`03`) users (`UserType::EXTERNAL` remains the default)
- `ClientConfig::setDefault()` / `getDefault()` / `resetDefault()` global default HTTP configuration, applied to every client created without an explicit config — including the automatic login/refresh calls inside token managers

### Changed

- Include the user type in `Token::cacheIdentifier()` and `FileToken::generateFilename()` hashes; stored tokens and cache prefixes are refreshed once on upgrade (the package re-authenticates automatically; `FileToken::forgetAllTokens()` can remove stale token files — it sweeps only the static/default token directory, not instance-level `cache_dir` directories, and also removes current tokens; similarly, orphaned PSR-16 data-cache entries under the old prefix can be removed with `Ergani::flushCache($cache)`)
- Consumer subclasses that override the static `FileToken::fake()` / `InMemoryToken::fake()` helpers must add the new optional `UserType` parameter to their override signature to remain declaration-compatible

## [2.0.3] - 2026-06-13

### Added

- Add `Ergani::make()` static constructor for fluent one-liners (e.g. `Ergani::make()->sendHiringNew($declaration)`)
- Generate LLM-friendly documentation (`llms.txt`, `llms-full.txt`, per-page Markdown) and a sitemap for the docs site
- Show last-updated dates and code group icons on docs pages; use clean URLs

### Changed

- Use the `Ergani` facade in the homepage and Getting Started quick examples

## [2.0.2] - 2026-06-12

### Added

- Add `logout()` method to the `Ergani` facade for revoking refresh tokens server-side

## [2.0.1] - 2026-06-12

> [!NOTE]
> The `v2.0.0` tag exists on GitHub but is not installable via Packagist (the tag was re-created during release and Packagist blocks re-tagged versions). Use `^2.0.1` instead.

### Changed

- Allow PHPUnit `^11.5` and Infection `^0.29.14` as dev dependency alternatives so the test suite can run on PHP 8.2 (PHPUnit 12 and Infection 0.32 require PHP 8.3)
- CI test matrix resolves dependencies per PHP version instead of installing the lock file

## [2.0.0] - 2026-06-12

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
- **E12 Construction Forms** - Construction work declaration support
  - `ConstructionWorkDeclaration` (E12) - Construction work personnel declaration
  - `ConstructionWorkCensus` (E12Apogr) - Construction work census
- **SixthDay Declaration** - Sixth day / extra shift declaration
  - `SixthDay` (SixthDay) - Employment declaration for extra shift
- **Pre-Announcement Exemption** - Pre-announcement exemption declaration
  - `PreAnnouncementExemption` (ExProan) - Exemption from pre-announcement requirement
- **E3.5 Internship Declaration** - Internship start/modification support
  - `Internship` (57) - Internship declaration with ~100 fields and schedule helpers

#### Ergani Facade Extension
- Extended `Ergani` facade class with 40+ methods for all document types
- Trait-based organization in `src/Ergani/Concerns/` for maintainability:
  - `SendsHiringDocuments` - E3 hiring methods (`sendHiringNew()`, `sendHiringModification()`, `sendHiringDeletion()`, `sendHiringWithLending()`)
  - `SendsTerminationDocuments` - E5 + E7 termination methods (`sendVoluntaryResignation()`, `sendResignationNotification()`, `sendResignationAfterNotification()`, `sendTerminationByDeath()`, `sendVoluntaryExitCompensation()`, `sendRetirementVoluntary()`, `sendRetirementMandatory()`, `sendFixedTermTermination()`)
  - `SendsDismissalDocuments` - E6 dismissal methods (`sendDismissalWithoutNotice()`, `sendDismissalWithNotice()`, `sendRetirementDismissal()`, `sendEndOfLoan()`, `sendTrialPeriodTermination()`, `sendTransfer()`)
  - `SendsWorkTimeDocuments` - Work time methods (`sendDailyWorkTime()`, `sendWeeklyWorkTime()`, `sendDailyWorkTimeDrivers()`, `sendDailyWorkTimeRetrospective()`, `sendWorkTimeLeave()`, `sendWorkTimeLeaveCorrection()`)
  - `SendsOvertimeDocuments` - Overtime methods (`sendOvertime()`, `sendOvertimeDrivers()`, `sendOvertimeRetrospective()`)
  - `SendsModificationDocuments` - MA methods (`sendEmploymentModification()`, `sendBorrowedEmploymentModification()`)
  - `SendsConstructionDocuments` - Construction methods (`sendConstructionWork()`, `sendConstructionWorkCensus()`)
  - `SendsSixthDayDocuments` - Sixth day method (`sendSixthDayDeclaration()`)
  - `SendsPreAnnouncementDocuments` - Pre-announcement method (`sendPreAnnouncementExemption()`)
  - `SendsInternshipDocuments` - Internship method (`sendInternshipDeclaration()`)
  - `ManagesDocuments` - Document management methods (`cancelDocument()`, `getSubmissions()`, `getSchema()`, `getDocumentPdf()`)
- `getMonthlyStatus()` method for retrieving employee status reports

#### Query Services
- `EmployerInfo` (EX_BASE_01) - Retrieve employer details
- `BranchInfo` (EX_BASE_02) - Retrieve branch details
- `ParameterLookup` (EX_BASE_03) - Query parameter lists (work types, nationalities, etc.)
- `MonthlyStatus` (EX_BASE_04) - Query monthly employee status
- `WorkforceStatus` (EX_BASE_05) - Query workforce movement status
- `AcceptanceStatus` (EX_BASE_06) - Query essential terms acceptance status

#### PSR-16 Caching
- Opt-in PSR-16 caching for `getEmployerInfo()`, `getBranches()`, and `getParameters()` via the `Ergani` facade
- `FileCache` - File-based PSR-16 implementation with TTL support and configurable directory (mirrors `FileToken` pattern)
- `InMemoryCache` - Array-based PSR-16 implementation for single-request use or testing
- `NullCache` - No-op PSR-16 implementation for explicitly disabling caching
- Cache clearing methods: `clearCache()`, `flushCache()`, `clearEmployerCache()`, `clearBranchCache()`, `clearParameterCache()`
- `clearExpired()` method on `FileCache` and `InMemoryCache` for maintenance
- Auto-derived cache prefix from `TokenManager` credentials (sha256 of username:password)
- `Token::cacheIdentifier()` method for generating cache-safe credential hashes

#### Response Collections
- `BranchCollection` - Typed collection for branch responses with search/filter methods
- `ParameterCollection` - Typed collection for parameter responses with O(1) lookup

#### Enums with Bilingual Labels
- `HasLabels` trait for backed enums with English/Greek labels
- New enums: `Sex`, `MaritalStatus`, `EmploymentStatus`, `WorkerType`, `EmploymentType`, `WorkLocation`, `SpecialCase`, `LoanType`, `SalaryPaymentSource`, `FixedTermTerminationReason`, `NoticePeriodMonths`, `WorkTimeType`, `DayOfWeek`, `WeekDays`, `BasicsAcceptance`, `SettlementType`, `IndividualContract`, `ResponsiblePosition`, `WorkCardDelayReason`
- Labels accessible via `->label()`, `->labelGreek()`, `::labels()`, `::labelsGreek()`
- `HasLabels::labelsFor()` method to get labels for a subset of enum cases
- `WorkTimeType` category helpers: `work()`, `rest()`, `schedule()`, `dayLeaves()`, `hourlyLeaves()`, `leaves()`, `overtime()` and corresponding instance check methods (`isWork()`, `isLeave()`, etc.)

#### Model Factory System
- Laravel-inspired factory system for generating test data
- `GreekProvider` for Faker with valid Greek identifiers (AFM, AMKA, ID numbers)
- Factory state methods for common scenarios (fixed-term, part-time, foreign nationals, etc.)
- Factories for all model types (WorkCard, WorkTime, Hiring, Termination, Dismissal, Modification, Overtime, Construction, SixthDay, PreAnnouncement, Internship)
- Factories require the dev-only `fakerphp/faker` package (listed in composer `suggest`); `Factory::fake()` throws a clear `RuntimeException` when Faker is not installed

#### Developer Experience
- `withDefaults()` method on models to auto-fill missing fields with empty strings
- Greek float casting with configurable precision (`'greek_float'` for 2 decimals, `'greek_float:1'` for 1 decimal)
- Configurable cache directory for `FileToken` via constructor options or `FileToken::setDirectory()`
- `.htaccess` protection for default `.cache/` directory
- **DateTime support** for all date setter methods - accept both `DateTime` objects and strings, automatically formatted to the expected format
- `Collection::toArray()` and `Response::toArray()` methods for easy serialization
- `bin/check-enum` CLI tool to compare enums against the live ERGANI API (use `composer enum:check -- --all`)
- `bin/check-schema` CLI tool to compare document schemas against local XSD files (use `composer schema:check -- --all`), with `--errors-only`, `--show-order`, `--list`, and `--coverage` options
- CLI tools cache API responses (use `--fresh` to bypass) and share a common `bin/bootstrap.php` for credential loading from `.env` or environment variables
- `.env.example` template for CLI tool credentials

#### Documentation
- VitePress documentation site with guides and API reference
- Full coverage of all document types, models, enums, and services
- Documentation for `pdf()` method to retrieve submitted documents as PDF
- Restructured API reference with separate pages for each enum and model category
- Model Factories guide page (`docs/guide/factories.md`)

#### Quality Assurance
- PHPStan level 7 static analysis
- Mutation testing with Infection
- Comprehensive test coverage for all document types

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
- **`flushCache()` is now static**: `$ergani->flushCache()` → `Ergani::flushCache($cache)` - no credentials needed to flush entire cache

#### Non-Breaking Changes
- Documents reorganized into subfolders (`Hiring/`, `Termination/`, `Dismissal/`, `Modification/`, `Overtime/`, `WorkCard/`)
- Models reorganized into subfolders matching document structure
- Extracted shared traits for declaration models (`HasExtendedEmploymentDetails`, `HasDypaPrograms`, `HasTrialPeriod`, `HasInsurance`, `HasWagePayment`, `HasAcceptanceFiles`, `HasSalary`, `HasCompensation`, `HasFormFile`, etc.)
- Generic `Collection` base class extracted for response collections
- Updated MAD (WebMAD) and E3PD XSD schemas to match the live ERGANI API
- composer.json `type` corrected from `package` to `library`

### Removed

- **Working Status Change** (`WKChgWK`) - Document type, models, factories, and XSD removed. The ERGANI API no longer includes `WKChgWK` in its submissions list; its functionality has been absorbed into Employment Modification forms (MA/MAD). Use `EmploymentModification` (WebMA) or `BorrowedEmploymentModification` (WebMAD) instead.
  - Removed `WorkingStatusChange` document class
  - Removed `WorkingStatus` and `WorkingStatusEmployee` models
  - Removed `WorkingStatusFactory` and `WorkingStatusEmployeeFactory` factories
  - Removed `SendsWorkingStatusDocuments` trait and `sendWorkingStatusChange()` facade method
  - Removed `WorkingStatusChange_v1.xsd` schema file

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

[2.2.0]: https://github.com/oxygensuite/oxygen-ergani/compare/v2.1.0...v2.2.0
[2.1.0]: https://github.com/oxygensuite/oxygen-ergani/compare/v2.0.3...v2.1.0
[2.0.3]: https://github.com/oxygensuite/oxygen-ergani/compare/v2.0.2...v2.0.3
[2.0.2]: https://github.com/oxygensuite/oxygen-ergani/compare/v2.0.1...v2.0.2
[2.0.1]: https://github.com/oxygensuite/oxygen-ergani/compare/v2.0.0...v2.0.1
[2.0.0]: https://github.com/oxygensuite/oxygen-ergani/compare/v1.1.1...v2.0.0
[1.1.1]: https://github.com/oxygensuite/oxygen-ergani/compare/v1.1.0...v1.1.1
[1.1.0]: https://github.com/oxygensuite/oxygen-ergani/compare/v1.0.0...v1.1.0
[1.0.0]: https://github.com/oxygensuite/oxygen-ergani/compare/v0.1.1-alpha...v1.0.0
[0.1.1-alpha]: https://github.com/oxygensuite/oxygen-ergani/compare/v0.1.0-alpha...v0.1.1-alpha
[0.1.0-alpha]: https://github.com/oxygensuite/oxygen-ergani/releases/tag/v0.1.0-alpha
