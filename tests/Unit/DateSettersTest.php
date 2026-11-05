<?php

namespace Tests\Unit;

use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use OxygenSuite\OxygenErgani\Models\Model;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ReflectionClass;
use ReflectionMethod;
use ReflectionNamedType;
use ReflectionParameter;
use ReflectionUnionType;

/**
 * Every model date input accepts any DateTimeInterface (DateTime, DateTimeImmutable, Carbon, ...),
 * so values such as SubmissionResponse::$submissionDate (a DateTimeImmutable) can be passed straight in.
 */
class DateSettersTest extends TestCase
{
    /** @var array<string, array{0: class-string<Model>}>|null */
    private static ?array $models = null;

    /**
     * Every concrete model class under src/Models.
     *
     * @return array<string, array{0: class-string<Model>}>
     */
    public static function models(): array
    {
        if (self::$models !== null) {
            return self::$models;
        }

        $src = dirname(__DIR__, 2) . '/src/Models';
        $cases = [];

        foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($src)) as $file) {
            if ($file->getExtension() !== 'php') {
                continue;
            }

            $relative = substr($file->getPathname(), strlen($src) + 1, -4);
            $class = 'OxygenSuite\\OxygenErgani\\Models\\' . str_replace(['/', '\\'], '\\', $relative);

            if (class_exists($class) && ! (new ReflectionClass($class))->isAbstract() && is_subclass_of($class, Model::class)) {
                $cases[$relative] = [$class];
            }
        }

        ksort($cases);

        return self::$models = $cases;
    }

    /**
     * No parameter may be typed as the concrete DateTime (rejects DateTimeImmutable), or as a class that
     * does not exist (e.g. `DateTimeInterface` used without its `use` import, resolving to a namespaced name).
     *
     * @param class-string<Model> $class
     */
    #[DataProvider('models')]
    public function test_no_parameter_is_limited_to_mutable_datetime(string $class): void
    {
        $offenders = [];
        foreach ((new ReflectionClass($class))->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
            foreach ($method->getParameters() as $parameter) {
                foreach ($this->typeNames($parameter, classesOnly: true) as $type) {
                    if ($type === DateTime::class || ! (class_exists($type) || interface_exists($type) || enum_exists($type))) {
                        $offenders[] = "{$method->getName()}({$type} \${$parameter->getName()})";
                    }
                }
            }
        }

        $this->assertSame([], $offenders, "{$class} has DateTime-only or unresolvable parameter types; use DateTimeInterface");
    }

    /**
     * Each date setter stores the same value for a DateTimeImmutable as for the equivalent DateTime.
     *
     * @param class-string<Model> $class
     */
    #[DataProvider('models')]
    public function test_date_setters_accept_immutable_dates(string $class): void
    {
        $setters = array_filter(
            (new ReflectionClass($class))->getMethods(ReflectionMethod::IS_PUBLIC),
            fn(ReflectionMethod $method) => str_starts_with($method->getName(), 'set')
                && $method->getNumberOfParameters() > 0
                && array_filter(
                    $method->getParameters(),
                    fn(ReflectionParameter $p) => ! in_array(DateTimeInterface::class, $this->typeNames($p), true),
                ) === [],
        );

        if ($setters === []) {
            $this->expectNotToPerformAssertions();

            return;
        }

        foreach ($setters as $setter) {
            $args = fn(string $type) => array_fill(0, $setter->getNumberOfParameters(), new $type('2026-11-05 09:30:15'));

            $mutable = new $class();
            $setter->invokeArgs($mutable, $args(DateTime::class));
            $immutable = new $class();
            $setter->invokeArgs($immutable, $args(DateTimeImmutable::class));

            $this->assertNotSame([], $mutable->toArray(), "{$class}::{$setter->getName()}() stored nothing");
            $this->assertSame($mutable->toArray(), $immutable->toArray(), "{$class}::{$setter->getName()}()");
        }
    }

    /**
     * @param class-string<Model> $class
     */
    #[DataProvider('models')]
    public function test_related_date_is_stored_in_greek_format(string $class): void
    {
        if (! method_exists($class, 'setRelatedDate')) {
            $this->expectNotToPerformAssertions();

            return;
        }

        foreach ([new DateTimeImmutable('2026-11-05 12:00'), new DateTime('2026-11-05'), '05/11/2026'] as $date) {
            $model = new $class();
            $model->setRelatedDate($date);

            $this->assertContains('05/11/2026', $model->toArray());
        }
    }

    public function test_models_are_discovered(): void
    {
        $this->assertGreaterThanOrEqual(50, count(self::models()));
    }

    /**
     * @return array<int, string>
     */
    private function typeNames(ReflectionParameter $parameter, bool $classesOnly = false): array
    {
        $type = $parameter->getType();
        $types = $type instanceof ReflectionUnionType ? $type->getTypes() : [$type];

        $named = array_filter(
            $types,
            fn($t) => $t instanceof ReflectionNamedType && (! $classesOnly || ! $t->isBuiltin()),
        );

        return array_values(array_map(
            fn(ReflectionNamedType $t) => $t->getName() === 'self' || $t->getName() === 'static'
                ? $parameter->getDeclaringClass()?->getName() ?? ''
                : $t->getName(),
            $named,
        ));
    }
}
