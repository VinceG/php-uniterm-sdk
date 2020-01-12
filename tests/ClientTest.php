<?php

declare(strict_types=1);

namespace Uniterm;

use Uniterm\Client;
use Uniterm\TestCase;
use Uniterm\XMLBuilder;

class ClientTest extends TestCase
{
    /**
     * @test
     */
    public function it_can_create_a_new_instance()
    {
        $this->assertInstanceOf(Client::class, new Client('test_retail:public', 'publ1ct3st', 'IP'));
    }

    /**
     * @test
     */
    public function it_can_assign_new_values_when_instantiated()
    {
        $client = new Client('test_retail:public', 'publ1ct3st', 'IP');
        
        $this->assertEquals('test_retail:public', $client->getUsername());
        $this->assertEquals('publ1ct3st', $client->getPassword());
        $this->assertEquals('IP', $client->getDevice());
    }

    /**
     * @test
     */
    public function it_can_overwrite_properties()
    {
        $client = new Client('test_retail:public', 'publ1ct3st', 'IP');

        $client->setUsername('changed')->setPassword('changed');
        
        $this->assertEquals('changed', $client->getUsername());
        $this->assertEquals('changed', $client->getPassword());
    }

    /**
     * @test
     */
    public function it_accepts_an_action()
    {
        $client = new Client('test_retail:public', 'publ1ct3st', 'IP');

        $client->setAction('ping');
        
        $this->assertEquals('ping', $client->getAction());
    }

    /**
     * @test
     */
    public function it_accepts_parameters()
    {
        $client = new Client('test_retail:public', 'publ1ct3st', 'IP');

        $client->setParams(['test' => 'test']);
        
        $this->assertArrayHasKey('test', $client->getParams());
    }
}
