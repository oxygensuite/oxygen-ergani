<?php

namespace OxygenSuite\OxygenErgani\Factories;

use Faker\Generator;
use OxygenSuite\OxygenErgani\Models\Model;

/**
 * Base factory for creating model instances with fake data.
 *
 * The fake() method returns a Faker Generator with Greek-specific methods from GreekProvider.
 *
 * @template TModel of Model
 *
 * @mixin GreekProvider
 */
abstract class Factory
{
    /** @var (Generator&GreekProvider)|null */
    private static ?Generator $faker = null;

    /** @var class-string<TModel>|null */
    protected ?string $model = null;

    /** @var array<string, mixed> */
    protected array $state = [];

    /** @var array<int, string> */
    protected array $except = [];

    protected int $count = 1;

    /**
     * Create a new factory instance.
     *
     * @return static
     */
    public static function new(): static
    {
        return new static();
    }

    /**
     * Create factory for a given model class.
     *
     * Resolves factory location based on model namespace structure:
     * - Models\Hiring\NewDeclaration → Factories\Hiring\NewDeclarationFactory
     * - Models\Card → Factories\CardFactory
     *
     * @template T of Model
     *
     * @param class-string<T> $modelClass
     *
     * @return Factory<T>
     */
    public static function factoryForModel(string $modelClass): Factory
    {
        // Extract subfolder from model namespace (e.g., "Hiring" from "Models\Hiring\NewDeclaration")
        $modelsBase = 'OxygenSuite\\OxygenErgani\\Models\\';
        $relativePath = str_replace($modelsBase, '', $modelClass);
        $parts = explode('\\', $relativePath);
        $modelName = array_pop($parts);
        $subfolder = implode('\\', $parts);

        // Build factory class name with matching subfolder
        $factoryNamespace = __NAMESPACE__ . ($subfolder ? '\\' . $subfolder : '');
        /** @var class-string<Factory<T>> $factoryClass */
        $factoryClass = $factoryNamespace . '\\' . $modelName . 'Factory';

        /** @var Factory<T> $factory */
        $factory = new $factoryClass();
        $factory->model = $modelClass;

        return $factory;
    }

    /**
     * Set the number of models to create.
     *
     * @return $this
     */
    public function count(int $count): static
    {
        $this->count = $count;

        return $this;
    }

    /**
     * Create model instance(s) with the given attributes.
     *
     * @param array<string, mixed> $attributes
     *
     * @return TModel|array<int, TModel>
     */
    public function make(array $attributes = []): Model|array
    {
        $instances = [];
        $modelClass = $this->model ?? $this->resolveModelClass();

        for ($i = 0; $i < $this->count; $i++) {
            $merged = array_merge($this->definition(), $this->state, $attributes);

            if (! empty($this->except)) {
                $merged = array_diff_key($merged, array_flip($this->except));
            }

            /** @var TModel $instance */
            $instance = new $modelClass();

            foreach ($merged as $key => $value) {
                // Resolve nested factories
                if ($value instanceof self) {
                    $value = $value->make();
                }

                // Resolve callable values (lazy evaluation)
                if (is_callable($value) && ! is_string($value)) {
                    $value = $value();
                }

                $instance->set($key, $value);
            }

            $instances[] = $instance;
        }

        return $this->count === 1 ? $instances[0] : $instances;
    }

    /**
     * Exclude specific fields from the generated data.
     *
     * @param array<int, string> $fields
     *
     * @return $this
     */
    public function except(array $fields): static
    {
        $this->except = array_merge($this->except, $fields);

        return $this;
    }

    /**
     * Override specific attributes for the generated model.
     *
     * @param array<string, mixed> $attributes
     *
     * @return $this
     */
    public function state(array $attributes): static
    {
        $this->state = array_merge($this->state, $attributes);

        return $this;
    }

    /**
     * Get the Faker generator instance with Greek-specific methods.
     *
     * @phpstan-return Generator
     */
    public static function fake(): Generator
    {
        if (self::$faker === null) {
            self::$faker = \Faker\Factory::create('el_GR');
            self::$faker->addProvider(new GreekProvider(self::$faker));
        }

        return self::$faker;
    }

    /**
     * Reset the Faker instance (useful for testing).
     */
    public static function resetFaker(): void
    {
        self::$faker = null;
    }

    /**
     * Resolve the model class from the factory class name.
     *
     * Uses factory namespace structure to determine model location:
     * - Factories\Hiring\NewDeclarationFactory → Models\Hiring\NewDeclaration
     * - Factories\CardFactory → Models\Card
     *
     * @return class-string<TModel>
     */
    protected function resolveModelClass(): string
    {
        $factoryClass = static::class;

        // Extract subfolder from factory namespace
        $factoriesBase = 'OxygenSuite\\OxygenErgani\\Factories\\';
        $relativePath = str_replace($factoriesBase, '', $factoryClass);
        $parts = explode('\\', $relativePath);
        $factoryName = array_pop($parts);
        $subfolder = implode('\\', $parts);

        // Remove "Factory" suffix to get model name
        $modelName = str_replace('Factory', '', $factoryName);

        // Build model class name with matching subfolder
        $modelsNamespace = 'OxygenSuite\\OxygenErgani\\Models\\' . ($subfolder ? $subfolder . '\\' : '');

        /** @var class-string<TModel> $modelClass */
        $modelClass = $modelsNamespace . $modelName;

        if (class_exists($modelClass)) {
            return $modelClass;
        }

        throw new \RuntimeException("Could not resolve model class for factory: {$factoryClass}");
    }

    /**
     * Define the default attribute values for the model.
     *
     * @return array<string, mixed>
     */
    abstract public function definition(): array;
}
