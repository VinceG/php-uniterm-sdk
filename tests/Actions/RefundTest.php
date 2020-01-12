<?php

declare(strict_types=1);

namespace Uniterm\Actions;

use Uniterm\Response;
use Uniterm\Actions\BaseAction;

class RefundTest extends BaseAction
{
    /**
     * @test
     */
    public function it_successfully_completes_command()
    {
        $response = $this->client->refund(10, 1);
        
        $this->assertTrue($response->isSuccess());
    }
}
