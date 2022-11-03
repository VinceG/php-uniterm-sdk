<?php

declare(strict_types=1);

namespace Uniterm;

use Uniterm\Response;
use Webmozart\Assert\Assert;
use GuzzleHttp\Client as HttpClient;

class Client
{
    const REQ_INPUT_TYPE_PHONE = 'PHONENUM';
    const REQ_INPUT_TYPE_ZIP = 'ZIP';
    const REQ_INPUT_TYPE_TIP = 'TIP';
    const REQ_INPUT_TYPE_EMAIL = 'EMAIL';
    const REQ_INPUT_TYPE_INVOICE_NUMBER = 'INVOICENUM';

    /**
     * @property string
     */
    protected $username;
    /**
     * @property string
     */
    protected $password;
    /**
     * @property string
     */
    protected $device;
    /**
     * Timeout in seconds
     * @property string
     */
    protected $timeout = 120;
    /**
     * @property string
     */
    protected $location = 'https://localhost:8123';
    /**
     * @property string
     */
    protected $deviceType = 'ingenico_rba';
    /**
     * @property string
     */
    protected $deviceFlags = 'DEVICEONLY';
    /**
     * @property string
     */
    protected $identifier = 1;
    /**
     * @property string
     */
    protected $action;
    /**
     * @property array
     */
    protected $params = [];
    /**
     * @property XMLBuilder
     */
    protected $xmlBuilder;

    /**
     * Create a new client instance
     * @property string $username
     * @property string $password
     * @property string $device
     */
    public function __construct(string $username, string $password, string $device)
    {
        $this->setUsername($username)
                ->setPassword($password)
                ->setDevice($device);
    }

    /**
     * Ping request action
     * @property array $params
     */
    public function ping(array $params = [])
    {
        return $this->setAction('ping')->dispatch($params);
    }

    /**
     * Reboot device
     * @property array $params
     */
    public function reboot(array $params = [])
    {
        return $this->setAction('devicereboot')->dispatch($params);
    }

    /**
     * Get Status
     * @property string|int $id
     */
    public function status($id)
    {
        return $this->setAction('status')->dispatch(['u_id' => $id]);
    }

    /**
     * Get Version
     * @property array $params
     */
    public function version(array $params = [])
    {
        return $this->setAction('version')->dispatch($params);
    }

    /**
     * Shutdown
     * @property array $params
     */
    public function shutdown(array $params = [])
    {
        return $this->setAction('shutdown')->dispatch($params);
    }

    /**
     * Cancel
     * @property string|int $id
     * @property array $params
     */
    public function cancel($id, array $params = [])
    {
        return $this->setAction('cancel')->dispatch(['u_id' => $id] + $params);
    }

    /**
     * Upload File
     * @property string $fileName
     * @property string $fileContents - base64 encoded
     * @property array $params
     */
    public function upload($fileName, $fileContents, array $params = [])
    {
        return $this->setAction('deviceupload')->dispatch(['u_filename' => $fileName, 'u_b64data' => $fileContents] + $params);
    }

    /**
     * Device Info
     * @property array $params
     */
    public function info(array $params = [])
    {
        return $this->setAction('deviceinfo')->dispatch($params);
    }

    /**
     * Load Device
     * @property array $params
     */
    public function load(array $params = [])
    {
        return $this->setAction('deviceload')->dispatch($params);
    }

    /**
     * Request Signature
     * @property array $params
     */
    public function signature(array $params = [])
    {
        return $this->setAction('reqsignature')->dispatch($params);
    }

    /**
     * Request Confirmation
     * @property array $params
     */
    public function confirm(string $message, array $params = [])
    {
        return $this->setAction('reqconfirm')->dispatch(['u_message' => $message] + $params);
    }

    /**
     * Request Input
     * @property array $params
     */
    public function input(string $type, array $params = [])
    {
        Assert::oneOf($type, [
            static::REQ_INPUT_TYPE_PHONE,
            static::REQ_INPUT_TYPE_ZIP,
            static::REQ_INPUT_TYPE_TIP,
            static::REQ_INPUT_TYPE_EMAIL,
            static::REQ_INPUT_TYPE_INVOICE_NUMBER
        ]);

        return $this->setAction('reqinput')->dispatch(['u_inputtype' => $type] + $params);
    }

    /**
     * Complete a previously authorized transaction
     * @property string|int $transactionId
     * @property array $params
     */
    public function authorizeComplete($transactionId, array $params = [])
    {
        return $this->passThrough('force', $transactionId, $params);
    }

    /**
     * Complete a previously authorized transaction
     * @property string|int $transactionId
     * @property array $params
     */
    public function reversal($transactionId, array $params = [])
    {
        return $this->passThrough('reversal', $transactionId, $params);
    }

    /**
     * Void a transaction
     * @property string|int $transactionId
     * @property array $params
     */
    public function void($transactionId, array $params = [])
    {
        return $this->passThrough('void', $transactionId, $params);
    }

    /**
     * Complete a passthrough action
     * 
     * @property array $params
     */
    protected function passThrough(string $action, $id, array $params)
    {
        return $this->setAction('passthrough')->dispatch(['action' => $action, 'ttid' => $id] + $params);
    }

