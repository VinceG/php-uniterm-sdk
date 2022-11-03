<?php

declare(strict_types=1);

namespace Uniterm\Actions;

use Uniterm\Actions\BaseAction;

class VoidTest extends BaseAction
{
    /**
     * @test
     */
    public function it_successfully_completes_command()
    {
        $response = $this->client->sale(100, 1);
        
        $this->assertTrue($response->isSuccess());

        $transactionId = $response->transactionId();

        $response = $this->client->void($transactionId);
        
        $this->assertTrue($response->isSuccess());
    }
}
