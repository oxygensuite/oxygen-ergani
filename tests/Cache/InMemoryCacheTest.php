<?php

namespace Tests\Cache;

use DateInterval;
use OxygenSuite\OxygenErgani\Cache\InMemoryCache;
use PHPUnit\Framework\TestCase;

class InMemoryCacheTest extends TestCase
{
    private InMemoryCache $cache;

    protected function setUp(): void
    {
        $this->cache = new InMemoryCache();
    }

    public function test_get_returns_default_for_missing_key(): void
    {
        $this->assertNull($this->cache->get('missing'));
        $this->assertSame('fallback', $this->cache->get('missing', 'fallback'));
    }

    public function test_set_and_get(): void
    {
        $this->assertTrue($this->cache->set('key', 'value'));
        $this->assertSame('value', $this->cache->get('key'));
    }

    public function test_set_with_null_ttl_stores_forever(): void
    {
        $this->cache->set('key', 'value', null);
        $this->assertSame('value', $this->cache->get('key'));
    }

    public function test_set_with_zero_ttl_deletes(): void
    {
        $this->cache->set('key', 'value');
        $this->cache->set('key', 'new', 0);
        $this->assertNull($this->cache->get('key'));
    }

    public function test_set_with_negative_ttl_deletes(): void
    {
        $this->cache->set('key', 'value');
        $this->cache->set('key', 'new', -1);
        $this->assertNull($this->cache->get('key'));
    }

    public function test_set_with_date_interval(): void
    {
        $this->cache->set('key', 'value', new DateInterval('PT1H'));
        $this->assertSame('value', $this->cache->get('key'));
    }

    public function test_delete(): void
    {
        $this->cache->set('key', 'value');
        $this->assertTrue($this->cache->delete('key'));
        $this->assertNull($this->cache->get('key'));
    }

    public function test_delete_nonexistent_key(): void
    {
        $this->assertTrue($this->cache->delete('nonexistent'));
    }

    public function test_clear(): void
    {
        $this->cache->set('key1', 'value1');
        $this->cache->set('key2', 'value2');
        $this->assertTrue($this->cache->clear());
        $this->assertNull($this->cache->get('key1'));
        $this->assertNull($this->cache->get('key2'));
    }

    public function test_has(): void
    {
        $this->assertFalse($this->cache->has('key'));
        $this->cache->set('key', 'value');
        $this->assertTrue($this->cache->has('key'));
    }

    public function test_has_with_null_value(): void
    {
        $this->cache->set('key', null);
        $this->assertTrue($this->cache->has('key'));
    }

    public function test_get_multiple(): void
    {
        $this->cache->set('key1', 'value1');
        $this->cache->set('key2', 'value2');

        $result = $this->cache->getMultiple(['key1', 'key2', 'key3'], 'default');

        $this->assertSame('value1', $result['key1']);
        $this->assertSame('value2', $result['key2']);
        $this->assertSame('default', $result['key3']);
    }

    public function test_set_multiple(): void
    {
        $this->assertTrue($this->cache->setMultiple(['key1' => 'value1', 'key2' => 'value2']));

        $this->assertSame('value1', $this->cache->get('key1'));
        $this->assertSame('value2', $this->cache->get('key2'));
    }

    public function test_delete_multiple(): void
    {
        $this->cache->set('key1', 'value1');
        $this->cache->set('key2', 'value2');
        $this->cache->set('key3', 'value3');

        $this->assertTrue($this->cache->deleteMultiple(['key1', 'key2']));

        $this->assertNull($this->cache->get('key1'));
        $this->assertNull($this->cache->get('key2'));
        $this->assertSame('value3', $this->cache->get('key3'));
    }

    public function test_stores_various_types(): void
    {
        $this->cache->set('string', 'hello');
        $this->cache->set('int', 42);
        $this->cache->set('float', 3.14);
        $this->cache->set('bool', true);
        $this->cache->set('array', ['a' => 1]);
        $this->cache->set('null', null);

        $this->assertSame('hello', $this->cache->get('string'));
        $this->assertSame(42, $this->cache->get('int'));
        $this->assertSame(3.14, $this->cache->get('float'));
        $this->assertTrue($this->cache->get('bool'));
        $this->assertSame(['a' => 1], $this->cache->get('array'));
        $this->assertNull($this->cache->get('null'));
    }

    public function test_overwrite_existing_key(): void
    {
        $this->cache->set('key', 'original');
        $this->cache->set('key', 'updated');
        $this->assertSame('updated', $this->cache->get('key'));
    }

    public function test_clear_expired_removes_only_expired(): void
    {
        $this->cache->set('forever', 'stays');
        $this->cache->set('long', 'stays', 3600);
        $this->cache->set('expired', 'gone', 1);

        // Manually expire by setting a past expiry via reflection
        $reflection = new \ReflectionClass($this->cache);
        $store = $reflection->getProperty('store');
        $items = $store->getValue($this->cache);
        $items['expired']['expiry'] = time() - 10;
        $store->setValue($this->cache, $items);

        $removed = $this->cache->clearExpired();

        $this->assertSame(1, $removed);
        $this->assertSame('stays', $this->cache->get('forever'));
        $this->assertSame('stays', $this->cache->get('long'));
        $this->assertNull($this->cache->get('expired'));
    }

    public function test_clear_expired_returns_zero_when_nothing_expired(): void
    {
        $this->cache->set('key', 'value');
        $this->assertSame(0, $this->cache->clearExpired());
    }
}
