<?php

declare(strict_types=1);

namespace Uniterm\Actions;

use Uniterm\Response;
use Uniterm\Actions\BaseAction;

class StartTransactionTest extends BaseAction
{
    /**
     * @test
     */
    public function it_successfully_completes_command()
    {
        $response = $this->client->startTransaction(1);
        
        $this->assertTrue($response->isSuccess());
    }
}
