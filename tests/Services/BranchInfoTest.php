<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace Tests\Services;

use OxygenSuite\OxygenErgani\Http\Services\BranchInfo;
use OxygenSuite\OxygenErgani\Responses\BranchCollection;
use OxygenSuite\OxygenErgani\Responses\BranchResponse;
use Tests\TestCase;

class BranchInfoTest extends TestCase
{
    public function test_branch_info(): void
    {
        $service = new BranchInfo('test-access-token');
        $service->getConfig()->setHandler($this->mockResponse(200, 'ex-base-02.json'));

        $response = $service->handle();

        $this->assertInstanceOf(BranchCollection::class, $response);
        $this->assertTrue($service->isSuccessful());
    }

    public function test_branch_info_response(): void
    {
        $service = new BranchInfo('test-access-token');
        $service->getConfig()->setHandler($this->mockResponse(200, 'ex-base-02.json'));

        $branches = $service->handle();

        $this->assertCount(2, $branches);

        // BranchCollection is keyed by branch code (aa)
        $this->assertInstanceOf(BranchResponse::class, $branches['0']);
        $this->assertSame('0', $branches['0']->aa);
        $this->assertSame('ΟΔΟΣ Α 12345 ΑΘΗΝΑ', $branches['0']->address);

        $this->assertInstanceOf(BranchResponse::class, $branches['1']);
        $this->assertSame('1', $branches['1']->aa);
        $this->assertSame('ΟΔΟΣ Β 12345 ΑΘΗΝΑ', $branches['1']->address);
    }

    public function test_branch_collection_methods(): void
    {
        $service = new BranchInfo('test-access-token');
        $service->getConfig()->setHandler($this->mockResponse(200, 'ex-base-02.json'));

        $branches = $service->handle();

        // Test find method
        $this->assertInstanceOf(BranchResponse::class, $branches->find('0'));
        $this->assertNull($branches->find('999'));

        // Test has method
        $this->assertTrue($branches->has('0'));
        $this->assertFalse($branches->has('999'));

        // Test first/last
        $this->assertSame('0', $branches->first()->aa);
        $this->assertSame('1', $branches->last()->aa);

        // Test search by address
        $results = $branches->search('ΟΔΟΣ Α');
        $this->assertCount(1, $results);
        $this->assertSame('0', $results->first()->aa);

        // Test toDropdown
        $dropdown = $branches->toDropdown();
        $this->assertSame(['0' => 'ΟΔΟΣ Α 12345 ΑΘΗΝΑ', '1' => 'ΟΔΟΣ Β 12345 ΑΘΗΝΑ'], $dropdown);
    }
}
