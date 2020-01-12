<?php

declare(strict_types=1);

namespace Uniterm\Actions;

use Uniterm\Client;
use Uniterm\Response;
use Uniterm\Actions\BaseAction;

class InputTest extends BaseAction
{
    /**
     * @test
     */
    public function it_successfully_completes_command()
    {
        $response = $this->client->input(Client::REQ_INPUT_TYPE_ZIP);
        
        $this->assertTrue($response->isSuccess());
        $this->assertNotEmpty($response->input());
    }
}
