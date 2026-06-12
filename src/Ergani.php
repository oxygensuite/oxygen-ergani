<?php

namespace OxygenSuite\OxygenErgani;

use OxygenSuite\OxygenErgani\Enums\Environment;
use OxygenSuite\OxygenErgani\Enums\UserType;
use OxygenSuite\OxygenErgani\Ergani\Concerns\ManagesDocuments;
use OxygenSuite\OxygenErgani\Ergani\Concerns\SendsConstructionDocuments;
use OxygenSuite\OxygenErgani\Ergani\Concerns\SendsDismissalDocuments;
use OxygenSuite\OxygenErgani\Ergani\Concerns\SendsHiringDocuments;
use OxygenSuite\OxygenErgani\Ergani\Concerns\SendsInternshipDocuments;
use OxygenSuite\OxygenErgani\Ergani\Concerns\SendsModificationDocuments;
use OxygenSuite\OxygenErgani\Ergani\Concerns\SendsOvertimeDocuments;
use OxygenSuite\OxygenErgani\Ergani\Concerns\SendsPreAnnouncementDocuments;
use OxygenSuite\OxygenErgani\Ergani\Concerns\SendsSixthDayDocuments;
use OxygenSuite\OxygenErgani\Ergani\Concerns\SendsTerminationDocuments;
use OxygenSuite\OxygenErgani\Ergani\Concerns\SendsWorkTimeDocuments;
use OxygenSuite\OxygenErgani\Exceptions\ErganiException;
use OxygenSuite\OxygenErgani\Http\Auth\AuthenticationLogin;
use OxygenSuite\OxygenErgani\Http\Auth\AuthenticationLogout;
use OxygenSuite\OxygenErgani\Http\ClientConfig;
use OxygenSuite\OxygenErgani\Http\Documents\WorkCard\WorkCard;
use OxygenSuite\OxygenErgani\Http\Services\AcceptanceStatus;
use OxygenSuite\OxygenErgani\Http\Services\BranchInfo;
use OxygenSuite\OxygenErgani\Http\Services\EmployerInfo;
use OxygenSuite\OxygenErgani\Http\Services\MonthlyStatus;
use OxygenSuite\OxygenErgani\Http\Services\ParameterLookup;
use OxygenSuite\OxygenErgani\Http\Services\ServicesList;
use OxygenSuite\OxygenErgani\Http\Services\WorkforceStatus;
use OxygenSuite\OxygenErgani\Models\WorkCard\Card;
use OxygenSuite\OxygenErgani\Responses\AcceptanceStatusResponse;
use OxygenSuite\OxygenErgani\Responses\AuthenticationToken;
use OxygenSuite\OxygenErgani\Responses\BranchCollection;
use OxygenSuite\OxygenErgani\Responses\EmployeeStatusResponse;
use OxygenSuite\OxygenErgani\Responses\EmployerResponse;
use OxygenSuite\OxygenErgani\Responses\ParameterCollection;
use OxygenSuite\OxygenErgani\Responses\WorkCardResponse;
use OxygenSuite\OxygenErgani\Responses\WorkforceStatusResponse;
use OxygenSuite\OxygenErgani\Storage\Token;
use Psr\SimpleCache\CacheInterface;
use Psr\SimpleCache\InvalidArgumentException;
use ReflectionClass;

class Ergani
{
    use ManagesDocuments;
    use SendsConstructionDocuments;
    use SendsDismissalDocuments;
    use SendsHiringDocuments;
    use SendsInternshipDocuments;
    use SendsModificationDocuments;
    use SendsOvertimeDocuments;
    use SendsPreAnnouncementDocuments;
    use SendsSixthDayDocuments;
    use SendsTerminationDocuments;
    use SendsWorkTimeDocuments;

    private const DEFAULT_CACHE_TTL = 2_592_000;

    private ?string $accessToken;
    private Environment $environment;
    private ClientConfig $config;
    private ?CacheInterface $cache;
    private string $cachePrefix;
    private int $cacheTtl;

