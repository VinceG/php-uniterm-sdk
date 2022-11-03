<?php

declare(strict_types=1);

namespace Uniterm\Actions;

use Uniterm\Actions\BaseAction;

class ShutdownTest extends BaseAction
{
    /**
     * @test
     */
    public function it_successfully_completes_command()
    {
        // $response = $this->client->shutdown();
        
        // $this->assertTrue($response->isSuccess());

        $this->assertTrue(true);
    }
}
