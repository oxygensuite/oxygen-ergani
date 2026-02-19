# CLI Tools

The package includes two CLI tools for verifying that local code stays in sync with the live ERGANI API. Both tools require ERGANI credentials.

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

Documents that share the same XSD (e.g., the 8 WTO work time documents) are grouped together, and their API fields are unioned for a single comparison per XSD.

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

For multi-document groups (e.g., WTO with 8 documents sharing one XSD):

```
WTO (xsd/WTO_v2.xsd)
  Documents: DailyWorkTime, DailyWorkTimeA, DailyWorkTimeD, WorkTimeLeave, ...
---------------------
✓ Fields match: 15 (union of 8 API schemas)
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

- `composer schema:check WTO` — checks the entire WTO group (all 8 documents)
- `composer schema:check DailyWorkTime` — resolves to the WTO group and checks all 8
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
