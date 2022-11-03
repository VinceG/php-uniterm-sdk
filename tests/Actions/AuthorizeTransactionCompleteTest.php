<?php

declare(strict_types=1);

namespace Uniterm\Actions;

use Uniterm\Actions\BaseAction;

class AuthorizeTransactionCompleteTest extends BaseAction
{
    /**
     * @test
     */
    public function it_successfully_completes_command()
    {
        $response = $this->client->authorize(10, 1);
        
        $this->assertTrue($response->isSuccess());

        $transactionId = $response->transactionId();

        $response = $this->client->authorizeComplete($transactionId);
        
        $this->assertTrue($response->isSuccess());
    }
}
