# Ergani API

## Introduction

A comprehensive package for seamlessly interacting with Greece’s Ergani system, enabling automated submissions for employee data such as check-ins, check-outs, and other employment-related information.

## Requirements

- PHP ^8.1
- Guzzle HTTP ^7.9
- Ergani credentials (`username` and `password`)

## Installation

To install the Ergani API package, run the following command in your terminal:

```bash
composer require oxygensuite/oxygen-ergani
```

## Usage

### Authentication and getting an Access token

All interactions with the Ergani API require a JSON Web Token (JWT) for authentication.
To get a JWT, start by Authenticating with your Ergani credentials. After authentication, you will receive
an object of type `\OxygenSuite\OxygenErgani\Responses\AuthenticationToken` with the following public properties:

- `accessToken`: You will use this token for later API requests.
- `accessTokenExpirationSeconds`: The duration in seconds for which the token is valid.
- `refreshToken`: A token used to refresh the access token when it expires.
- `accessTokenExpiresAt`: The timestamp when the access token will expire.
- `refreshTokenExpiresAt`: The timestamp when the refresh token will expire.

```php
use OxygenSuite\OxygenErgani\Http\Auth\AuthenticationLogin;

$auth = new AuthenticationLogin();
$response = $auth->handle('your-username', 'your-password');

// $response->accessToken;
// $response->accessTokenExpirationSeconds;
// $response->refreshToken;
// $response->refreshTokenExpiresAt;
// $response->accessTokenExpiresAt;
```

### Refreshing the Access Token

If your access token expires, you won't be able to make further requests until you refresh it.
It is recommended to refresh the access token before it expires to ensure uninterrupted access to the API.
This package provides a simple way to refresh your access token:

```php
use OxygenSuite\OxygenErgani\Http\Auth\AuthenticationRefresh;

$auth = new AuthenticationRefresh();
$response = $auth->handle('old-access-token', 'old-refresh-token');
```

After refresh, you will receive the same object and properties as in the authentication step.

### Logout
To log out and invalidate your access token, use the following functionality by providing both your access token and refresh token.

```php
use OxygenSuite\OxygenErgani\Http\Auth\AuthenticationLogout;

$auth = new AuthenticationLogout('access-token');
$response = $auth->handle('refresh-token');
```

### Using your Access Token and choosing the Environment

All API requests require an access token for authentication and an environment to specify the API endpoint on each call.
For example, to create a work card (checkin or checkout), you need to define your access token you received while authentication and
your preferred environment (either `Environment::TEST` or `Environment::PRODUCTION`).

```php
use OxygenSuite\OxygenErgani\Http\Documents\WorkCard;
use OxygenSuite\OxygenErgani\Enums\Environment;
use OxygenSuite\OxygenErgani\Models\WorkCard\Card;

$workCard = new WorkCard('your-access-token', Environment::TEST); // or 'Environment::PRODUCTION' for production environment

$card = new Card();
// Populate the card with necessary data

$workCard->handle($card);
```

### Token Management
Ergani requires you to manage your tokens effectively. This means you should:
- store your access token and refresh token,
- use the access token for API requests until it expires,
- refresh your access token before or after it expires,
- log out when needed to invalidate your tokens,
- and DO NOT authenticate (create a new JWT), every time you need to make a request!!!

If these seem overwhelming to you, don't worry! The package provides two different ways that do all of this for you:
- `FileToken`: An environment agnostic token manager that stores tokens in files and cycles them forever (recommended).
- `InMemoryToken`: A token manager that stores tokens in the memory and lasts until the script execution ends.

```php
// Using FileToken (recommended)

use OxygenSuite\OxygenErgani\Storage\FileToken;
use OxygenSuite\OxygenErgani\Storage\Token;
use OxygenSuite\OxygenErgani\Enums\Environment;

$username = 'your-username';
$password = 'your-password';
$env = Environment::TEST; // or Environment::PRODUCTION

Token::setCurrentTokenManager(new FileToken($username, $password), $env);
```

That's it! Now set the current token manager each time you boot your application, and you won't have to worry about managing tokens manually.
> If you are using Laravel, you can set the current token manager in a service provider or middleware to ensure it is set for every request.

Now you can use the Ergani API without specifying the access token and the environment on each call.
In the examples below, we assume the `FileToken` manager is set as the current token manager.

### Creating a Work Card

