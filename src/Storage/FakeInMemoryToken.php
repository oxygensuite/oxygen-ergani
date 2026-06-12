<?php

namespace OxygenSuite\OxygenErgani\Storage;

use GuzzleHttp\Handler\MockHandler;
use OxygenSuite\OxygenErgani\Http\Auth\AuthenticationLogin;
use OxygenSuite\OxygenErgani\Http\Auth\AuthenticationRefresh;
use OxygenSuite\OxygenErgani\Responses\AuthenticationToken;

class FakeInMemoryToken extends InMemoryToken
{
    private bool $loginCalled = false;
    private bool $refreshCalled = false;

    private MockHandler $loginHandler;
    private MockHandler $refreshHandler;

    public function setLoginHandler(MockHandler $loginHandler): static
    {
        $this->loginHandler = $loginHandler;

        return $this;
    }

    public function setRefreshHandler(MockHandler $refreshHandler): static
    {
        $this->refreshHandler = $refreshHandler;

        return $this;
    }

    public function loginCalled(): bool
    {
        return $this->loginCalled;
    }

    public function refreshCalled(): bool
    {
        return $this->refreshCalled;
    }

    protected function login(): AuthenticationToken
    {
        $this->loginCalled = true;
        $login = new AuthenticationLogin();

        if (!empty($this->loginHandler)) {
            $login->getConfig()->setHandler($this->loginHandler);
        }

        return $login->handle($this->username, $this->password);
    }

    protected function refresh(): AuthenticationToken
    {
        $this->refreshCalled = true;
        $refresh = new AuthenticationRefresh();

        if (!empty($this->refreshHandler)) {
            $refresh->getConfig()->setHandler($this->refreshHandler);
        }

        return $refresh->handle($this->getAccessToken(), $this->getRefreshToken());
    }
}
