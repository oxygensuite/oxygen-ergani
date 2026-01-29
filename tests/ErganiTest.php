<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace Tests;

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response as HttpResponse;
use OxygenSuite\OxygenErgani\Cache\InMemoryCache;
use OxygenSuite\OxygenErgani\Enums\Environment;
use OxygenSuite\OxygenErgani\Ergani;
use OxygenSuite\OxygenErgani\Http\ClientConfig;
use OxygenSuite\OxygenErgani\Http\Documents\Hiring\HiringNew;
use OxygenSuite\OxygenErgani\Http\Services\ParameterLookup;
use OxygenSuite\OxygenErgani\Models\Dismissal\DismissalWithoutNoticeDeclaration;
use OxygenSuite\OxygenErgani\Models\Hiring\NewDeclaration;
use OxygenSuite\OxygenErgani\Models\Modification\ModificationDeclaration;
use OxygenSuite\OxygenErgani\Models\Termination\VoluntaryResignationDeclaration;
use OxygenSuite\OxygenErgani\Models\WorkingStatus\WorkingStatus;
use OxygenSuite\OxygenErgani\Responses\BranchCollection;
use OxygenSuite\OxygenErgani\Responses\BranchResponse;
use OxygenSuite\OxygenErgani\Responses\EmployeeStatusResponse;
use OxygenSuite\OxygenErgani\Responses\EmployerResponse;
use OxygenSuite\OxygenErgani\Responses\ParameterCollection;
use OxygenSuite\OxygenErgani\Responses\ParameterResponse;
use OxygenSuite\OxygenErgani\Responses\SubmissionResponse;
use OxygenSuite\OxygenErgani\Storage\InMemoryToken;
use OxygenSuite\OxygenErgani\Storage\Token;

class ErganiTest extends TestCase
{
    public function test_get_parameters(): void
    {
        $config = (new ClientConfig())->setHandler($this->mockResponse(200, 'ex-base-03.json'));
        $ergani = new Ergani('test-access-token', config: $config);

        $parameters = $ergani->getParameters(ParameterLookup::WORK_TIME_TYPE);

        $this->assertInstanceOf(ParameterCollection::class, $parameters);
        $this->assertCount(42, $parameters);

        // Verify we can access parameters
        $workType = $parameters->find('ΕΡΓ');
        $this->assertInstanceOf(ParameterResponse::class, $workType);
        $this->assertSame('ΕΡΓΑΣΙΑ', $workType->description);
    }

    public function test_get_parameters_for_dropdown(): void
    {
        $config = (new ClientConfig())->setHandler($this->mockResponse(200, 'ex-base-03.json'));
        $ergani = new Ergani('test-access-token', config: $config);

        $dropdown = $ergani->getParameters(ParameterLookup::WORK_TIME_TYPE)->toDropdown();

        $this->assertIsArray($dropdown);
        $this->assertSame('ΕΡΓΑΣΙΑ', $dropdown['ΕΡΓ']);
        $this->assertSame('ΤΗΛΕΡΓΑΣΙΑ', $dropdown['ΤΗΛ']);
    }

    public function test_get_employer_info(): void
    {
        $config = (new ClientConfig())->setHandler($this->mockResponse(200, 'ex-base-01.json'));
        $ergani = new Ergani('test-access-token', config: $config);

        $employer = $ergani->getEmployerInfo();

        $this->assertInstanceOf(EmployerResponse::class, $employer);
        $this->assertSame('12345', $employer->id);
        $this->assertSame('12345678', $employer->tin);
        $this->assertSame('ERGANI A.E', $employer->name);
    }

    public function test_get_branches(): void
    {
        $config = (new ClientConfig())->setHandler($this->mockResponse(200, 'ex-base-02.json'));
        $ergani = new Ergani('test-access-token', config: $config);

        $branches = $ergani->getBranches();

        $this->assertInstanceOf(BranchCollection::class, $branches);
        $this->assertCount(2, $branches);

        $branch = $branches->find('0');
        $this->assertInstanceOf(BranchResponse::class, $branch);
        $this->assertSame('ΟΔΟΣ Α 12345 ΑΘΗΝΑ', $branch->address);
    }