    /**
     * Perform a sale transaction
     * @property string|int $amount
     * @property string|int $uniqueId
     * @property array $params
     */
    public function sale($amount, $uniqueId, array $params = [])
    {
        return $this->internalTransaction('txnrequest', 'sale', $amount, $uniqueId, $params);
    }

    /**
     * Perform a transaction start
     * @property string|int $uniqueId
     * @property array $params
     */
    public function startTransaction($uniqueId, array $params = [])
    {
        return $this->internalTransaction('txnstart', 'sale', null, $uniqueId, $params);
    }

    /**
     * Perform a transaction start
     * @property string|int $amount
     * @property string|int $uniqueId
     * @property array $params
     */
    public function completeTransaction($amount, $uniqueId, array $params = [])
    {
        return $this->internalTransaction('txnfinish', 'sale', $amount, $uniqueId, $params);
    }

    /**
     * Pre-Authorize transaction
     * @property string|int $amount
     * @property string|int $uniqueId
     * @property array $params
     */
    public function authorize($amount, $uniqueId, array $params = [])
    {
        return $this->internalTransaction('txnrequest', 'preauth', $amount, $uniqueId, $params);
    }

    /**
     * Refund an amount
     * @property string|int $amount
     * @property string|int $uniqueId
     * @property array $params
     */
    public function refund($amount, $uniqueId, array $params = [])
    {
        return $this->internalTransaction('txnrequest', 'return', $amount, $uniqueId, $params);
    }

    /**
     * Perform a transaction
     * 
     * @property array $params
     */
    protected function internalTransaction(string $action, string $subAction, $amount, $uniqueId, array $params = [])
    {
        Assert::notEmpty($action);
        Assert::notEmpty($subAction);
        Assert::notEmpty($uniqueId);

        return $this->setAction($action)->dispatch([
            'action' => $subAction,
            'amount' => $amount,
            'u_id' => $uniqueId,
            'nsf' => 'yes',
            'laneid' => data_get($params, 'laneid', 1)
        ] + $params);
    }

    protected function dispatch(array $params = [])
    {
        if(count($params) > 0) {
            $this->setParams($params);
        }

        $this->setXmlBuilder(new XMLBuilder($this));

        $xml = $this->getXmlBuilder()->asXml();

        $response = $this->createClient()->post('', ['body' => $xml]);

        return new Response($response);
    }

    protected function createClient()
    {
        return new HttpClient([
            'base_uri' => $this->location,
            'timeout'  => $this->timeout,
            'headers' => [
                'Content-Type' => 'text/xml'
            ],
            'verify' => false
        ]);
    }

    /**
     * Get the value of username
     */ 
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set the value of username
     *
     * @return  self
     */ 
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get the value of password
     */ 
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set the value of password
     *
     * @return  self
     */ 
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get the value of device
     */ 
    public function getDevice()
    {
        return $this->device;
    }

    /**
     * Set the value of device
     *
     * @return  self
     */ 
    public function setDevice($device)
    {
        $this->device = $device;

        return $this;
    }

    /**
     * Get timeout in seconds
     */ 
    public function getTimeout()
    {
        return $this->timeout;
    }

    /**
     * Set timeout in seconds
     *
     * @return  self
     */ 
    public function setTimeout($timeout)
    {
        $this->timeout = $timeout;

        return $this;
    }

    /**
     * Get the value of location
     */ 
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * Set the value of location
     *
     * @return  self
     */ 
    public function setLocation($location)
    {
        $this->location = $location;

        return $this;
    }

    /**
     * Get the value of deviceType
     */ 
    public function getDeviceType()
    {
        return $this->deviceType;
    }

    /**
     * Set the value of deviceType
     *
     * @return  self
     */ 
    public function setDeviceType($deviceType)
    {
        $this->deviceType = $deviceType;

        return $this;
    }

    /**
     * Get the value of deviceFlags
     */ 
    public function getDeviceFlags()
    {
        return $this->deviceFlags;
    }

    /**
     * Set the value of deviceFlags
     *
     * @return  self
     */ 
    public function setDeviceFlags($deviceFlags)
    {
        $this->deviceFlags = $deviceFlags;

        return $this;
    }

    /**
     * Get the value of identifier
     */ 
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * Set the value of identifier
     *
     * @return  self
     */ 
    public function setIdentifier($identifier)
    {
        $this->identifier = $identifier;

        return $this;
    }

    /**
     * Get the value of xmlBuilder
     */ 
    public function getXmlBuilder()
    {
        return $this->xmlBuilder;
    }

    /**
     * Set the value of xmlBuilder
     *
     * @return  self
     */ 
    public function setXmlBuilder($xmlBuilder)
    {
        $this->xmlBuilder = $xmlBuilder;

        return $this;
    }

    /**
     * Get the value of action
     */ 
    public function getAction()
    {
        return $this->action;
    }

    /**
     * Set the value of action
     *
     * @return  self
     */ 
    public function setAction($action)
    {
        $this->action = $action;

        return $this;
    }

    /**
     * Get the value of params
     */ 
    public function getParams()
    {
        return $this->params;
    }

    /**
     * Set the value of params
     *
     * @return  self
     */ 
    public function setParams(array $params)
    {
        $this->params = $params;

        return $this;
    }
}
