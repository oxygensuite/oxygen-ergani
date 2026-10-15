# CLI Tools

::: info For contributors
These tools live in the repository's `bin/` folder and run as Composer scripts of this package. They are excluded from the published package, so clone the repository to use them.
:::

The repository includes four maintainer tools for keeping the package in sync with ERGANI:

| Command | What it checks | Needs credentials |
|---------|----------------|-------------------|
| `composer enum:check` | Enums against ERGANI's parameter lists | Yes |
| `composer schema:check` | Local XSDs against the live schema endpoint, and action-code coverage | Yes |
| `composer xsd:sync` | Local XSDs against a downloaded ERGANI help-files bundle | No |
| `composer submit:check` | Every document's payload against ERGANI's own validator, using invalid business data | Yes |

`composer test` also runs `XsdComplianceTest`, which validates every document's factory payload against the XSDs in `xsd/` offline.

## Configuration

Both tools read credentials from a `.env` file in the project root, or from environment variables:

```ini
# .env
ERGANI_USERNAME=your_username
ERGANI_PASSWORD=your_password
ERGANI_ENV=test  # or 'production'
```

Alternatively, set environment variables directly:

::: code-group

```bash [Bash]
ERGANI_USERNAME=xxx ERGANI_PASSWORD=xxx composer enum:check -- --all
```

```powershell [PowerShell]
$env:ERGANI_USERNAME="xxx"; $env:ERGANI_PASSWORD="xxx"; composer enum:check -- --all
```

:::

---

## Schema Check

Compares document field schemas from the live API against local XSD files. Useful for detecting when the ERGANI API adds, removes, or reorders fields.

Documents that share the same XSD (e.g., the 6 daily work time and overtime documents in the WTO group, validated against `WTO_v1.xsd`; leave uses the `WTOLeave` group and `WTO_v2.xsd`) are grouped together, and their API fields are unioned for a single comparison per XSD.

### Usage

```bash
# Check a specific schema group
composer schema:check E6LT

# Check a group by document name (resolves to its group)
composer schema:check DailyWorkTime    # → resolves to WTO group

# Check all schemas
composer schema:check -- --all

# Show only schemas with mismatches
composer schema:check -- --all --errors-only

# Show field order differences
composer schema:check -- --all --show-order

# List all available schema groups
composer schema:check -- --list

# Check API coverage (which submissions we implement)
composer schema:check -- --coverage

# Clear cache and re-fetch from API
composer schema:check -- --all --fresh
```

### Flags

| Flag | Description |
|------|-------------|
| `--all` | Check all schema groups |
| `--list` | List available schema groups and their documents |
| `--errors-only` | With `--all`, suppress passing groups and only show mismatches |
| `--show-order` | Show field order differences when fields match but order differs |
| `--coverage` | Cross-reference implemented documents against the API submissions list |
| `--fresh` | Clear cached API responses and re-fetch from the live API |
| `--help` | Show usage information |

### Output

For single-document groups:

```
E6LT (xsd/E6LT_v1.xsd)
------------------------
✓ Fields match: 47
✓ Order matches
```

For multi-document groups (e.g., WTO with 6 documents sharing one XSD):

```
WTO (xsd/WTO_v1.xsd)
  Documents: DailyWorkTime, DailyWorkTimeA, DailyWorkTimeD, Overtime, ...
---------------------
✓ Fields match: 13 (union of 6 API schemas)
```

When mismatches are found:

```
E3N (xsd/E3N_v1.xsd)
---------------------
✓ Fields match: 85
⚠ In API but not in XSD: 2
    + f_new_field_1
    + f_new_field_2
✗ In XSD but not in API: 1
    - f_removed_field
```

### Name Resolution

You can use either a **group name** or a **document name** as the argument:

- `composer schema:check WTO` — checks the entire WTO group (6 daily/overtime documents)
- `composer schema:check DailyWorkTime` — resolves to the WTO group and checks all 6
- `composer schema:check E6LT` — single-document group, checks just that one

Use `--list` to see all groups, documents, and their XSD files.

### Coverage Report

The `--coverage` flag fetches the API's available submissions list and cross-references it against implemented document classes:

```bash
composer schema:check -- --coverage
```

```
API Coverage Report
========================================

✓ Covered: 30/32 API submissions
    WTODaily → DailyWorkTime
    WebE3N → E3N
    ...

⚠ Not covered: 2 API submissions
    NewAction — Νέα Υποβολή Δήλωσης
    ...
```

---

## Enum Check

Compares local PHP enum values against the live ERGANI API parameter lists. Detects when the API adds new values or removes existing ones.

### Usage

```bash
# Check a specific enum
composer enum:check WorkCardDelayReason

# Check all enums
composer enum:check -- --all

# List available enums
composer enum:check -- --list

# Clear cache and re-fetch from API
composer enum:check -- --all --fresh
```