    public function test_get_parameters_cached(): void
    {
        $cache = new InMemoryCache();
        $config = (new ClientConfig())->setHandler(new MockHandler([
            new HttpResponse(200, body: $this->readFile('ex-base-03.json')),
        ]));
        $ergani = new Ergani('test-access-token', config: $config, cache: $cache);

        // First call hits API
        $result1 = $ergani->getParameters(ParameterLookup::WORK_TIME_TYPE);
        $this->assertCount(42, $result1);

        // Second call returns from cache (no mock response queued, would throw if called)
        $result2 = $ergani->getParameters(ParameterLookup::WORK_TIME_TYPE);
        $this->assertCount(42, $result2);
        $this->assertSame($result1, $result2);
    }

    public function test_get_employer_info_cached(): void
    {
        $cache = new InMemoryCache();
        $config = (new ClientConfig())->setHandler(new MockHandler([
            new HttpResponse(200, body: $this->readFile('ex-base-01.json')),
        ]));
        $ergani = new Ergani('test-access-token', config: $config, cache: $cache);

        $result1 = $ergani->getEmployerInfo();
        $this->assertSame('12345', $result1->id);

        // Second call from cache
        $result2 = $ergani->getEmployerInfo();
        $this->assertSame($result1, $result2);
    }

    public function test_get_branches_cached(): void
    {
        $cache = new InMemoryCache();
        $config = (new ClientConfig())->setHandler(new MockHandler([
            new HttpResponse(200, body: $this->readFile('ex-base-02.json')),
        ]));
        $ergani = new Ergani('test-access-token', config: $config, cache: $cache);

        $result1 = $ergani->getBranches();
        $this->assertCount(2, $result1);

        // Second call from cache
        $result2 = $ergani->getBranches();
        $this->assertSame($result1, $result2);
    }

    public function test_cache_prefix_isolation(): void
    {
        $cache = new InMemoryCache();
        $response = $this->readFile('ex-base-01.json');

        $config1 = (new ClientConfig())->setHandler(new MockHandler([
            new HttpResponse(200, body: $response),
        ]));
        $ergani1 = new Ergani('token-1', config: $config1, cache: $cache, cachePrefix: 'user1');

        $config2 = (new ClientConfig())->setHandler(new MockHandler([
            new HttpResponse(200, body: $response),
        ]));
        $ergani2 = new Ergani('token-2', config: $config2, cache: $cache, cachePrefix: 'user2');

        $result1 = $ergani1->getEmployerInfo();
        $result2 = $ergani2->getEmployerInfo();

        // Both should succeed (neither should use the other's cache)
        $this->assertSame('12345', $result1->id);
        $this->assertSame('12345', $result2->id);

        // They are separate cached objects
        $this->assertNotSame($result1, $result2);
    }

    public function test_clear_cache(): void
    {
        $cache = new InMemoryCache();
        $config = (new ClientConfig())->setHandler(new MockHandler([
            new HttpResponse(200, body: $this->readFile('ex-base-01.json')),
            new HttpResponse(200, body: $this->readFile('ex-base-01.json')),
        ]));
        $ergani = new Ergani('test-access-token', config: $config, cache: $cache);

        $ergani->getEmployerInfo();
        $this->assertTrue($ergani->clearCache());

        // After clearing, should hit API again
        $result = $ergani->getEmployerInfo();
        $this->assertSame('12345', $result->id);
    }

    public function test_clear_employer_cache(): void
    {
        $cache = new InMemoryCache();
        $config = (new ClientConfig())->setHandler(new MockHandler([
            new HttpResponse(200, body: $this->readFile('ex-base-01.json')),
            new HttpResponse(200, body: $this->readFile('ex-base-01.json')),
        ]));
        $ergani = new Ergani('test-access-token', config: $config, cache: $cache);

        $ergani->getEmployerInfo();
        $this->assertTrue($ergani->clearEmployerCache());

        // Should hit API again
        $result = $ergani->getEmployerInfo();
        $this->assertSame('12345', $result->id);
    }

    public function test_clear_parameter_cache(): void
    {
        $cache = new InMemoryCache();
        $config = (new ClientConfig())->setHandler(new MockHandler([
            new HttpResponse(200, body: $this->readFile('ex-base-03.json')),
            new HttpResponse(200, body: $this->readFile('ex-base-03.json')),
        ]));
        $ergani = new Ergani('test-access-token', config: $config, cache: $cache);

        $ergani->getParameters(ParameterLookup::WORK_TIME_TYPE);
        $this->assertTrue($ergani->clearParameterCache(ParameterLookup::WORK_TIME_TYPE));

        // Should hit API again
        $result = $ergani->getParameters(ParameterLookup::WORK_TIME_TYPE);
        $this->assertCount(42, $result);
    }

