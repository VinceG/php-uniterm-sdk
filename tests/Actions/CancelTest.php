<?php

declare(strict_types=1);

namespace Uniterm\Actions;

use Uniterm\Response;
use Uniterm\Actions\BaseAction;

class CancelTest extends BaseAction
{
    /**
     * @test
     */
    public function it_successfully_completes_command()
    {
        $response = $this->client->cancel(1);

        $this->assertEquals(Response::ID_NOT_FOUND, $response->errorCode());
    }
}
