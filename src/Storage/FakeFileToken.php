<?php

namespace OxygenSuite\OxygenErgani\Storage;

use GuzzleHttp\Handler\MockHandler;
use OxygenSuite\OxygenErgani\Http\Auth\AuthenticationLogin;
use OxygenSuite\OxygenErgani\Http\Auth\AuthenticationRefresh;
use OxygenSuite\OxygenErgani\Responses\AuthenticationToken;

class FakeFileToken extends FileToken
{
    private bool $loginCalled = false;
    private bool $refreshCalled = false;

    private MockHandler $loginHandler;
    private MockHandler $refreshHandler;

    /**
     * @param string $username
     * @param string $password
     * @param array{cache_dir?: string} $options
     */
    public function __construct(string $username, string $password, array $options = [])
    {
        parent::__construct($username, $password, $options);
    }

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
