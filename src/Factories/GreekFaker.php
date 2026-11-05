<?php

namespace OxygenSuite\OxygenErgani\Factories;

/**
 * Static-analysis type for the Faker generator returned by {@see Factory::fake()}.
 *
 * The generator is a plain `Faker\Generator` with {@see GreekProvider} registered, so the
 * provider's methods (`afm()`, `amka()`, `greekDate()`, ...) are callable on it through Faker's
 * `__call()`. This marker interface exposes those methods to PHPStan and IDEs via `@mixin`;
 * nothing implements it at runtime.
 *
 * @mixin GreekProvider
 */
interface GreekFaker {}
