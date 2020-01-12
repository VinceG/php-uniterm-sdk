<?php

declare(strict_types=1);

namespace Uniterm;

use Uniterm\Client;
use Sabre\Xml\Service;

class XMLBuilder
{
    /**
     * @property Sabre\Xml\Service
     */
    protected $writer;
    /**
     * @property Client
     */
    protected $client;

    public function __construct(Client $client)
    {
        $this->setClient($client)->setWriter(new Service);
    }

    protected function build()
    {
        return $this->writer->write('MonetraTrans', [
            [
                'name' => 'Trans',
                'attributes' => [
                    'identifier' => (string) $this->client->getIdentifier(),
                ],
                'value' => [
                    'username' => $this->client->getUsername(),
                    'password' => $this->client->getPassword(),
                    'u_action' => $this->client->getAction(),
                    'u_device' => $this->client->getDevice(),
                    'u_devicetype' => $this->client->getDeviceType(),
                    'u_flags' => $this->client->getDeviceFlags()
                ] + $this->params()
            ]
        ]);
    }

    protected function params()
    {
        $list = [];

        foreach($this->client->getParams() as $key => $value) {
            $list[$key] = $value;
        }

        return $list;
    }

    public function asXml()
    {
        return $this->build();
    }

    /**
     * Get the value of client
     */ 
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Set the value of client
     *
     * @return  self
     */ 
    public function setClient($client)
    {
        $this->client = $client;

        return $this;
    }

    /**
     * Get the value of writer
     */ 
    public function getWriter()
    {
        return $this->writer;
    }

    /**
     * Set the value of writer
     *
     * @return  self
     */ 
    public function setWriter($writer)
    {
        $this->writer = $writer;

        return $this;
    }
}
