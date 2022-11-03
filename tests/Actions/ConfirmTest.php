<?php

declare(strict_types=1);

namespace Uniterm\Actions;

use Uniterm\Actions\BaseAction;

class ConfirmTest extends BaseAction
{
    /**
     * @test
     */
    public function it_successfully_completes_command()
    {
        $response = $this->client->confirm('Are you sure you would like to cancel?');
        
        $this->assertTrue($response->isSuccess());
        $this->assertTrue($response->isConfirmed());
    }
}
