<?php

declare(strict_types=1);

namespace Uniterm\Actions;

use Uniterm\Actions\BaseAction;

class ReversalTest extends BaseAction
{
    /**
     * @test
     */
    public function it_successfully_completes_command()
    {
        $response = $this->client->sale(10, 1);
        
        $this->assertTrue($response->isSuccess());

        $transactionId = $response->transactionId();

        $response = $this->client->reversal($transactionId);
        
        $this->assertTrue($response->isSuccess());
    }
}
