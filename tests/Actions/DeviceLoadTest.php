<?php

declare(strict_types=1);

namespace Uniterm\Actions;

use Uniterm\Actions\BaseAction;

class DeviceLoadTest extends BaseAction
{
    /**
     * @test
     */
    public function it_successfully_completes_command()
    {
        $response = $this->client->load();

        $this->assertTrue($response->isSuccess());
    }
}
