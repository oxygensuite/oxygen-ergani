<?php

namespace Tests\Enums;

use OxygenSuite\OxygenErgani\Attributes\Label;
use OxygenSuite\OxygenErgani\Enums\EmploymentStatus;
use OxygenSuite\OxygenErgani\Enums\MaritalStatus;
use OxygenSuite\OxygenErgani\Enums\ResponsiblePosition;
use OxygenSuite\OxygenErgani\Enums\Sex;
use OxygenSuite\OxygenErgani\Enums\SpecialCase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use ReflectionEnumUnitCase;

class HasLabelsTest extends TestCase
{
    public function test_label_returns_english_description(): void
    {
        $this->assertSame('Male', Sex::MALE->label());
        $this->assertSame('Female', Sex::FEMALE->label());
        $this->assertSame('Full-time', EmploymentStatus::FULL->label());
        $this->assertSame('Part-time', EmploymentStatus::PARTIAL->label());
    }

    public function test_label_greek_returns_greek_description(): void
    {
        $this->assertSame('Άνδρας', Sex::MALE->labelGreek());
        $this->assertSame('Γυναίκα', Sex::FEMALE->labelGreek());
        $this->assertSame('Πλήρης απασχόληση', EmploymentStatus::FULL->labelGreek());
        $this->assertSame('Μερική απασχόληση', EmploymentStatus::PARTIAL->labelGreek());
    }

    public function test_labels_returns_all_english_labels(): void
    {
        $labels = Sex::labels();

        $this->assertCount(2, $labels);
        $this->assertSame('Male', $labels[0]);
        $this->assertSame('Female', $labels[1]);
    }

    public function test_labels_greek_returns_all_greek_labels(): void
    {
        $labels = Sex::labelsGreek();

        $this->assertCount(2, $labels);
        $this->assertSame('Άνδρας', $labels[0]);
        $this->assertSame('Γυναίκα', $labels[1]);
    }

    public function test_labels_with_multiple_cases(): void
    {
        $labels = MaritalStatus::labels();

        $this->assertCount(4, $labels);
        $this->assertSame('Single', $labels[0]);
        $this->assertSame('Married', $labels[1]);
        $this->assertSame('Divorced', $labels[2]);
        $this->assertSame('Widowed', $labels[3]);
    }

    public function test_labels_with_string_backed_enum(): void
    {
        $labels = SpecialCase::labels();

        $this->assertCount(3, $labels);
        $this->assertSame('Not applicable', $labels['']);
        $this->assertSame('Private law - Narrow public sector', $labels['2']);
        $this->assertSame('Private law - Broader public sector', $labels['3']);
    }

    public function test_labels_greek_with_string_backed_enum(): void
    {
        $labels = ResponsiblePosition::labelsGreek();

        $this->assertCount(5, $labels);
        $this->assertSame('Δεν εφαρμόζεται', $labels['']);
        $this->assertSame('Όχι', $labels['1']);
        $this->assertSame('Θέση με διευθυντικό δικαίωμα', $labels['2']);
    }

    /**
     * Enums excluded from label testing (pure enums without HasLabels trait).
     *
     * @var array<int, string>
     */
    private const EXCLUDED_ENUMS = [
        'Environment',
    ];

    /**
     * @return array<string, array{class-string}>
     */
    public static function enumClassProvider(): array
    {
        $enumsPath = dirname(__DIR__, 2) . '/src/Enums/*.php';
        $enumClasses = [];

        foreach (glob($enumsPath) as $file) {
            $className = basename($file, '.php');

            if (in_array($className, self::EXCLUDED_ENUMS, true)) {
                continue;
            }

            $fullClassName = "OxygenSuite\\OxygenErgani\\Enums\\{$className}";

            if (enum_exists($fullClassName)) {
                $enumClasses[$className] = [$fullClassName];
            }
        }

        return $enumClasses;
    }

    /**
     * @param class-string<\BackedEnum> $enumClass
     */
    #[DataProvider('enumClassProvider')]
    public function test_all_enum_cases_have_english_and_greek_labels(string $enumClass): void
    {
        foreach ($enumClass::cases() as $case) {
            $reflection = new ReflectionEnumUnitCase($enumClass, $case->name);
            $attributes = $reflection->getAttributes(Label::class);

            $this->assertNotEmpty(
                $attributes,
                sprintf('Enum case %s::%s is missing a Label attribute', $enumClass, $case->name),
            );

            $label = $attributes[0]->newInstance();

            $this->assertNotEmpty(
                $label->english,
                sprintf('Enum case %s::%s has an empty English label', $enumClass, $case->name),
            );

            $this->assertNotEmpty(
                $label->greek,
                sprintf('Enum case %s::%s has an empty Greek label', $enumClass, $case->name),
            );
        }
    }
}