### Flags

| Flag | Description |
|------|-------------|
| `--all` | Check all enums |
| `--list` | List available enums with their parameter types and classes |
| `--fresh` | Clear cached API responses and re-fetch from the live API |
| `--help` | Show usage information |

### Available Enums

| Enum | Parameter Type | Description |
|------|---------------|-------------|
| `WorkCardDelayReason` | `WorkCardDelayReason` | Work card delay reason codes |
| `WorkTimeType` | `WorkTimeType` | Work time type codes (work, rest, leave, overtime) |

### Output

```
WorkCardDelayReason
-------------------
✓ Matched: 5
✓ Enum is in sync with API
```

When differences are found:

```
WorkTimeType
------------
✓ Matched: 38
⚠ Missing in enum: 2
    - ΝΕΟΣ1 (Νέος Τύπος Εργασίας)
    - ΝΕΟΣ2 (Νέος Τύπος Αδείας)
✗ Extra in enum (not in API): 1
    - ΠΑΛΑΙΟΣ
```

---

## Caching

Both tools cache API responses locally (in `.cache/data/`) with a 1-day TTL to speed up repeated runs. On subsequent invocations the cached data is used instead of calling the live API, making the typical fix-and-recheck loop much faster.

Use the `--fresh` flag to clear the cache and force a fresh API fetch:

```bash
composer schema:check -- --all --fresh
composer enum:check -- --all --fresh
```

::: tip
Authentication (login/token refresh) still happens on every run — only the schema and parameter data is cached.
:::

---

## Exit Codes

Both tools exit with code `0` when all checks pass, and `1` when any mismatch is found. This makes them suitable for CI pipelines:

```yaml
# Example CI step
- run: composer schema:check -- --all
- run: composer enum:check -- --all
```

---

## xsd:sync

Compares the `xsd/` folder with an ERGANI help-files bundle (the `HELP_FILES_SAMPLES…` download that comes with each
interoperability guide update). No credentials are needed.

```bash
composer xsd:sync -- "path/to/HELP_FILES_SAMPLES_trial2_v3"
composer xsd:sync -- "path/to/bundle" --changed-only   # hide identical files
composer xsd:sync -- "path/to/bundle" --apply          # copy changed files into xsd/
```

It reports:

- **Identical / changed / missing** for every XSD in `xsd/`, with field-level differences for changed files
  (added and removed elements, changed enumerations, patterns, lengths and occurrence)
- **Newer schema versions** in the bundle, e.g. `E3N_v1.xsd -> E3N_v2.xsd`. Schema file versions are independent of the
  bundle's own release number, and ERGANI often publishes a newer `_vN.xsd` before activating it, so confirm with
  `submit:check` which version ERGANI validates against before switching
- **Bundle schemas not used by the package** (possible new or legacy forms)

It exits with `1` when changed files are found (without `--apply`).

---

## submit:check

Checks every document against ERGANI's own validator. For each document in `bin/schema-map.php`, the package builds
its normal payload from the model factory, then replaces the business identifiers with values that pass the XSD but
can never pass ERGANI's business rules:

| Field | Replaced with |
|-------|---------------|
| every `*afm*` field | `123456789` (wrong check digit) |
| every `*amka*` field | `01010101011` (wrong Luhn check digit) |
| `f_aa_pararthmatos` | `999` (branch that does not exist) |
| `f_ypiresia_sepe` / `f_ypiresia_oaed` | `99999` / `999999` (services that do not exist) |

ERGANI validates the XSD before the business rules, so the response tells you whether the package's structure is
accepted:

| Result | Meaning |
|--------|---------|
| `STRUCTURE OK` | Rejected by a business rule: the structure passed ERGANI's XSD validation |
| `XSD REJECTED` | ERGANI rejected the structure: the package sends an invalid payload |
| `NOT AVAILABLE` | The form is not enabled in that environment (`Type … not valid` / `XSD File not found`) |
| `SKIPPED` | Fewer than two values could be made invalid, so it is not safe to send |

```bash
composer submit:check                               # trial environment (default)
composer submit:check -- --production               # production environment
composer submit:check -- --only=E14,FastAna         # specific documents
composer submit:check -- --list                     # document names
```

::: warning Real submissions
`submit:check` sends real requests to ERGANI. Every payload is built to be rejected, and anything that is unexpectedly
accepted is cancelled immediately and reported, but prefer the trial environment and use `--production` deliberately.
`ERGANI_ENV` is ignored: only `--production` selects production.
:::

ERGANI's schema endpoint used by `schema:check` can disagree with the validator (it has omitted fields the validator
requires, and listed fields in the wrong order), so `submit:check` is the authoritative check.

Documents whose default factory output targets another variant of the same schema (weekly work time, leave) use the
factories listed in `bin/factory-overrides.php`.

