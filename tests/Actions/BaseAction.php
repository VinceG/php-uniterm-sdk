<?php

declare(strict_types=1);

namespace Uniterm\Actions;

use Uniterm\Client;
use Uniterm\TestCase;

class BaseAction extends TestCase
{
    protected $client;

    protected function setUp(): void
    {
        $this->client = new Client(getenv('UNITERM_USERNAME'), getenv('UNITERM_PASSWORD'), getenv('UNITERM_DEVICE'));
    }
}