```php
use OxygenSuite\OxygenErgani\Http\Documents\WorkCard;
use OxygenSuite\OxygenErgani\Models\WorkCard\Card;

$workCard = new WorkCard(); // No need to pass access token and environment if you have set a token manager

$card = Card::make()
    ->setEmployerTin('999999999') // Company TIN
    ->setBranchCode(0) // Company Branch Code
    ->setComments('test-comments') // Comments are required but can be empty or null
    ->addDetails( // Add as many details as you need, each detail represents an employee's a check-in or check-out
        CardDetail::make()
            ->setTin('888888888') // Employee TIN
            ->setFirstName('John') // Employee First Name
            ->setLastName('Doe') // Employee Last Name
            ->setType(CardDetailType::CHECK_IN) // The type of the movement can be CHECK_IN or CHECK_OUT
            ->setReferenceDate(date('Y-m-d')) // The reference date of the movement, format: Y-m-d
            ->setDate(date('Y-m-d\TH:i:s.uP')) // The date and time of the movement, format: Y-m-d\TH:i:s.uP
            ->setReasonCode(null) // The reason code for the movement can be null if not applicable
    );

$response = $workCard->handle($card);

// The response array will contain as many work cards as you have added details to the card.
$response[0]->id; // The ID of the created work card
$response[0]->protocol; // The protocol number of the created work card (e.g. 'ΕΥΣ92')
$response[0]->submissionDate->format('d/m/Y H:i'); // The submission date of the work card (e.g. '04/05/2022 01:13')
```

### Canceling a Submitted Document

```php
use OxygenSuite\OxygenErgani\Http\Documents\CancelSubmittedDocument;

$cancel = new CancelSubmittedDocument();

$documentType = "the-document-type";
$protocol = "the-protocol-number"; // e.g. 'ΕΥΣ92'
$submissionDate = "20250604"; // format: yyyymmdd

$cancel->handle($documentType, $protocol, $submissionDate);
```

### Custom Token Manager
The package also allows you to create your own token manager by implementing the `OxygenSuite\OxygenErgani\Storage\Token` abstract class.
Here's an example of a custom token manager that stores and retrieves tokens from a database:

```php

namespace App\Services\Ergani\TokenManagers;

use DateTime;
use DateTimeImmutable;
use Error;
use OxygenSuite\OxygenErgani\Enums\Environment;
use OxygenSuite\OxygenErgani\Http\Client;
use OxygenSuite\OxygenErgani\Responses\AuthenticationToken;
use OxygenSuite\OxygenErgani\Storage\Token;

class DatabaseToken extends Token
{
    private ?AuthenticationToken $token = null;

    private function loadTokenFromDatabase(): void
    {
        // Implement your logic to retrieve the token from the database

        $username = $this->username;
        $password = $this->password;
        $environment = Client::getDefaultEnvironment()->name;

//      Use md5 of the username, password, and environment to create a unique string for the client
//        $dbToken = ""; // Replace with the actual database retrieval logic

        if (empty($dbToken)) {
            // There isn't a token stored in the database for the current user
            return;
        }

        $this->token = new AuthenticationToken();
        $this->token->accessToken = $dbToken->accessToken;
        $this->token->accessTokenExpiresAt = $dbToken->accessTokenExpiresAt;
        $this->token->refreshToken = $dbToken->refreshToken;
        $this->token->refreshTokenExpiresAt = $dbToken->refreshTokenExpiresAt;
    }

    public function getAccessToken(): ?string
    {
        if (empty($this->token)) {
            $this->loadTokenFromDatabase();
        }

        return $this->token?->accessToken;
    }

    public function getRefreshToken(): string
    {
        return $this->token?->refreshToken;
    }

    public function isAccessTokenExpired(): bool
    {
        return $this->token?->accessTokenExpiresAt < new DateTime();
    }

    public function isRefreshTokenExpired(): bool
    {
        return $this->token?->refreshTokenExpiresAt < new DateTime();
    }

    public function setAuthToken(AuthenticationToken $token): static
    {
        $this->token = $token;
        // Implement your logic to save the token to the database
        return $this;
    }

    public function failedAuthentication(): static
    {
        $this->token = null;
        // Implement your logic to handle failed authentication, such as logging or deleting the token from the database
        return $this;
    }

    public function authToken(string $token): ?AuthenticationToken
    {
        return $this->token;
    }
}
```

> [!CAUTION]
> Since usernames are not unique, if you are storing tokens for multiple users, you need to implement a way to differentiate between them.
> FileToken does this by storing tokens in files by using the md5 of the username, password, and environment for the filename.

## Contributing

Contributions are welcome! If you have suggestions for improvements or find bugs, please open an issue or submit a pull request.
- [PSR-12 Coding Standard](https://www.php-fig.org/psr/psr-12/) is followed for code style.
- [PHPUnit](https://phpunit.de/) is used for testing.

## License
This package is open-source software licensed under the [MIT License](https://opensource.org/license/mit/).

Copyright 2025 © [Oxygen Suite](https://github.com/oxygensuite).
