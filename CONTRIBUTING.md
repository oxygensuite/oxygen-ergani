# Contributing to Oxygen Ergani

Thank you for your interest in contributing to Oxygen Ergani! This document provides guidelines and instructions for contributing.

## Code of Conduct

Please be respectful and constructive in all interactions. We welcome contributors of all experience levels.

## How to Contribute

### Reporting Bugs

Before submitting a bug report:

1. Check existing [GitHub Issues](https://github.com/oxygensuite/oxygen-ergani/issues) to avoid duplicates
2. Use the latest version to confirm the bug still exists

When submitting a bug report, include:

- PHP version and environment details
- Steps to reproduce the issue
- Expected vs actual behavior
- Relevant code snippets or error messages

### Suggesting Features

Feature suggestions are welcome! Please:

1. Check existing issues for similar suggestions
2. Describe the use case and why the feature would be valuable
3. Provide examples of how the feature would work

### Submitting Pull Requests

1. Fork the repository
2. Create a feature branch from `master`
3. Make your changes
4. Ensure all tests pass
5. Submit a pull request

## Development Setup

### Requirements

- PHP 8.2+
- Composer

### Installation

```bash
git clone https://github.com/oxygensuite/oxygen-ergani.git
cd oxygen-ergani
composer install
```

### Running Tests

```bash
# Run all tests
composer test

# Run a specific test file
./vendor/bin/phpunit tests/Documents/WorkCardTest.php

# Run a specific test method
./vendor/bin/phpunit --filter testMethodName
```

### Static Analysis

We use PHPStan at level 7:

```bash
composer analyse
```

New code should not introduce PHPStan errors. If you encounter issues with existing code, they may be in the baseline file (`phpstan-baseline.neon`).

### Code Style

We follow the [PER Coding Style](https://www.php-fig.org/per/coding-style/). Use Laravel Pint to format your code:

```bash
# Check only changed files
composer lint -- --dirty

# Fix all files
composer lint

# Check specific files
./vendor/bin/pint src/Http/Documents/YourFile.php
```

### Mutation Testing

We use Infection for mutation testing to ensure test quality:

```bash
composer infect
```

### Run All Checks

Before submitting a PR, run all checks:

```bash
composer check
```

This runs linting, static analysis, and tests.

## Coding Guidelines

### General Principles

- **Simplicity for consumers**: The API should be intuitive and easy to use
- **Fluent interfaces**: Setters should return `$this` for method chaining
- **Type safety**: Use PHP 8.2+ features (enums, typed properties, union types)
- **No over-engineering**: Only implement what's needed

### PHPDoc Annotations

For PHPStan compliance, specify array types:

```php
// Good
/** @var array<string, mixed> */
protected array $attributes = [];

/** @param array<int, Card> $cards */
public function setCards(array $cards): static

// Bad - PHPStan will complain
protected array $attributes = [];
```

For fluent methods, use native `static` return type:

```php
// Good
public function setName(string $name): static
{
    $this->name = $name;
    return $this;
}
```

### Enums

When creating enums, use the `HasLabels` trait with bilingual labels:

```php
use OxygenSuite\OxygenErgani\Attributes\Label;
use OxygenSuite\OxygenErgani\Enums\Concerns\HasLabels;

enum MyEnum: string
{
    use HasLabels;

    #[Label('English Label', 'Ελληνική Ετικέτα')]
    case VALUE = '1';
}
```

### Models

Models should:

- Extend the base `Model` class or use `HasAttributes` trait
- Define `$expectedOrder` for API field ordering
- Define `$casts` for type conversions (especially `greek_float`)
- Provide fluent setters and typed getters

```php
class MyDeclaration extends Model
{
    protected array $expectedOrder = ['f_field1', 'f_field2'];

    protected array $casts = [
        'f_amount' => 'greek_float',      // 2 decimals
        'f_hours' => 'greek_float:1',     // 1 decimal
    ];

    public function setAmount(float $amount): static
    {
        return $this->set('f_amount', $amount);
    }

    public function getAmount(): ?float
    {
        return $this->greekFloat('f_amount');
    }
}
```

### Tests

- Place tests in `tests/` mirroring the `src/` structure
- Use Guzzle's MockHandler for API responses
- Store mock response files in `tests/responses/`
- Use factories for generating test data

```php
use Tests\TestCase;

class MyDocumentTest extends TestCase
{
    public function test_it_submits_successfully(): void
    {
        $this->mockResponse('my-response.json');

        $model = MyModel::factory()->make();
        $response = (new MyDocument())->handle($model);

        $this->assertNotNull($response->id);
    }
}
```

### Documentation

When adding new features, update the documentation:

- **New enums**: Update `docs/api/enums.md`
- **New models**: Update relevant guide pages (e.g., `docs/guide/work-time.md`)
- **New documents**: Add guide page and update `docs/.vitepress/config.mts`
- **New services**: Update `docs/guide/services.md`
- **New exceptions**: Update `docs/api/exceptions.md`

## Commit Messages

We follow [Conventional Commits](https://www.conventionalcommits.org/):

```
feat: add new E8 document type
fix: handle null response in WorkCard
docs: update hiring guide with new examples
refactor: extract shared traits from declarations
test: add coverage for edge cases
chore: update dependencies
```

For breaking changes, add `!` after the type:

```
refactor!: rename WTO models to WorkTime
```

## Pull Request Process

1. **Branch naming**: Use descriptive names like `feat/e8-document` or `fix/workcard-null-response`

2. **PR description**: Include:
   - What changes were made
   - Why the changes were needed
   - How to test the changes

3. **Checks must pass**: All CI checks (tests, PHPStan, Pint) must pass

4. **Review**: A maintainer will review your PR and may request changes

5. **Merge**: Once approved, a maintainer will merge your PR

## Questions?

If you have questions about contributing, feel free to:

- Open a [GitHub Discussion](https://github.com/oxygensuite/oxygen-ergani/discussions)
- Ask in an issue

Thank you for contributing!
