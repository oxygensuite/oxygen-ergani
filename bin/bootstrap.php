<?php

require __DIR__ . '/../vendor/autoload.php';

use OxygenSuite\OxygenErgani\Enums\Environment;
use OxygenSuite\OxygenErgani\Storage\FileToken;
use OxygenSuite\OxygenErgani\Storage\Token;

/**
 * Load environment variables from .env file if it exists.
 *
 * @return array<string, string>
 */
function loadEnvFile(string $path): array
{
    $env = [];

    if (!file_exists($path)) {
        return $env;
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($lines as $line) {
        if (str_starts_with(trim($line), '#')) {
            continue;
        }

        if (str_contains($line, '=')) {
            [$key, $value] = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);

            if ((str_starts_with($value, '"') && str_ends_with($value, '"'))
                || (str_starts_with($value, "'") && str_ends_with($value, "'"))) {
                $value = substr($value, 1, -1);
            }

            $env[$key] = $value;
        }
    }

    return $env;
}

/**
 * Get config value from .env array or environment variable.
 *
 * @param array<string, string> $envFile
 */
function getConfig(array $envFile, string $key, ?string $default = null): ?string
{
    return $envFile[$key] ?? getenv($key) ?: $default;
}

/**
 * Set up ERGANI authentication from environment configuration.
 *
 * @param array<string, string> $envFile
 */
function setupAuth(array $envFile): void
{
    $username = getConfig($envFile, 'ERGANI_USERNAME');
    $password = getConfig($envFile, 'ERGANI_PASSWORD');
    $envName = getConfig($envFile, 'ERGANI_ENV', 'test');

    if (!$username || !$password) {
        echo "\033[31mError: ERGANI_USERNAME and ERGANI_PASSWORD are required.\033[0m\n\n";
        echo "Option 1: Create a .env file in the project root:\n";
        echo "  ERGANI_USERNAME=your_username\n";
        echo "  ERGANI_PASSWORD=your_password\n";
        echo "  ERGANI_ENV=test\n\n";
        echo "Option 2: Set environment variables:\n";
        echo "  export ERGANI_USERNAME=your_username\n";
        echo "  export ERGANI_PASSWORD=your_password\n";
        echo "  export ERGANI_ENV=test\n";
        exit(1);
    }

    $env = strtolower($envName) === 'production' ? Environment::PRODUCTION : Environment::TEST;
    Token::setCurrentTokenManager(new FileToken($username, $password), $env);

    echo "Using environment: \033[1m{$env->name}\033[0m\n";
}
