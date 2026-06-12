<?php

namespace OxygenSuite\OxygenErgani\Models\Concerns;

use OxygenSuite\OxygenErgani\Factories\Factory;

/**
 * Provides factory support for models.
 *
 * Models using this trait can create factory instances via the static factory() method.
 */
trait HasFactory
{
    /**
     * Get a new factory instance for the model.
     *
     * @param int $count Number of models to create
     *
     * @return Factory<static>
     */
    public static function factory(int $count = 1): Factory
    {
        return Factory::factoryForModel(static::class)->count($count);
    }
}
