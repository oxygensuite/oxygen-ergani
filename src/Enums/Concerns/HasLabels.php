<?php

namespace OxygenSuite\OxygenErgani\Enums\Concerns;

use OxygenSuite\OxygenErgani\Attributes\Label;
use ReflectionEnumUnitCase;

trait HasLabels
{
    /**
     * Get the English label for this enum case.
     */
    public function label(): string
    {
        $label = $this->getLabelAttribute();

        return $label !== null ? $label->english : $this->name;
    }

    /**
     * Get the Greek label for this enum case.
     */
    public function labelGreek(): string
    {
        $label = $this->getLabelAttribute();

        return $label !== null ? $label->greek : $this->name;
    }

    /**
     * Get all English labels as [value => label] array.
     *
     * For backed enums, keys are the backing values.
     * For pure enums, keys are the case names.
     *
     * @return array<int|string, string>
     */
    public static function labels(): array
    {
        return self::getLabelsForLocale('english');
    }

    /**
     * Get all Greek labels as [value => label] array.
     *
     * For backed enums, keys are the backing values.
     * For pure enums, keys are the case names.
     *
     * @return array<int|string, string>
     */
    public static function labelsGreek(): array
    {
        return self::getLabelsForLocale('greek');
    }

    /**
     * Get labels for a specific subset of cases as [value => label] array.
     *
     * Useful for creating filtered dropdowns from category helper methods.
     *
     * @param array<self> $cases Array of enum cases to get labels for
     * @param string $locale 'english' or 'greek'
     *
     * @return array<int|string, string>
     */
    public static function labelsFor(array $cases, string $locale = 'greek'): array
    {
        $labels = [];

        foreach ($cases as $case) {
            $reflection = new ReflectionEnumUnitCase(self::class, $case->name);
            $attributes = $reflection->getAttributes(Label::class);

            $key = $case->value;

            if (! empty($attributes)) {
                /** @var Label $label */
                $label = $attributes[0]->newInstance();
                $labels[$key] = $label->{$locale};
            } else {
                $labels[$key] = $case->name;
            }
        }

        return $labels;
    }

    /**
     * @return array<int|string, string>
     */
    private static function getLabelsForLocale(string $locale): array
    {
        $labels = [];

        foreach (self::cases() as $case) {
            $caseReflection = new ReflectionEnumUnitCase(self::class, $case->name);
            $attributes = $caseReflection->getAttributes(Label::class);

            $key = $case->value;

            if (! empty($attributes)) {
                /** @var Label $label */
                $label = $attributes[0]->newInstance();
                $labels[$key] = $label->{$locale};
            } else {
                $labels[$key] = $case->name;
            }
        }

        return $labels;
    }

    private function getLabelAttribute(): ?Label
    {
        $reflection = new ReflectionEnumUnitCase(self::class, $this->name);
        $attributes = $reflection->getAttributes(Label::class);

        if (! empty($attributes)) {
            /** @var Label */
            return $attributes[0]->newInstance();
        }

        return null;
    }
}
