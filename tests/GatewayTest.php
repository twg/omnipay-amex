<?php

namespace Omnipay\Amex;

use Omnipay\Tests\GatewayTestCase;

class GatewayTest extends GatewayTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->gateway = new Gateway($this->getHttpClient(), $this->getHttpRequest());
        $this->gateway->initialize(array(
          'username' => 'test',
          'password' => 'test'
        ));
    }

    // Override broken test in base class of omnipay-tests lib.
    public function testPurchaseParameters()
    {
        if ($this->gateway->supportsPurchase()) {
            foreach ($this->gateway->getDefaultParameters() as $key => $default) {
                // set property on gateway
                $getter = 'get'.ucfirst($key);
                $setter = 'set'.ucfirst($key);
                $value = uniqid();
                $this->gateway->$setter($value);

                // request should have matching property, with correct value
                $request = $this->gateway->purchase();
                $this->assertSame($value, $request->$getter());
            }
        }
    }

    public function testAuthorizeDeclined()
    {
        $this->setMockHttpResponse('AuthorizeDeclined.txt');
        $request = $this->gateway->authorize(array(
          'amount' => '1.00',
          'orderId' => '123',
          'card'   => $this->getValidCard()
        ));

        $this->assertInstanceOf('\Omnipay\Amex\Message\AuthorizeRequest', $request);
        $this->assertSame('1.00', $request->getAmount());

        $response = $request->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertSame('3877', $response->getTransactionReference());
        $this->assertFalse($response->isApproved());
        $this->assertSame('2', $response->getCode());
        $this->assertSame('Declined', $response->getMessage());
    }
}