    public function test_clear_all_parameter_cache(): void
    {
        $cache = new InMemoryCache();
        $config = (new ClientConfig())->setHandler(new MockHandler([
            new HttpResponse(200, body: $this->readFile('ex-base-03.json')),
            new HttpResponse(200, body: $this->readFile('ex-base-03.json')),
        ]));
        $ergani = new Ergani('test-access-token', config: $config, cache: $cache);

        $ergani->getParameters(ParameterLookup::WORK_TIME_TYPE);
        $this->assertTrue($ergani->clearParameterCache());

        // Should hit API again
        $result = $ergani->getParameters(ParameterLookup::WORK_TIME_TYPE);
        $this->assertCount(42, $result);
    }

    public function test_no_caching_when_cache_is_null(): void
    {
        $config = (new ClientConfig())->setHandler(new MockHandler([
            new HttpResponse(200, body: $this->readFile('ex-base-01.json')),
            new HttpResponse(200, body: $this->readFile('ex-base-01.json')),
        ]));
        $ergani = new Ergani('test-access-token', config: $config);

        $result1 = $ergani->getEmployerInfo();
        $result2 = $ergani->getEmployerInfo();

        // Both hit the API (different objects)
        $this->assertNotSame($result1, $result2);
        $this->assertSame('12345', $result1->id);
        $this->assertSame('12345', $result2->id);
    }

    public function test_clear_cache_returns_false_without_cache(): void
    {
        $ergani = new Ergani('test-access-token');

        $this->assertFalse($ergani->clearCache());
        $this->assertFalse($ergani->clearEmployerCache());
        $this->assertFalse($ergani->clearBranchCache());
        $this->assertFalse($ergani->clearParameterCache());
    }

    public function test_flush_cache(): void
    {
        $cache = new InMemoryCache();
        $config = (new ClientConfig())->setHandler(new MockHandler([
            new HttpResponse(200, body: $this->readFile('ex-base-01.json')),
            new HttpResponse(200, body: $this->readFile('ex-base-01.json')),
        ]));
        $ergani = new Ergani('test-access-token', config: $config, cache: $cache, cachePrefix: 'user1');

        $ergani->getEmployerInfo();
        $this->assertTrue(Ergani::flushCache($cache));

        // After flushing, should hit API again
        $result = $ergani->getEmployerInfo();
        $this->assertSame('12345', $result->id);
    }

    public function test_auto_prefix_from_token_manager(): void
    {
        $cache = new InMemoryCache();
        $response = $this->readFile('ex-base-01.json');

        // Set up token manager with credentials A
        Token::setCurrentTokenManager(
            InMemoryToken::fake('userA', 'passA'),
            Environment::TEST,
        );

        $config1 = (new ClientConfig())->setHandler(new MockHandler([
            new HttpResponse(200, body: $response),
        ]));
        $ergani1 = new Ergani('token-1', config: $config1, cache: $cache);
        $result1 = $ergani1->getEmployerInfo();

        // Change to credentials B - different auto-derived prefix
        Token::setCurrentTokenManager(
            InMemoryToken::fake('userB', 'passB'),
            Environment::TEST,
        );

        $config2 = (new ClientConfig())->setHandler(new MockHandler([
            new HttpResponse(200, body: $response),
        ]));
        $ergani2 = new Ergani('token-2', config: $config2, cache: $cache);
        $result2 = $ergani2->getEmployerInfo();

        // Both succeeded separately (different auto-derived prefixes, both hit API)
        $this->assertSame('12345', $result1->id);
        $this->assertSame('12345', $result2->id);
        $this->assertNotSame($result1, $result2);

        // Clean up
        Token::setCurrentTokenManager(null);
    }

    public function test_auto_prefix_is_empty_without_token_manager(): void
    {
        Token::setCurrentTokenManager(null);

        $cache = new InMemoryCache();
        $config = (new ClientConfig())->setHandler(new MockHandler([
            new HttpResponse(200, body: $this->readFile('ex-base-01.json')),
        ]));
        $ergani = new Ergani('test-access-token', config: $config, cache: $cache);

        // Should work with empty prefix
        $result = $ergani->getEmployerInfo();
        $this->assertSame('12345', $result->id);
    }

