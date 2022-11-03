<?php

declare(strict_types=1);

namespace Uniterm\Actions;

use Uniterm\Actions\BaseAction;

class VersionTest extends BaseAction
{
    /**
     * @test
     */
    public function it_successfully_completes_command()
    {
        $response = $this->client->version();
        
        $this->assertTrue($response->isSuccess());
    }
}
