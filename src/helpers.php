<?php

use Faker\Generator;
use OxygenSuite\OxygenErgani\Factories\Factory;
use OxygenSuite\OxygenErgani\Factories\GreekProvider;

if (! function_exists('fake')) {
    /**
     * Get the Faker generator instance with Greek-specific methods.
     *
     * @return Generator
     *
     * @mixin GreekProvider
     */
    function fake(): Generator
    {
        return Factory::fake();
    }
}
