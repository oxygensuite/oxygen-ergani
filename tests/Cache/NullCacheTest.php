<?php

namespace Tests\Cache;

use OxygenSuite\OxygenErgani\Cache\NullCache;
use PHPUnit\Framework\TestCase;

class NullCacheTest extends TestCase
{
    private NullCache $cache;

    protected function setUp(): void
    {
        $this->cache = new NullCache();
    }

    public function test_get_always_returns_default(): void
    {
        $this->assertNull($this->cache->get('any-key'));
        $this->assertSame('default', $this->cache->get('any-key', 'default'));
    }

    public function test_set_returns_true(): void
    {
        $this->assertTrue($this->cache->set('key', 'value'));
    }

    public function test_set_does_not_store(): void
    {
        $this->cache->set('key', 'value');
        $this->assertNull($this->cache->get('key'));
    }

    public function test_delete_returns_true(): void
    {
        $this->assertTrue($this->cache->delete('key'));
    }

    public function test_clear_returns_true(): void
    {
        $this->assertTrue($this->cache->clear());
    }

    public function test_has_always_returns_false(): void
    {
        $this->cache->set('key', 'value');
        $this->assertFalse($this->cache->has('key'));
    }

    public function test_get_multiple_returns_defaults(): void
    {
        $result = $this->cache->getMultiple(['key1', 'key2'], 'default');

        $this->assertSame('default', $result['key1']);
        $this->assertSame('default', $result['key2']);
    }

    public function test_set_multiple_returns_true(): void
    {
        $this->assertTrue($this->cache->setMultiple(['key1' => 'value1', 'key2' => 'value2']));
    }

    public function test_delete_multiple_returns_true(): void
    {
        $this->assertTrue($this->cache->deleteMultiple(['key1', 'key2']));
    }
}
