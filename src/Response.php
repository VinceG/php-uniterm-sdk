<?php

declare(strict_types=1);

namespace Uniterm;

use GuzzleHttp\Psr7\Response as GuzzleResponse;

class Response
{
    const DENY = 'DENY';
    const SUCCESS = 'SUCCESS';
    const PENDING = 'PENDING_TRAN';
    const ID_NOT_FOUND = 'UID_NOT_FOUND';

    /**
     * Guzzle Response
     * @property GuzzleResponse
     */
    protected $httpResponse;
    /**
     * Guzzle Response Body
     * @property string
     */
    protected $body;
    /**
     * XML String
     * @property string
     */
    protected $xml;
    /**
     * Response Data
     * @property array
     */
    protected $responseData;

    public function __construct(GuzzleResponse $response)
    {
        $this->setHttpResponse($response)
                ->setBody((string) $response->getBody())
                ->parse();
    }

    public function device()
    {
        return collect([
            'app' => data_get($this->response(), 'device_app'),
            'appver' => data_get($this->response(), 'device_appver'),
            'encryption' => data_get($this->response(), 'device_encryption'),
            'kernver' => data_get($this->response(), 'device_kernver'),
            'manuf' => data_get($this->response(), 'device_manuf'),
            'manuf_sn' => data_get($this->response(), 'device_manuf_sn'),
            'model' => data_get($this->response(), 'device_model'),
            'serialnum' => $this->serialNumber()
        ]);
    }

    public function serialNumber()
    {
        return data_get($this->response(), 'serialnum');
    }

    public function isConfirmed()
    {
        return data_get($this->response(), 'u_confirmed') === 'yes';
    }

    public function isPending()
    {
        return $this->errorCode() == static::PENDING;
    }

    public function isSuccess()
    {
        return $this->errorCode() == static::SUCCESS;
    }

    public function isError()
    {
        return ! $this->isSuccess();
    }

    public function cardHolderName()
    {
        return data_get($this->response(), 'cardholdername');
    }

    public function cardType()
    {
        return data_get($this->response(), 'cardtype');
    }

    public function merchant()
    {
        return collect([
            'address1' => data_get($this->response(), 'merch_addr1'),
            'address2' => data_get($this->response(), 'merch_addr2'),
            'address3' => data_get($this->response(), 'merch_addr3'),
            'email' => data_get($this->response(), 'merch_email'),
            'name' => data_get($this->response(), 'merch_name'),
            'phone' => data_get($this->response(), 'merch_phone'),
            'url' => data_get($this->response(), 'merch_url'),
            'processor' => data_get($this->response(), 'merch_proc')
        ]);
    }

    public function account()
    {
        return data_get($this->response(), 'account');
    }

    public function auth()
    {
        return data_get($this->response(), 'auth');
    }

    public function batch()
    {
        return data_get($this->response(), 'batch');
    }

    public function version()
    {
        return data_get($this->response(), 'u_version');
    }

    public function input()
    {
        return data_get($this->response(), 'u_input');
    }

    public function id()
    {
        return data_get($this->response(), 'u_id');
    }

    public function signature()
    {
        return data_get($this->response(), 'u_signature');
    }

    public function verbiage()
    {
        return data_get($this->response(), 'verbiage');
    }

    public function errorCode()
    {
        return data_get($this->response(), 'u_errorcode');
    }

    public function code()
    {
        return data_get($this->response(), 'code');
    }

    public function softCode()
    {
        return data_get($this->response(), 'msoft_code');
    }

    /**
     *  Indicates how the card data was captured. 
     *  Possible values are:
     *   • G: Keyed entry (EMV Fallback)
     *   • M: Keyed entry
     *   • T: EMV Contactless
     *   • C: EMV Contact
     *   • F: Swipe (EMV Fallback)
     *   • R: MSD (RFID) Contactless
     *   • S: Swipe
     *   • I: MICR Check Read
     */
    public function entryMode()
    {
        return data_get($this->response(), 'rcpt_entry_mode');
    }

    public function ts()
    {
        return data_get($this->response(), 'rcpt_host_ts');
    }

    public function ttid()
    {
        return data_get($this->response(), 'ttid');
    }

    public function transactionId()
    {
        return $this->ttid();
    }

    public function cardClass()
    {
        return data_get($this->response(), 'u_cardclass');
    }

    public function flowFlags()
    {
        return data_get($this->response(), 'u_flowflags');
    }

    public function item()
    {
        return data_get($this->response(), 'item');
    }

    public function identifier()
    {
        return data_get($this->response(), '@attributes.identifier');
    }

    public function response()
    {
        return data_get($this->getResponseData(), 'Resp');
    }

    public function transferCode()
    {
        return data_get($this->getResponseData(), 'DataTransferStatus.@attributes.code');
    }

    protected function parse()
    {
        $this->xml = simplexml_load_string($this->getBody());
        $this->setResponseData(json_decode(json_encode($this->xml), true));
    }

    /**
     * Get guzzle Response Body
     */ 
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Set guzzle Response Body
     *
     * @return  self
     */ 
    public function setBody($body)
    {
        $this->body = $body;

        return $this;
    }

    /**
     * Get xML String
     */ 
    public function getXml()
    {
        return $this->xml;
    }

    /**
     * Set xML String
     *
     * @return  self
     */ 
    public function setXml($xml)
    {
        $this->xml = $xml;

        return $this;
    }

    /**
     * Get response Data
     */ 
    public function getResponseData()
    {
        return $this->responseData;
    }

    /**
     * Set response Data
     *
     * @return  self
     */ 
    public function setResponseData($responseData)
    {
        $this->responseData = $responseData;

        return $this;
    }

    /**
     * Get guzzle Response
     */ 
    public function getHttpResponse()
    {
        return $this->httpResponse;
    }

    /**
     * Set guzzle Response
     *
     * @return  self
     */ 
    public function setHttpResponse($httpResponse)
    {
        $this->httpResponse = $httpResponse;

        return $this;
    }

    /**
     * Get guzzle Response
     */ 
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Set guzzle Response
     *
     * @return  self
     */ 
    public function setResponse($response)
    {
        $this->response = $response;

        return $this;
    }
}
