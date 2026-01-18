<?php

namespace OxygenSuite\OxygenErgani;

use OxygenSuite\OxygenErgani\Enums\Environment;
use OxygenSuite\OxygenErgani\Enums\UserType;
use OxygenSuite\OxygenErgani\Exceptions\ErganiException;
use OxygenSuite\OxygenErgani\Http\Auth\AuthenticationLogin;
use OxygenSuite\OxygenErgani\Http\ClientConfig;
use OxygenSuite\OxygenErgani\Http\Documents\WorkCard\WorkCard;
use OxygenSuite\OxygenErgani\Http\Services\ParameterLookup;
use OxygenSuite\OxygenErgani\Http\Services\ServicesList;
use OxygenSuite\OxygenErgani\Models\WorkCard\Card;
use OxygenSuite\OxygenErgani\Responses\AuthenticationToken;
use OxygenSuite\OxygenErgani\Responses\ParameterCollection;
use OxygenSuite\OxygenErgani\Responses\WorkCardResponse;

class Ergani
{
    private ?string $accessToken;
    private Environment $environment;
    private ClientConfig $config;

    public function __construct(?string $accessToken = null, ?Environment $environment = Environment::TEST, ?ClientConfig $config = null)
    {
        $this->accessToken = $accessToken;
        $this->environment = $environment;
        $this->config = $config ?? new ClientConfig();
    }

    /**
     * @throws ErganiException
     */
    public function authenticate(string $username, string $password, UserType $userType = UserType::EXTERNAL): AuthenticationToken
    {
        $auth = new AuthenticationLogin($this->environment, $this->config);

        return $auth->handle($username, $password, $userType);
    }

    /**
     * @return array<string, mixed>
     * @throws ErganiException
     */
    public function getServices(): array
    {
        $services = new ServicesList($this->accessToken, $this->environment, $this->config);

        return $services->handle();
    }

    /**
     * @return array<string, mixed>
     * @throws ErganiException
     */
    public function workCardSchema(): array
    {
        $workCard = new WorkCard($this->accessToken, $this->environment, $this->config);

        return $workCard->schema();
    }

    /**
     * @param Card|array<int, Card> $cards
     *
     * @return array<int, WorkCardResponse>
     * @throws ErganiException
     */
    public function sendWorkCards(Card|array $cards): array
    {
        $workCard = new WorkCard($this->accessToken, $this->environment, $this->config);

        return $workCard->handle($cards);
    }

    /**
     * Look up parameter values by type.
     *
     * @param string $parameter The parameter type (use ParameterLookup constants)
     *
     * @throws ErganiException
     *
     * @see ParameterLookup::WORK_TIME_TYPE
     * @see ParameterLookup::NATIONALITY
     * @see ParameterLookup::BANK
     */
    public function getParameters(string $parameter): ParameterCollection
    {
        $lookup = new ParameterLookup($this->accessToken, $this->environment, $this->config);

        return $lookup->handle($parameter);
    }
}
