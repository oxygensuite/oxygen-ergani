---
layout: home

hero:
  name: Oxygen Ergani
  text: PHP SDK for Greece's ERGANI System
  tagline: A comprehensive package for seamlessly interacting with Greece's labor ministry API
  actions:
    - theme: brand
      text: Get Started
      link: /guide/getting-started
    - theme: alt
      text: View on GitHub
      link: https://github.com/oxygensuite/oxygen-ergani

features:
  - icon:
      src: /icons/clock.svg
      alt: Work Cards
    title: Work Cards
    details: Submit employee check-ins and check-outs with full protocol tracking and response handling.

  - icon:
      src: /icons/calendar.svg
      alt: Work Time
    title: Work Time Declarations
    details: Manage daily and weekly work time schedules, overtime, and leave declarations.

  - icon:
      src: /icons/user-plus.svg
      alt: Hiring
    title: Employee Hiring (E3)
    details: Handle new hires, transfers, and lending arrangements with complete declaration support.

  - icon:
      src: /icons/user-minus.svg
      alt: Termination
    title: Terminations & Dismissals
    details: Process voluntary resignations, retirements, dismissals, and fixed-term contract endings.

  - icon:
      src: /icons/shield.svg
      alt: Token Management
    title: Automatic Token Management
    details: Built-in token managers handle authentication, refresh cycles, and token persistence automatically.

  - icon:
      src: /icons/code.svg
      alt: Type Safety
    title: Modern PHP 8.2+
    details: Fluent interfaces, enums, typed properties, and comprehensive IDE autocompletion support.
---

## Quick Example

```php
use OxygenSuite\OxygenErgani\Ergani;
use OxygenSuite\OxygenErgani\Storage\FileToken;
use OxygenSuite\OxygenErgani\Storage\Token;
use OxygenSuite\OxygenErgani\Enums\Environment;
use OxygenSuite\OxygenErgani\Models\WorkCard\Card;
use OxygenSuite\OxygenErgani\Models\WorkCard\CardDetail;
use OxygenSuite\OxygenErgani\Enums\CardDetailType;

// Set up token management (once per application boot)
Token::setCurrentTokenManager(
    new FileToken('username', 'password'),
    Environment::TEST
);

// Create a work card
$card = Card::make()
    ->setEmployerTin('999999999')
    ->setBranchCode(0)
    ->addDetails(
        CardDetail::make()
            ->setTin('888888888')
            ->setFirstName('John')
            ->setLastName('Doe')
            ->setType(CardDetailType::CHECK_IN)
            ->setReferenceDate(date('Y-m-d'))
            ->setDate(date('Y-m-d\TH:i:s.uP'))
    );

// Submit it through the Ergani facade
$ergani = new Ergani();
$responses = $ergani->sendWorkCards($card);

echo $responses[0]->protocol; // e.g., 'ΕΥΣ92'
```

## Requirements

- PHP ^8.2
- Guzzle HTTP ^7.9
- ERGANI credentials (username and password)

## License

Open-source software licensed under the [MIT License](https://opensource.org/license/mit/).
