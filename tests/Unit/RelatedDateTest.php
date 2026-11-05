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

/**
 * Every model's setRelatedDate() must accept any DateTimeInterface, so the DateTimeImmutable in
 * SubmissionResponse::$submissionDate can be passed straight to a correction / follow-up declaration.
 */
class RelatedDateTest extends TestCase
{
    /** @var array<string, array{0: class-string<Model>}>|null */
    private static ?array $models = null;

    /**
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

            if (! class_exists($class)) {
                continue;
            }

            $reflection = new ReflectionClass($class);
            if (! $reflection->isAbstract() && $reflection->isSubclassOf(Model::class) && $reflection->hasMethod('setRelatedDate')) {
                $cases[$reflection->getShortName() . ' (' . $relative . ')'] = [$class];
            }
        }

        return self::$models = $cases;
    }

    public function test_models_are_discovered(): void
    {
        $this->assertGreaterThanOrEqual(15, count(self::models()));
    }

    /**
     * @param class-string<Model> $class
     */
    #[DataProvider('models')]
    public function test_set_related_date_accepts_any_datetime(string $class): void
    {
        foreach ([new DateTimeImmutable('2026-11-05 12:00'), new DateTime('2026-11-05'), '05/11/2026'] as $date) {
            /** @var Model $model */
            $model = new $class();
            $model->setRelatedDate($date);

            $this->assertContains('05/11/2026', $model->toArray(), sprintf(
                '%s::setRelatedDate(%s) did not store 05/11/2026',
                $class,
                $date instanceof DateTimeInterface ? $date::class : 'string',
            ));
        }
    }
}
