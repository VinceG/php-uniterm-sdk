<?php

declare(strict_types=1);

namespace Uniterm;

use Uniterm\TestCase;
use Uniterm\XMLBuilder;

class XMLBuilderTest extends TestCase
{
    /**
     * @test
     */
    public function it_can_create_an_xml_builder_instance()
    {
        $client = (new Client('test_retail:public', 'publ1ct3st', 'IP'));
        $builder = new XMLBuilder($client);

        $this->assertXmlStringEqualsXmlString(
            '<?xml version="1.0"?>
            <MonetraTrans>
                <Trans identifier="1">
                    <username>test_retail:public</username>
                    <password>publ1ct3st</password>
                    <u_action/>
                    <u_device>IP</u_device>
                    <u_devicetype>ingenico_rba</u_devicetype>
                    <u_flags>DEVICEONLY</u_flags>
                </Trans>
            </MonetraTrans>',
            $builder->asXml()
        );
    }
    
    /**
     * @test
     */
    public function it_accepts_custom_params()
    {
        $client = new Client('test_retail:public', 'publ1ct3st', 'IP');
        $client->setAction('ping')->setParams(['test' => 'abc']);
        $builder = new XMLBuilder($client);

        $this->assertXmlStringEqualsXmlString(
            '<?xml version="1.0"?>
            <MonetraTrans>
                <Trans identifier="1">
                    <username>test_retail:public</username>
                    <password>publ1ct3st</password>
                    <u_action>ping</u_action>
                    <u_device>IP</u_device>
                    <u_devicetype>ingenico_rba</u_devicetype>
                    <u_flags>DEVICEONLY</u_flags>
                    <test>abc</test>
                </Trans>
            </MonetraTrans>',
            $builder->asXml()
        );
    }

    /**
     * @test
     */
    public function it_can_accept_new_action()
    {
        $client = new Client('test_retail:public', 'publ1ct3st', 'IP');
        $client->setAction('ping')->setParams(['test' => 'abc']);
        $builder = new XMLBuilder($client);

        $this->assertXmlStringEqualsXmlString(
            '<?xml version="1.0"?>
            <MonetraTrans>
                <Trans identifier="1">
                    <username>test_retail:public</username>
                    <password>publ1ct3st</password>
                    <u_action>ping</u_action>
                    <u_device>IP</u_device>
                    <u_devicetype>ingenico_rba</u_devicetype>
                    <u_flags>DEVICEONLY</u_flags>
                    <test>abc</test>
                </Trans>
            </MonetraTrans>',
            $builder->asXml()
        );

        $client->setAction('cancel')->setParams([]);

        $this->assertXmlStringEqualsXmlString(
            '<?xml version="1.0"?>
            <MonetraTrans>
                <Trans identifier="1">
                    <username>test_retail:public</username>
                    <password>publ1ct3st</password>
                    <u_action>cancel</u_action>
                    <u_device>IP</u_device>
                    <u_devicetype>ingenico_rba</u_devicetype>
                    <u_flags>DEVICEONLY</u_flags>
                </Trans>
            </MonetraTrans>',
            $builder->asXml()
        );
    }
}
