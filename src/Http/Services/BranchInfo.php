<?php

namespace OxygenSuite\OxygenErgani\Http\Services;

use OxygenSuite\OxygenErgani\Exceptions\ErganiException;
use OxygenSuite\OxygenErgani\Responses\BranchCollection;
use OxygenSuite\OxygenErgani\Responses\BranchResponse;

class BranchInfo extends Service
{
    /**
     * Retrieves all branches for the employer
     *
     * @throws ErganiException
     */
    public function handle(): BranchCollection
    {
        $data = $this->execute()->json();
        $branches = $data[$this->serviceCode()]['Pararthma'] ?? [];

        return new BranchCollection(array_map(
            fn(array $branch) => new BranchResponse($branch),
            $branches,
        ));
    }

    protected function serviceCode(): string
    {
        return 'EX_BASE_02';
    }
}
