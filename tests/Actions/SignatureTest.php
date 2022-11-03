<?php

declare(strict_types=1);

namespace Uniterm\Actions;

use Uniterm\Actions\BaseAction;

class SignatureTest extends BaseAction
{
    /**
     * @test
     */
    public function it_successfully_completes_command()
    {
        $response = $this->client->signature();
        
        $this->assertTrue($response->isSuccess());
        $this->assertNotEmpty($response->signature());
    }
}