    public function test_get_monthly_status(): void
    {
        $config = (new ClientConfig())->setHandler($this->mockResponse(200, 'ex-base-04.json'));
        $ergani = new Ergani('test-access-token', config: $config);

        $employees = $ergani->getMonthlyStatus(2025, 1);

        $this->assertCount(2, $employees);
        $this->assertInstanceOf(EmployeeStatusResponse::class, $employees[0]);
        $this->assertSame('123456789', $employees[0]->afm);
        $this->assertSame('ΠΑΠΑΔΟΠΟΥΛΟΣ', $employees[0]->lastName);
    }

    public function test_send_hiring_new(): void
    {
        $config = (new ClientConfig())->setHandler($this->mockResponse(200, 'hiring-new.json'));
        $ergani = new Ergani('test-access-token', config: $config);

        $declaration = NewDeclaration::factory()->make();
        $responses = $ergani->sendHiringNew($declaration);

        $this->assertCount(1, $responses);
        $this->assertInstanceOf(SubmissionResponse::class, $responses[0]);
        $this->assertSame('E3N200', $responses[0]->protocol);
    }

    public function test_send_voluntary_resignation(): void
    {
        $config = (new ClientConfig())->setHandler($this->mockResponse(200, 'termination-voluntary-resignation.json'));
        $ergani = new Ergani('test-access-token', config: $config);

        $declaration = VoluntaryResignationDeclaration::factory()->make();
        $responses = $ergani->sendVoluntaryResignation($declaration);

        $this->assertCount(1, $responses);
        $this->assertInstanceOf(SubmissionResponse::class, $responses[0]);
    }

    public function test_send_dismissal_without_notice(): void
    {
        $config = (new ClientConfig())->setHandler($this->mockResponse(200, 'dismissal-without-notice.json'));
        $ergani = new Ergani('test-access-token', config: $config);

        $declaration = DismissalWithoutNoticeDeclaration::factory()->make();
        $responses = $ergani->sendDismissalWithoutNotice($declaration);

        $this->assertCount(1, $responses);
        $this->assertInstanceOf(SubmissionResponse::class, $responses[0]);
    }

    public function test_send_employment_modification(): void
    {
        $config = (new ClientConfig())->setHandler($this->mockResponse(200, 'employment-modification.json'));
        $ergani = new Ergani('test-access-token', config: $config);

        $declaration = ModificationDeclaration::factory()->make();
        $responses = $ergani->sendEmploymentModification($declaration);

        $this->assertCount(1, $responses);
        $this->assertInstanceOf(SubmissionResponse::class, $responses[0]);
    }

    public function test_send_working_status_change(): void
    {
        $config = (new ClientConfig())->setHandler($this->mockResponse(200, 'working-status-change.json'));
        $ergani = new Ergani('test-access-token', config: $config);

        $workingStatus = WorkingStatus::factory()->make();
        $responses = $ergani->sendWorkingStatusChange($workingStatus);

        $this->assertCount(1, $responses);
        $this->assertInstanceOf(SubmissionResponse::class, $responses[0]);
    }

    public function test_get_submissions(): void
    {
        $config = (new ClientConfig())->setHandler($this->mockResponse(200, 'lookup-submissions.json'));
        $ergani = new Ergani('test-access-token', config: $config);

        $submissions = $ergani->getSubmissions();

        $this->assertIsArray($submissions);
        $this->assertNotEmpty($submissions);
        $this->assertArrayHasKey('code', $submissions[0]);
    }

    public function test_get_schema(): void
    {
        $config = (new ClientConfig())->setHandler($this->mockResponse(200, 'work-card-schema.json'));
        $ergani = new Ergani('test-access-token', config: $config);

        $schema = $ergani->getSchema(HiringNew::class);

        $this->assertIsArray($schema);
    }

    public function test_cancel_document(): void
    {
        $config = (new ClientConfig())->setHandler(new MockHandler([
            new HttpResponse(200),
        ]));
        $ergani = new Ergani('test-access-token', config: $config);

        $result = $ergani->cancelDocument('WTOLeave', 'WTO12345', 20250115);

        $this->assertTrue($result);
    }
}