    /**
     * @param ?string $accessToken Bearer token (null when using TokenManager)
     * @param ?Environment $environment API environment
     * @param ?ClientConfig $config Custom HTTP client configuration
     * @param ?CacheInterface $cache PSR-16 cache for service responses
     * @param ?string $cachePrefix Cache key prefix. Null = auto-derive from TokenManager credentials. Empty string = no prefix.
     * @param int $cacheTtl Cache TTL in seconds (default: 30 days)
     */
    public function __construct(
        ?string $accessToken = null,
        ?Environment $environment = Environment::TEST,
        ?ClientConfig $config = null,
        ?CacheInterface $cache = null,
        ?string $cachePrefix = null,
        int $cacheTtl = self::DEFAULT_CACHE_TTL,
    ) {
        $this->accessToken = $accessToken;
        $this->environment = $environment;
        $this->config = $config ?? new ClientConfig();
        $this->cache = $cache;
        $this->cachePrefix = $cachePrefix ?? $this->deriveCachePrefix();
        $this->cacheTtl = $cacheTtl;
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
     * Invalidate the session by deleting the refresh token from the API server.
     *
     * @param string $refreshToken The refresh token to revoke
     *
     * @throws ErganiException
     */
    public function logout(string $refreshToken): bool
    {
        $auth = new AuthenticationLogout($this->accessToken, $this->environment, $this->config);

        return $auth->handle($refreshToken);
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
     * @throws ErganiException
     * @throws InvalidArgumentException
     */
    public function getEmployerInfo(): EmployerResponse
    {
        return $this->cached(
            'employer_info',
            EmployerResponse::class,
            fn() => (new EmployerInfo($this->accessToken, $this->environment, $this->config))->handle(),
        );
    }

    /**
     * @throws ErganiException
     * @throws InvalidArgumentException
     */
    public function getBranches(): BranchCollection
    {
        return $this->cached(
            'branches',
            BranchCollection::class,
            fn() => (new BranchInfo($this->accessToken, $this->environment, $this->config))->handle(),
        );
    }

    /**
     * Retrieve monthly employee status for reporting.
     *
     * @param int $year Report year (e.g., 2025)
     * @param int $month Report month (1-12)
     *
     * @return EmployeeStatusResponse[]
     * @throws ErganiException
     */
    public function getMonthlyStatus(int $year, int $month): array
    {
        return (new MonthlyStatus($this->accessToken, $this->environment, $this->config))
            ->handle($year, $month);
    }

    /**
     * Retrieve current workforce status, optionally filtered by employee TIN.
     *
     * @param string|null $tin Employee tax identification number (optional)
     *
     * @return WorkforceStatusResponse[]
     * @throws ErganiException
     */
    public function getWorkforceStatus(?string $tin = null): array
    {
        return (new WorkforceStatus($this->accessToken, $this->environment, $this->config))
            ->handle($tin);
    }

    /**
     * Retrieve the acceptance status of essential terms declarations.
     *
     * @param string $tin Employee tax identification number
     * @param string $protocol Declaration protocol number
     * @param \DateTime|string $date Declaration submission date (DD/MM/YYYY)
     *
     * @throws ErganiException
     */
    public function getAcceptanceStatus(string $tin, string $protocol, \DateTime|string $date): ?AcceptanceStatusResponse
    {
        return (new AcceptanceStatus($this->accessToken, $this->environment, $this->config))
            ->handle($tin, $protocol, $date);
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
     * @throws InvalidArgumentException
     *
     * @see ParameterLookup::WORK_TIME_TYPE
     * @see ParameterLookup::NATIONALITY
     * @see ParameterLookup::BANK
     */
    public function getParameters(string $parameter): ParameterCollection
    {
        return $this->cached(
            "parameters:{$parameter}",
            ParameterCollection::class,
            fn() => (new ParameterLookup($this->accessToken, $this->environment, $this->config))
                ->handle($parameter),
        );
    }

    /**
     * Clear all cached data for the current prefix (current credentials).
     *
     * @throws InvalidArgumentException
     */
    public function clearCache(): bool
    {
        if ($this->cache === null) {
            return false;
        }

        $keys = ['employer_info', 'branches'];

        foreach ($this->parameterConstants() as $constant) {
            $keys[] = "parameters:{$constant}";
        }

        $this->cache->deleteMultiple(
            array_map(fn(string $key) => $this->cacheKey($key), $keys),
        );

        return true;
    }

    /**
     * Flush the entire cache store (all prefixes, all credentials).
     *
     * Use this to clean up remnants from old credentials.
     */
    public static function flushCache(CacheInterface $cache): bool
    {
        return $cache->clear();
    }

    /**
     * @throws InvalidArgumentException
     */
    public function clearEmployerCache(): bool
    {
        return $this->cache?->delete($this->cacheKey('employer_info')) ?? false;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function clearBranchCache(): bool
    {
        return $this->cache?->delete($this->cacheKey('branches')) ?? false;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function clearParameterCache(?string $parameter = null): bool
    {
        if ($this->cache === null) {
            return false;
        }

        if ($parameter !== null) {
            return $this->cache->delete($this->cacheKey("parameters:{$parameter}"));
        }

        $keys = array_map(
            fn(string $constant) => $this->cacheKey("parameters:{$constant}"),
            $this->parameterConstants(),
        );

        $this->cache->deleteMultiple($keys);

        return true;
    }

    /**
     * @template T of object
     *
     * @param string $key
     * @param class-string<T> $type
     * @param callable(): T $factory
     *
     * @return T
     * @throws InvalidArgumentException
     */
    private function cached(string $key, string $type, callable $factory): mixed
    {
        $cacheKey = $this->cacheKey($key);

        if ($this->cache !== null) {
            $cached = $this->cache->get($cacheKey);
            if ($cached instanceof $type) {
                return $cached;
            }
        }

        $result = $factory();

        $this->cache?->set($cacheKey, $result, $this->cacheTtl);

        return $result;
    }

    private function cacheKey(string $key): string
    {
        return $this->cachePrefix !== ''
            ? $this->cachePrefix . ':' . $key
            : $key;
    }

    /**
     * Derives a cache prefix from the global TokenManager credentials.
     */
    private function deriveCachePrefix(): string
    {
        $tokenManager = Token::currentTokenManager();

        if ($tokenManager instanceof Token) {
            return $tokenManager->cacheIdentifier();
        }

        return '';
    }

    /**
     * @return array<int, string>
     */
    private function parameterConstants(): array
    {
        $reflection = new ReflectionClass(ParameterLookup::class);

        return array_values($reflection->getConstants());
    }
}
