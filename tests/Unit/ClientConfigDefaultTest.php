<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace Tests\Unit;

use OxygenSuite\OxygenErgani\Ergani;
use OxygenSuite\OxygenErgani\Http\Client;
use OxygenSuite\OxygenErgani\Http\ClientConfig;
use Tests\TestCase;

class ClientConfigDefaultTest extends TestCase
{
    protected function tearDown(): void
    {
        ClientConfig::resetDefault();
    }

    public function test_get_default_returns_fresh_instance_when_unset(): void
    {
        $config = ClientConfig::getDefault();

        $this->assertSame(10, $config->getTimeout());
    }

    public function test_get_default_returns_clone_of_configured_default(): void
    {
        ClientConfig::setDefault((new ClientConfig())->setTimeout(30));

        $config = ClientConfig::getDefault();

        $this->assertSame(30, $config->getTimeout());
        $this->assertNotSame($config, ClientConfig::getDefault());
    }

    public function test_mutating_returned_config_does_not_affect_default(): void
    {
        ClientConfig::setDefault((new ClientConfig())->setTimeout(30));

        ClientConfig::getDefault()->setBearerToken('leaked-token');

        $this->assertNull(ClientConfig::getDefault()->getBearerToken());
    }

    public function test_reset_default_restores_builtin_defaults(): void
    {
        ClientConfig::setDefault((new ClientConfig())->setTimeout(99));
        ClientConfig::resetDefault();

        $this->assertSame(10, ClientConfig::getDefault()->getTimeout());
    }

    public function test_client_falls_back_to_global_default(): void
    {
        ClientConfig::setDefault((new ClientConfig())->setTimeout(45));

        $client = new Client();

        $this->assertSame(45, $client->getConfig()->getTimeout());
    }

    public function test_explicit_config_wins_over_global_default(): void
    {
        ClientConfig::setDefault((new ClientConfig())->setTimeout(45));

        $client = new Client(config: (new ClientConfig())->setTimeout(7));

        $this->assertSame(7, $client->getConfig()->getTimeout());
    }

    public function test_ergani_service_calls_use_global_default_config(): void
    {
        ClientConfig::setDefault(
            (new ClientConfig())->setHandler($this->mockResponse(200, 'ex-base-01.json')),
        );

        $ergani = new Ergani('token');
        $info = $ergani->getEmployerInfo();

        $this->assertSame('12345', $info->id);
    }
}
